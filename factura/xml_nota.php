<?php

    require './../robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    # Procedimiento para enviar comprobante a la SUNAT
    class feedSoap extends SoapClient{
        public $XMLStr = "";
        public function setXMLStr($value){
            $this->XMLStr = $value;
        }
        public function getXMLStr(){
            return $this->XMLStr;
        }
        public function __doRequest($request, $location, $action, $version, $one_way = 0){
            $request = $this->XMLStr;
            $dom = new DOMDocument('1.0');
            try
            {
                $dom->loadXML($request);
            } catch (DOMException $e) {
                die($e->code);
            }
            $request = $dom->saveXML();
            //Solicitud
            return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
        }
        public function SoapClientCall($SOAPXML){
            return $this->setXMLStr($SOAPXML);
        }
    }


    function soapCall($wsdlURL, $callFunction = "", $XMLString){
        $client = new feedSoap($wsdlURL, array('trace' => true));
        $reply  = $client->SoapClientCall($XMLString);
        $client->__call("$callFunction", array(), array());
        return $client->__getLastResponse();
    }

    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

try {

    try{
        require ('conexion.php');
        date_default_timezone_set('America/Lima');




        /* PARAMETROS GET
        ***********************************/
        $gen = $_GET['gen'];
        $emp = $_GET['emp'];
        $tip = $_GET['tip'];
        $num = $_GET['num'];


        /* CONSULTA CAB_DOC_GEN
        *****************************************************************************/
        $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_tip_doc='".$tip."' and cdg_num_doc='".$num."'";
        $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
        oci_execute($sql_parse);
        oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];

        /* FECHA 26-07-2017
         ********************/
        $fecha = date("Y-m-d", strtotime($cab_doc_gen['CDG_FEC_GEN']));

        /* DOC Y SERIE 01-F001
        *******************/
        if($cab_doc_gen['CDG_TIP_DOC'] == 'F'){
            $doc = '01';
            $doc_nombre = 'FACTURA ELECTRÓNICA';
            $serie = 'F00'.$cab_doc_gen['CDG_SER_DOC'];
        }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'B'){
            $doc = '03';
            $serie = 'B00'.$cab_doc_gen['CDG_SER_DOC'];
            $doc_nombre = 'BOLETA ELECTRÓNICA';
        }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'A'){
            $doc = '07';
            $doc_nombre = 'NOTA CREDITO ELECTRÓNICA';
            if($cab_doc_gen['CDG_TIP_REF'] == 'BR' || $cab_doc_gen['CDG_TIP_REF'] == 'BS'){
                $serie = 'BN0'.$cab_doc_gen['CDG_SER_DOC'];
            }elseif($cab_doc_gen['CDG_TIP_REF'] == 'FR' || $cab_doc_gen['CDG_TIP_REF'] == 'FS' || $cab_doc_gen['CDG_TIP_REF'] == 'FC'){
                $serie = 'FN0'.$cab_doc_gen['CDG_SER_DOC'];
            }
        }

        /*  RUC O DNI
         *******************/
        if(strlen(trim($cab_doc_gen['CDG_DOC_CLI']))==11){
            $tipo_doc = 'RUC';
            $tipo_doc_num=6;
        }elseif(strlen(trim($cab_doc_gen['CDG_DOC_CLI']))==8){
            $tipo_doc = 'DNI';
            $tipo_doc_num=1;
        }else{
            $tipo_doc = 'Carnet Extranj';
            $tipo_doc_num=4;
        }


        /* ITEMS
         ***********************************/
        $i=0;
        if($cab_doc_gen['CDG_TIP_IMP'] != 'R'){
            $sql_repuestos = "select * from det_doc_rep inner join LIS_PRE_REP on lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro where DDR_COD_GEN='".$cab_doc_gen['CDG_COD_GEN']."' and DDR_COD_EMP='".$cab_doc_gen['CDG_COD_EMP']."' and DDR_NUM_DOC='".$cab_doc_gen['CDG_NUM_DOC']."' and DDR_CLA_DOC='".$cab_doc_gen['CDG_CLA_DOC']."' ORDER BY rownum Desc";
            $sql_repuestos_parse = oci_parse($conn,$sql_repuestos);
            oci_execute($sql_repuestos_parse);
            oci_fetch_all($sql_repuestos_parse, $repuestos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            foreach ($repuestos as $repuesto){
                $items[$i]['codigo'] = $repuesto['DDR_COD_PRO']; // codigo
                $items[$i]['descripcion'] = $repuesto['LPR_DES_PRO']; // descripcion
                $items[$i]['cantidad'] = $repuesto['DDR_CAN_PRO']; // cantidad
                $items[$i]['unitario'] = number_format($repuesto['DDR_VVP_SOL'],2,'.',''); // precio unitario
                $items[$i]['importe'] = number_format(($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']), 2, '.',''); // importe
                $items[$i]['descuento'] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES'])/100), 2, '.', ''); // descuento esta en % hay que sacarle del importe
                $items[$i]['venta'] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']) - (($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES'])/100)),2,'.',''); // valor venta (importe - descuento)
                $i++;
            }
        }

        if($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
            $sql_servicios = "select * from det_doc_ser where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
            $sql_servicios_parse = oci_parse($conn, $sql_servicios);
            oci_execute($sql_servicios_parse);
            oci_fetch_all($sql_servicios_parse, $servicios, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            foreach ($servicios as $servicio) {
                $items[$i]['codigo'] = $servicio['DDS_COD_PRO']; // codigo
                $items[$i]['descripcion'] = $servicio['DDS_DES_001']; // descripcion
                $items[$i]['cantidad'] = $servicio['DDS_CAN_PRO']; // cantidad
                $items[$i]['unitario'] = number_format($servicio['DDS_VVP_SOL'],2,'.',''); // precio unitario
                $items[$i]['importe'] = number_format(($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']), 2, '.', ''); // importe
                $items[$i]['descuento'] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES'])/100), 2, '.', ''); // descuento
                $items[$i]['venta'] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']) - (($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES'])/100)),2,'.',''); // valor venta (importe - descuento)
                $i++;
            }
        }

        if($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
            $sql_otros = "select * from det_doc_otr where DDO_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDO_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDO_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDO_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
            $sql_otros_parse = oci_parse($conn, $sql_otros);
            oci_execute($sql_otros_parse);
            oci_fetch_all($sql_otros_parse, $otros, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            foreach ($otros as $otro) {  // DDO_DES_OTR
                $items[$i]['codigo'] = '';
                $items[$i]['descripcion'] = $otro['DDO_DES_OTR'];
                $items[$i]['cantidad'] = '';
                $items[$i]['unitario'] = '';
                $items[$i]['importe'] = '';
                $items[$i]['descuento'] = '';
                $items[$i]['venta'] = '';
                $i++;
            }
        }

        if($cab_doc_gen['CDG_TIP_IMP'] == 'R') { // solo si es resumen se imprime cdg_ten_res, nunca va ver un R que sea AN
            if ($cab_doc_gen['CDG_TEN_RES'] != '') {
                $items[$i]['codigo'] = '-- -- --';
                $items[$i]['descripcion'] = $cab_doc_gen['CDG_TEN_RES'];
                $items[$i]['cantidad'] = '1';
                if($cab_doc_gen['CDG_EXI_FRA'] == 'S'){
                    $items[$i]['unitario'] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.','');
                    $items[$i]['importe'] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.','');
                    $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',',');
                    $items[$i]['venta'] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))-$cab_doc_gen['CDG_DES_TOT']),2,'.','');
                }else{
                    $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');
                    $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');
                    $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',''); //descuentos
                    $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.','');  // gravadas cdg_vvp_tot-cdg_des_tot;
                }
            }
        }

        // anticipo pero factura
        if($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] != 'A') { // solo si es anticipo se imprime la nota en arriba anticipo es contado
            $items[$i]['codigo'] = '-- -- --';
            $items[$i]['descripcion'] = $cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003'];
            $items[$i]['cantidad'] = '1';
            $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',''); // precio unitario
            $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',''); //importe
            $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',''); //descuentos
            $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.','');  // gravadas cdg_vvp_tot-cdg_des_tot
        }

        // anticipo pero nota de credito
        if($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] == 'A') {
            $sql_nota = "select cdg_not_001,cdg_not_002,cdg_not_003 from cab_doc_gen where cdg_cod_gen ='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_REF']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_REF']."'";
            $sql_nota_parse = oci_parse($conn, $sql_nota);
            oci_execute($sql_nota_parse);
            oci_fetch_all($sql_nota_parse, $nota, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            $items[$i]['codigo'] = '-- -- --';
            $items[$i]['descripcion'] = $nota[0]['CDG_NOT_001'].' '.$nota[0]['CDG_NOT_002'].' '.$nota[0]['CDG_NOT_003'];
            $items[$i]['cantidad'] = '1';
            $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');;
            $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');;
            $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.','');;
            $items[$i]['venta'] = number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.',''); // total cdg_imp_neto
            //print_r($nota);
        }

        //print_r($items);

        /* TOTALES
        ***********************************************/
        if($cab_doc_gen['CDG_EXI_FRA'] == 'S'){
            $subtotal = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.','');
            $gravadas = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))-$cab_doc_gen['CDG_DES_TOT']),2,'.','');
            $igv = number_format(($cab_doc_gen['CDG_IGV_TOT'] -($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))*($cab_doc_gen['CDG_POR_IGV']/100)),2,'.','');
        }else{
            $subtotal = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');
            $gravadas = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.','');  // gravadas cdg_vvp_tot-cdg_des_tot
            $igv = number_format($cab_doc_gen['CDG_IGV_TOT'],2,'.',''); // igv total
        }
        $descuentos = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.','');
        $total = number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.',''); // total cdg_imp_neto



        /*REFERENCIA 0:sin  1:nota  2:franquisia 3:anticipo
        *****************************************************/
        if($cab_doc_gen['CDG_TIP_DOC']=='A'){
            $reference = 1;
            $sql_ref = "select * from cab_doc_gen where cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_REF']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_REF']."'";
            $sql_ref_parse = oci_parse($conn, $sql_ref);
            oci_execute($sql_ref_parse);
            oci_fetch_all($sql_ref_parse, $ref, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            $ref_doc = $ref[0]['CDG_TIP_DOC'][0].'00'.$ref[0]['CDG_SER_DOC'].'-'.$ref[0]['CDG_NUM_DOC'];
            $ref_fecha = date("d-m-Y", strtotime($ref[0]['CDG_FEC_GEN']));

            if($ref[0]['CDG_TIP_DOC'] == 'F'){
                $doc_ref_tipo = '01';
            }elseif($ref[0]['CDG_TIP_DOC'] == 'B'){
                $doc_ref_tipo = '03';
            }elseif($ref[0]['CDG_TIP_DOC'] == 'A'){
                $doc_ref_tipo = '07';
            }

            //print_r($ref);
            //echo $ref_serie;
        }elseif($cab_doc_gen['CDG_EXI_FRA']=='S' && $cab_doc_gen['CDG_TIP_DOC'] !='A'){
            $reference = 2;
        }elseif($cab_doc_gen['CDG_EXI_ANT']=='AN' && $cab_doc_gen['CDG_TIP_DOC'] !='A'){
            $reference = 3;
        }else{
            $reference = 0;
        }

        $xml = new DomDocument('1.0', 'UTF-8');
        $xml->preserveWhiteSpace = false;
        $CreditNote = $xml->createElement('CreditNote'); $CreditNote = $xml->appendChild($CreditNote);
        $CreditNote->setAttribute('xmlns',"urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2");
        $CreditNote->setAttribute('xmlns:cac',"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
        $CreditNote->setAttribute('xmlns:cbc',"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
        $CreditNote->setAttribute('xmlns:ccts',"urn:un:unece:uncefact:documentation:2");
        $CreditNote->setAttribute('xmlns:ds',"http://www.w3.org/2000/09/xmldsig#");
        $CreditNote->setAttribute('xmlns:ext',"urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
        $CreditNote->setAttribute('xmlns:qdt',"urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
        $CreditNote->setAttribute('xmlns:sac',"urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
        $CreditNote->setAttribute('xmlns:udt',"urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");
        $CreditNote->setAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");

        $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $CreditNote->appendChild($UBLExtension);
            $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
                $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);
                    $sac = $xml->createElement('sac:AdditionalInformation'); $sac = $contents->appendChild($sac);
                        // el 2005 es Total descuentos
                        $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '2005'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $descuentos); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        // El 1001 total velor venta - operaciones gravadas1
                        $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1001'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $gravadas); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        // el 1002 total valor venta - operaciones inafectas
                        $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1002'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        // el 1003 total valor venta - operaciones exoneradas
                        $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1003'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            // 2.- Firma electronica
            $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent', ' '); $contents = $ext->appendChild($contents);

        // 36. Version del UBL
        $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $CreditNote->appendChild($cbc);
        // 37.- Version de la estructura del documento
        $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $CreditNote->appendChild($cbc);
        // 8.- Numeracion , conformada por serie y numero correlativo B001-00012926
        $cbc = $xml->createElement('cbc:ID', $serie.'-'.$cab_doc_gen['CDG_NUM_DOC']); $cbc = $CreditNote->appendChild($cbc);
        // 1.- Fecha de emision 2017-04-13
        $cbc = $xml->createElement('cbc:IssueDate', $fecha); $cbc = $CreditNote->appendChild($cbc);
        // 28.- Tipo de moneda en la cual se emite la factura electronica $c19
        $cbc = $xml->createElement('cbc:DocumentCurrencyCode', 'PEN'); $cbc = $CreditNote->appendChild($cbc);

        $cac = $xml->createElement('cac:DiscrepancyResponse'); $cac = $CreditNote->appendChild($cac);
        $cbc = $xml->createElement('cbc:ReferenceID',$ref_doc); $cbc = $cac->appendChild($cbc);
        $cbc = $xml->createElement('cbc:ResponseCode','07'); $cbc = $cac->appendChild($cbc);
        $cbc = $xml->createElement('cbc:Description',$cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003']); $cbc = $cac->appendChild($cbc);

        $BillingReference = $xml->createElement('cac:BillingReference'); $BillingReference = $CreditNote->appendChild($BillingReference);
        $cac = $xml->createElement('cac:InvoiceDocumentReference'); $cac = $BillingReference->appendChild($cac);
        $cbc = $xml->createElement('cbc:ID',$ref_doc); $cbc = $cac->appendChild($cbc);
        $cbc = $xml->createElement('cbc:DocumentTypeCode',$doc_ref_tipo); $cbc = $cac->appendChild($cbc);


        // 2.- Parte de la firma electronica. esto es de quien creo la firma electronica
        $cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $CreditNote->appendChild($cac_signature);
        $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac_signature->appendChild($cbc);
        $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
        $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
        $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
        $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
        $cbc = $xml->createElement('cbc:Name', 'SURMOTRIZ S.R.L'); $cbc = $cac->appendChild($cbc);
        $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
        $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
        $cbc = $xml->createElement('cbc:URI', 'SIGN'); $cbc = $cac->appendChild($cbc);


        // DATOS EMISOR
        $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
        $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
        $cbc = $xml->createElement('cbc:ID', '220101'); $cbc = $address->appendChild($cbc);//ubigeo
        $cbc = $xml->createElement('cbc:StreetName', 'AV. LEGUIA NRO. 1870'); $cbc = $address->appendChild($cbc);// Direccion
        $cbc = $xml->createElement('cbc:CitySubdivisionName', 'FRENTE A I.E. JOSE ROSA ARA'); $cbc = $address->appendChild($cbc);// urbanizacion
        $cbc = $xml->createElement('cbc:CityName', 'TACNA'); $cbc = $address->appendChild($cbc);//departamento
        $cbc = $xml->createElement('cbc:CountrySubentity', 'TACNA'); $cbc = $address->appendChild($cbc);
        $cbc = $xml->createElement('cbc:District', 'TACNA'); $cbc = $address->appendChild($cbc);
        $country = $xml->createElement('cac:Country'); $country = $address->appendChild($country);// pais
        $cbc = $xml->createElement('cbc:IdentificationCode', 'PE'); $cbc = $country->appendChild($cbc);
        $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);// razon social
        $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);

        //DATOS CLIENTE
        $cac_accounting = $xml->createElement('cac:AccountingCustomerParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', $cab_doc_gen['CDG_DOC_CLI']); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', $tipo_doc_num); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
        // nombre o razon zocial
        $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
        $cbc = $xml->createElement('cbc:RegistrationName', $cab_doc_gen['CDG_NOM_CLI']); $cbc = $legal->appendChild($cbc);

        // Sumatoria IGV
        $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNote->appendChild($taxtotal);
        $cbc = $xml->createElement('cbc:TaxAmount', $igv); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
        $cbc = $xml->createElement('cbc:TaxAmount', $igv); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
        $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
        $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
        $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
        $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

        // Importe total de venta
        $legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $CreditNote->appendChild($legal);
        $cbc = $xml->createElement('cbc:PayableAmount', $total); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

        $i=1;
        foreach($items as $item){
            $CreditNoteLine = $xml->createElement('cac:CreditNoteLine'); $CreditNoteLine = $CreditNote->appendChild($CreditNoteLine);
                $cbc = $xml->createElement('cbc:ID', $i); $cbc = $CreditNoteLine->appendChild($cbc);
                $cbc = $xml->createElement('cbc:CreditedQuantity', $item['cantidad']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU"); // cantidad x item:  1
                $cbc = $xml->createElement('cbc:LineExtensionAmount', $item['venta']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                // precio unitario del producto con igv
                $pricing = $xml->createElement('cac:PricingReference'); $pricing = $CreditNoteLine->appendChild($pricing);
                    $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                        // precio unitario con igv
                        $cbc = $xml->createElement('cbc:PriceAmount', $item['unitario']*0.18+$item['unitario']); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        // 01 con igv, 02 operaciones no onerosas
                        $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
                // igv del total del producto aplicado ya el descuento *0.18
                $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNoteLine->appendChild($taxtotal);
                    $cbc = $xml->createElement('cbc:TaxAmount', round($item['venta']*0.18,2)); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                        $cbc = $xml->createElement('cbc:TaxAmount', round($item['venta']*0.18,2)); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                            $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                            $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                                $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                                $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                                $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

            $item1 = $xml->createElement('cac:Item'); $item1 = $CreditNoteLine->appendChild($item1);
                $cbc = $xml->createElement('cbc:Description', $item['descripcion']); $cbc = $item1->appendChild($cbc);
                $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item1->appendChild($sellers);
                    $cbc = $xml->createElement('cbc:ID', $item['codigo']); $cbc = $sellers->appendChild($cbc);
            // precio sin igv ejm 83.05
            $price = $xml->createElement('cac:Price'); $price = $CreditNoteLine->appendChild($price);
            $cbc = $xml->createElement('cbc:PriceAmount', $item['venta']); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $i++;
        }


        $xml->formatOutput = true;

        $nom = '20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'];
        $doc = new DOMDocument();
        $doc->loadXML($xml->saveXML());
        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
            array('force_uri' => true)
        );
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
        $objKey->loadKey('../archivos_pem/private_key.pem', true);
        $objDSig->sign($objKey);
        $objDSig->add509Cert(file_get_contents('../archivos_pem/public_key.pem'), true, false, array('subjectName' => true));
        $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
        $strings_xml = $doc->saveXML();

        /* RUTA   ../app/repo/2017/08/08/
        ************************************************************/
        $ruta = explode('-',$fecha);
        $ruta = '../app/repo/'.$ruta[2].'/'.$ruta[1].'/'.$ruta[0].'/';
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        ## Creación del archivo .ZIP
        $zip = new ZipArchive;
        $res = $zip->open($ruta.$nom.'.zip', ZipArchive::CREATE);
        $zip->addFromString($nom.'.xml', $strings_xml);
        $zip->close();



        $wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
        $XMLString = '<?xml version="1.0" encoding="UTF-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                 <soapenv:Header>
                     <wsse:Security>
                         <wsse:UsernameToken>
                             <wsse:Username>20532710066MODDATOS</wsse:Username>
                             <wsse:Password>MODDATOS</wsse:Password>
                         </wsse:UsernameToken>
                     </wsse:Security>
                 </soapenv:Header>
                 <soapenv:Body>
                     <ser:sendBill>
                        <fileName>'.$nom.'.zip</fileName>
                        <contentFile>'.base64_encode(file_get_contents($ruta.$nom.'.zip')).'</contentFile>
                     </ser:sendBill>
                 </soapenv:Body>
                </soapenv:Envelope>';

        $result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);
        preg_match_all('/<applicationResponse>(.*?)<\/applicationResponse>/is', $result, $matches);
        $archivo = fopen($ruta.'R-'.$nom.'.zip', 'w+');
        fputs($archivo, base64_decode($matches[1][0]));
        fclose($archivo);
        chmod($ruta.'R-'.$nom.'.zip', 0777);
    }catch(Exception $e){

    }

}catch (Exception $e) {
    echo $e->getMessage();
}

?>