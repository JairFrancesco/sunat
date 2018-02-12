<?php

    /*Firmado Electronico*/
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    /*Conexion BD
    ************************/
    include "../conexion.php";


    //0 no pasa, 1 crea xml, 2 solo comprueba
    $cdg_sun_can = 1;
    $nopasa = 0;
    foreach ($boletas_notas as $index => $boletas_nota){
        if ($boletas_nota['CDG_COD_SNT'] == '0001'){
            //aqui va entrar solo las boletas anuladas que no han sido envias 0003
            if ($boletas_nota['CDG_ANU_SN']=='S' && $boletas_nota['CDG_DOC_ANU']=='S' && $boletas_nota['CDG_TIP_DOC'] == 'B' && $boletas_nota['CDG_COD_SNT'] == '0001'){

            }else{
                unset($boletas_notas[$index]); // elimina todos los registrados
            }
        }

        if (($boletas_nota['CDG_COD_SNT'] == '0001' || $boletas_nota['CDG_COD_SNT'] == '0003') && $nopasa == 0){
            $nopasa = 0; // entran todos los que han sido enviados
        }elseif ($nopasa == 2){ // para mantener cuando el primer nopasa = 2
            $nopasa = 2;
        }elseif ($boletas_nota['CDG_COD_SNT'] == '0098'){
            if ($boletas_nota['CDG_SUN_CAN'] == '1'){
                $nopasa = 2; // solo comprueba y obtiene el codigo 0 o si no suma 1 al CDG_SUN_CAN
                $cdg_sun_can = 2;
            }elseif ($boletas_nota['CDG_SUN_CAN'] == '2'){
                $nopasa = 1; // crea xml de cero
            }
        }else{
            $nopasa = 1; // crea xml de cero
        }
    }
    //echo count($boletas_notas);
    //echo $nopasa;
    //print_r($boletas_notas);

    if ($nopasa=='1') {
        /*ruta
          ***********************/
        $ruta = '../../app/resumenes/'.date('Y').'/'.date('m').'/'.date('d').'/';
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $j=1;
        while(file_exists($ruta.'20532710066-RC-'.date('Ymd').'-'.$j.'.zip')){
            $j++;
            // el valor de j es el actual que se va crear
        }

        //fecha
        $fecha_antigua = date("Y-m-d", strtotime($fecha));
        $fecha_actual = date('Y-m-d');

        
        //print_r($boletas_notas);

        // creacion del xml
        $xml = new DomDocument('1.0', 'UTF-8');
        //$xml->preserveWhiteSpace = false;

        $Summary = $xml->appendChild($xml->createElement('p:SummaryDocuments'));
        $Summary->setAttribute('xmlns:p', 'urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1');
        $Summary->setAttribute('xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $Summary->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $Summary->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $Summary->setAttribute('xmlns:sac', 'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1');

        $UBLExtensions = $Summary->appendChild($xml->createElement('ext:UBLExtensions'));
            $UBLExtension = $UBLExtensions->appendChild($xml->createElement('ext:UBLExtension'));
                $ExtensionContent = $UBLExtension->appendChild($xml->createElement('ext:ExtensionContent'));

        $UBLVersionID = $Summary->appendChild($xml->createElement('cbc:UBLVersionID','2.0'));
        $CustomizationID = $Summary->appendChild($xml->createElement('cbc:CustomizationID','1.1'));
        $ID = $Summary->appendChild($xml->createElement('cbc:ID','RC-'.date('Ymd').'-'.$j));
        $ReferenceDate = $Summary->appendChild($xml->createElement('cbc:ReferenceDate',$fecha_antigua));
        $IssueDate = $Summary->appendChild($xml->createElement('cbc:IssueDate',$fecha_actual));

        $Signature = $Summary->appendChild($xml->createElement('cac:Signature'));
            $ID = $Signature->appendChild($xml->createElement('cbc:ID','SRC-'.$fecha_actual.'-'.$j));
            $SignatoryParty = $Signature->appendChild($xml->createElement('cac:SignatoryParty'));
                $PartyIdentification = $SignatoryParty->appendChild($xml->createElement('cac:PartyIdentification'));
                    $ID = $PartyIdentification->appendChild($xml->createElement('cbc:ID','20532710066'));
                $PartyName = $SignatoryParty->appendChild($xml->createElement('cac:PartyName'));
                    $Name = $PartyName->appendChild($xml->createElement('cbc:Name','sunat'));
            $DigitalSignatureAttachment = $Signature->appendChild($xml->createElement('cac:DigitalSignatureAttachment'));
                $ExternalReference = $DigitalSignatureAttachment->appendChild($xml->createElement('cac:ExternalReference'));
                    $URI = $ExternalReference->appendChild($xml->createElement('cbc:URI','#SRC-20171218-900'));

        $AccountingSupplierParty = $Summary->appendChild($xml->createElement('cac:AccountingSupplierParty'));
            $CustomerAssignedAccountID = $AccountingSupplierParty->appendChild($xml->createElement('cbc:CustomerAssignedAccountID','20532710066'));
            $AdditionalAccountID = $AccountingSupplierParty->appendChild($xml->createElement('cbc:AdditionalAccountID','6'));
            $Party = $AccountingSupplierParty->appendChild($xml->createElement('cac:Party'));
                $PartyLegalEntity = $Party->appendChild($xml->createElement('cac:PartyLegalEntity'));
                    $PartyLegalEntity = $PartyLegalEntity->appendChild($xml->createElement('cbc:RegistrationName','SURMOTRIZ SRL'));


        $i = 1;
        foreach ($boletas_notas as $boletas_nota){

            /*  RUC O DNI
            *******************/
            if (strlen(trim($boletas_nota['CDG_DOC_CLI'])) == 11) {
                $tipo_doc = 'RUC';
                $tipo_doc_num = 6;
            } elseif (strlen(trim($boletas_nota['CDG_DOC_CLI'])) == 8) {
                $tipo_doc = 'DNI';
                $tipo_doc_num = 1;
            } else {
                $tipo_doc = 'Carnet Extranj';
                $tipo_doc_num = 4;
            }

            // tipo boleta o nota
            if ($boletas_nota['CDG_TIP_DOC'] == 'B') {
                $cbc_DocumentTypeCode = '03';
                $serieNumero = 'B00'.$boletas_nota['CDG_SER_DOC'].'-'.$boletas_nota['CDG_NUM_DOC'];
                // tipo del estado del item
                if ($boletas_nota['CDG_ANU_SN']=='S' &&  $boletas_nota['CDG_DOC_ANU']=='S'){
                    $cbc_ConditionCode = '3';
                }else{
                    $cbc_ConditionCode = '1';
                }
            }elseif ($boletas_nota['CDG_TIP_DOC'] == 'A'){
                //serie numero ref
                if($boletas_nota['CDG_COD_EMP']=='01'){
                    $serienumero_ref = 'B001-'.$boletas_nota['CDG_DOC_REF'];
                }elseif ($boletas_nota['CDG_COD_EMP']=='02'){
                    $serienumero_ref = 'B004-'.$boletas_nota['CDG_DOC_REF'];
                }

                $cbc_DocumentTypeCode = '07';
                $serieNumero = 'BN0'.$boletas_nota['CDG_SER_DOC'].'-'.$boletas_nota['CDG_NUM_DOC'];
                $cbc_ConditionCode = '1';
            }
            // total
            $sac_TotalAmount = number_format($boletas_nota['CDG_IMP_NETO'], 2, '.', '');

            /*  MONEDA
            *********************************************/
            if($boletas_nota['CDG_TIP_CAM'] != 0){
                $moneda = 'USD';
                $moneda_nombre = '$$';
                $moneda_leyenda = 'dolares';
            }else{
                $moneda = 'PEN';
                $moneda_nombre = 'S/';
                $moneda_leyenda = 'soles';
            }

            //relacionado o anticipo
            if ($boletas_nota['CDG_DOC_FRA'] != '0') {
                // gravadas
                $gravadas = number_format(round($boletas_nota['CDG_VVP_TOT']-($boletas_nota['CDG_TOT_FRA']/(1+$boletas_nota['CDG_POR_IGV']/100)) - $boletas_nota['CDG_DES_TOT'],2),2,'.','');
                //number_format($boletas_nota['CDG_VVP_TOT'] - $boletas_nota['CDG_DES_TOT'], 2, '.', '');
                //number_format($cab_doc_gen['CDG_VVP_TOT']-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100)) - $cab_doc_gen['CDG_DES_TOT'],2,'.','')

                //igv
                $igv = number_format(round(($boletas_nota['CDG_IGV_TOT'] -($boletas_nota['CDG_TOT_FRA']/(1+$boletas_nota['CDG_POR_IGV']/100))*($boletas_nota['CDG_POR_IGV']/100)),2),2,'.','');
                //number_format($boletas_nota['CDG_IGV_TOT'],2,'.','');
            }else{ // boleta o nota normal
                // gravadas
                $gravadas = number_format($boletas_nota['CDG_VVP_TOT'] - $boletas_nota['CDG_DES_TOT'], 2, '.', '');

                //igv
                $igv = number_format($boletas_nota['CDG_IGV_TOT'],2,'.','');
            }

            $SummaryDocumentsLine = $Summary->appendChild($xml->createElement('sac:SummaryDocumentsLine'));
                $LineID = $SummaryDocumentsLine->appendChild($xml->createElement('cbc:LineID',$i));
                $DocumentTypeCode = $SummaryDocumentsLine->appendChild($xml->createElement('cbc:DocumentTypeCode',$cbc_DocumentTypeCode));
                $ID = $SummaryDocumentsLine->appendChild($xml->createElement('cbc:ID',$serieNumero));
                $AccountingCustomerParty = $SummaryDocumentsLine->appendChild($xml->createElement('cac:AccountingCustomerParty'));
                    $CustomerAssignedAccountID = $AccountingCustomerParty->appendChild($xml->createElement('cbc:CustomerAssignedAccountID',trim($boletas_nota['CDG_DOC_CLI'])));
                    $AdditionalAccountID = $AccountingCustomerParty->appendChild($xml->createElement('cbc:AdditionalAccountID',$tipo_doc_num));

                if($cbc_DocumentTypeCode=='07'){ // Nota Credito
                    $BillingReference = $SummaryDocumentsLine->appendChild($xml->createElement('cac:BillingReference'));
                        $InvoiceDocumentReference = $BillingReference->appendChild($xml->createElement('cac:InvoiceDocumentReference'));
                            $ID = $InvoiceDocumentReference->appendChild($xml->createElement('cbc:ID',$serienumero_ref));
                            $DocumentTypeCode = $InvoiceDocumentReference->appendChild($xml->createElement('cbc:DocumentTypeCode','03'));
                }

                $Status = $SummaryDocumentsLine->appendChild($xml->createElement('cac:Status'));
                    $ConditionCode = $Status->appendChild($xml->createElement('cbc:ConditionCode',$cbc_ConditionCode));
                $TotalAmount = $SummaryDocumentsLine->appendChild($xml->createElement('sac:TotalAmount',$sac_TotalAmount)); $TotalAmount->setAttribute('currencyID', 'PEN');
                if($gravadas!='0.00'){
                    $BillingPayment = $SummaryDocumentsLine->appendChild($xml->createElement('sac:BillingPayment'));
                        $PaidAmount = $BillingPayment->appendChild($xml->createElement('cbc:PaidAmount',$gravadas)); $PaidAmount->setAttribute('currencyID', 'PEN');
                        $InstructionID = $BillingPayment->appendChild($xml->createElement('cbc:InstructionID','01'));
                }
                $TaxTotal = $SummaryDocumentsLine->appendChild($xml->createElement('cac:TaxTotal'));
                    $TaxAmount = $TaxTotal->appendChild($xml->createElement('cbc:TaxAmount',$igv)); $TaxAmount->setAttribute('currencyID', 'PEN');
                    $TaxSubtotal = $TaxTotal->appendChild($xml->createElement('cac:TaxSubtotal'));
                        $TaxAmount = $TaxSubtotal->appendChild($xml->createElement('cbc:TaxAmount',$igv)); $TaxAmount->setAttribute('currencyID', 'PEN');
                        $TaxCategory = $TaxSubtotal->appendChild($xml->createElement('cac:TaxCategory'));
                            $TaxScheme = $TaxCategory->appendChild($xml->createElement('cac:TaxScheme'));
                                $ID = $TaxScheme->appendChild($xml->createElement('cbc:ID','1000'));
                                $Name = $TaxScheme->appendChild($xml->createElement('cbc:Name','IGV'));
                                $TaxTypeCode = $TaxScheme->appendChild($xml->createElement('cbc:TaxTypeCode','VAT'));




            $i++;

        }//end foreach


        //header('Content-Type: text/xml; charset=UTF-8');
        //$strings_xml = $xml->saveXML();
        //echo $strings_xml;



        $xml->formatOutput = true;
        $nom = '20532710066-RC-'.date('Ymd').'-'.($j);
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

        //Crear una nueva clave de seguridad (privada)
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

        //Cargamos la clave privada
        $objKey->loadKey('../../archivos_pem/private_key.pem', true);
        $objDSig->sign($objKey);

        // Agregue la clave pública asociada a la firma
        $objDSig->add509Cert(file_get_contents('../../archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));

        // Anexar la firma al XML
        $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(0));
        $strings_xml = $doc->saveXML();





        ## Creación del archivo .ZIP
        $zip = new ZipArchive;
        $res = $zip->open($ruta.$nom.'.zip', ZipArchive::CREATE);
        $zip->addFromString($nom.'.xml', $strings_xml);
        $zip->close();



        $wsdlURL = "./billService2.wsdl";
        $XMLString = '<?xml version="1.0" encoding="UTF-8"?>
            <soapenv:Envelope
            xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
             xmlns:ser="http://service.sunat.gob.pe"
             xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
            <wsse:Security>
            <wsse:UsernameToken>
            <wsse:Username>20532710066SURMOTR1</wsse:Username>
            <wsse:Password>TOYOTA2051</wsse:Password>
            </wsse:UsernameToken>
            </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
            <ser:sendSummary>
            <fileName>'.'20532710066-RC-'.date('Ymd').'-'.($j).'.zip</fileName>
            <contentFile>' . base64_encode(file_get_contents($ruta.'20532710066-RC-'.date('Ymd').'-'.($j).'.zip')) . '</contentFile>
            </ser:sendSummary>
            </soapenv:Body>
            </soapenv:Envelope>';

        preg_match_all('/<ticket>(.*?)<\/ticket>/is', soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString), $ticket); $ticket= $ticket[1][0];
        sleep(10);
        //$resul = soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString);
        //echo $resul;


        $XMLString = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
            <wsse:Security>
            <wsse:UsernameToken>
            <wsse:Username>20532710066SURMOTR1</wsse:Username>
            <wsse:Password>TOYOTA2051</wsse:Password>
            </wsse:UsernameToken>
            </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
            <ser:getStatus>
            <ticket>'.$ticket.'</ticket>
            </ser:getStatus>
            </soapenv:Body>
            </soapenv:Envelope>';
        //echo $XMLString;
        $result = soapCall($wsdlURL, $callFunction = "getStatus", $XMLString);
        //header('Content-Type: text/xml; charset=UTF-8');
        //echo $result;

        preg_match_all('/<statusCode>(.*?)<\/statusCode>/is',$result, $codigo); $codigo = $codigo[1][0];
        if ($codigo == '0' || $codigo == '0098'){

            foreach ($boletas_notas as $index => $boletas_nota){
                if ($boletas_nota['CDG_ANU_SN']=='S' && $boletas_nota['CDG_DOC_ANU']=='S' && $boletas_nota['CDG_TIP_DOC'] == 'B'){
                    // agrega cab_doc_gen boletas
                    $update = "update cab_doc_gen SET CDG_SUN_ENV='S', CDG_COD_SNT='".$codigo."003', CDG_SUN_TIK='".$ticket."', CDG_SUN_CAN='1' WHERE to_char(CDG_FEC_GEN,'dd-mm-yyyy')='".$fecha."' and CDG_NUM_DOC='".$boletas_nota['CDG_NUM_DOC']."' ";
                    $stmt = oci_parse($conn, $update);
                    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($stmt);
                }else{
                    // agrega cab_doc_gen boletas
                    $update = "update cab_doc_gen SET CDG_SUN_ENV='S', CDG_COD_SNT='".$codigo."001', CDG_SUN_TIK='".$ticket."', CDG_SUN_CAN='1' WHERE to_char(CDG_FEC_GEN,'dd-mm-yyyy')='".$fecha."' and CDG_NUM_DOC='".$boletas_nota['CDG_NUM_DOC']."' ";
                    $stmt = oci_parse($conn, $update);
                    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($stmt);
                }

            }
        }


        //header('Content-Type: text/xml; charset=UTF-8');
        //echo $result;

        //genera el cdr de la comprobacion del ticket y lo guarda para analizar errores
        preg_match_all('/<content>(.*?)<\/content>/is',$result,$matches);
        $cdr=base64_decode($matches[1][0]);
        $archivo = fopen($ruta.'CDR'.$j.'.zip','w+');
        fputs($archivo,$cdr);
        fclose($archivo);


    }

    //si hay codigo 98
    if ($nopasa=='2'){
        foreach ($boletas_notas as $index => $boletas_nota){
            if ($boletas_nota['CDG_COD_SNT']=='0098'){
                $ticket = $boletas_nota['CDG_SUN_TIK'];
                echo $boletas_nota['CDG_COD_SNT'];
            }
        }
        $wsdlURL = "./billService2.wsdl";
        $XMLString = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
            <wsse:Security>
            <wsse:UsernameToken>
            <wsse:Username>20532710066SURMOTR1</wsse:Username>
            <wsse:Password>TOYOTA2051</wsse:Password>
            </wsse:UsernameToken>
            </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
            <ser:getStatus>
            <ticket>'.$ticket.'</ticket>
            </ser:getStatus>
            </soapenv:Body>
            </soapenv:Envelope>';
        //echo $XMLString;
        $result = soapCall($wsdlURL, $callFunction = "getStatus", $XMLString);
        preg_match_all('/<statusCode>(.*?)<\/statusCode>/is',$result, $codigo); $codigo = $codigo[1][0];
        if ($codigo == '0') {
            foreach ($boletas_notas as $index => $boletas_nota){
                if ($boletas_nota['CDG_COD_SNT']=='0098'){

                    // agrega cab_doc_gen boletas
                    $update = "update cab_doc_gen SET CDG_SUN_ENV='S', CDG_COD_SNT='".$codigo."001', CDG_SUN_CAN='2' WHERE to_char(CDG_FEC_GEN,'dd-mm-yyyy')='".$fecha."' and CDG_NUM_DOC='".$boletas_nota['CDG_NUM_DOC']."' ";
                    $stmt = oci_parse($conn, $update);
                    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($stmt);
                }
            }
        }
        //echo $codigo;
        //header('Content-Type: text/xml; charset=UTF-8');
        //echo $result;

    }

?>