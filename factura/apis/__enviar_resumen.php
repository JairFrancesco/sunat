<?php
  /*Firmado Electronico*/
  use RobRichards\XMLSecLibs\XMLSecurityDSig;
  use RobRichards\XMLSecLibs\XMLSecurityKey;

  /*Conexion BD
  ************************/
  include "../conexion.php";

  include "../__resumen.php";

  if (count($boletas) != 0){ // si existen boletas o notas entra

      /*Consulta a la BD
      **********************************/
      $sql_resumen = "select * from resumenes where to_char(fecha,'dd-mm-yyyy')='".$fecha."'";
      $sql_parse = oci_parse($conn,$sql_resumen);
      oci_execute($sql_parse);
      oci_fetch_all($sql_parse, $resumenes, null, null, OCI_FETCHSTATEMENT_BY_ROW);



      //$chek:  0: no hay resumen, 1: resumen aceptado y comprobado, 2 : resumen hay pero esta en 0098
      if(isset($resumenes[0]['CODIGO'])){
          if($resumenes[0]['CODIGO'] == '0'){
              $check = 1;
          }elseif($resumenes[0]['CODIGO'] == '0098'){
              $check = 2;
          }
      }else{
          $check = 0;
      }



      if ($check == 0 || $check == 2) {

          /*ruta
          ***********************/
          $ruta = '../../app/resumenes/'.date('Y').'/'.date('m').'/'.date('d').'/';
          if (!file_exists($ruta)) {
              mkdir($ruta, 0777, true);
          }
          $j=1;
          while(file_exists($ruta.'20532710066-RC-'.date('Ymd').'-'.$j.'.zip')){
              $j++;
              // el valor de i es el actual que se va crear
          }

          // creacion del xml
          $xml = new DomDocument('1.0', 'ISO-8859-1');
          $xml->standalone         = false;
          $xml->preserveWhiteSpace = false;
          $Invoice = $xml->createElement('SummaryDocuments'); $Invoice = $xml->appendChild($Invoice);
          $Invoice->setAttribute('xmlns',"urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1");
          $Invoice->setAttribute('xmlns:cac',"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
          $Invoice->setAttribute('xmlns:cbc',"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
          $Invoice->setAttribute('xmlns:ds',"http://www.w3.org/2000/09/xmldsig#");
          $Invoice->setAttribute('xmlns:ext',"urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
          $Invoice->setAttribute('xmlns:sac',"urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
          $Invoice->setAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");
          //$Invoice->setAttribute('xsi:schemaLocation','urn:sunat:names:specification:ubl:peru:schema:xsd:InvoiceSummary-1 D:\UBL_SUNAT\SUNAT_xml_20110112\20110112\xsd\maindoc\UBLPE-InvoiceSummary-1.0.xsd');
          $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $Invoice->appendChild($UBLExtension);
          $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
          $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);

          $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $Invoice->appendChild($cbc);
          $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $Invoice->appendChild($cbc);
          $cbc = $xml->createElement('cbc:ID', 'RC-'.date('Ymd').'-'.$j); $cbc = $Invoice->appendChild($cbc);
          $cbc = $xml->createElement('cbc:ReferenceDate', date("Y-m-d", strtotime($fecha))); $cbc = $Invoice->appendChild($cbc);
          $cbc = $xml->createElement('cbc:IssueDate', date('Y-m-d')); $cbc = $Invoice->appendChild($cbc);

          // signature
          $cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $Invoice->appendChild($cac_signature);
          $cbc = $xml->createElement('cbc:ID', 'IDSignKG'); $cbc = $cac_signature->appendChild($cbc);
          $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
          $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
          $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
          $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
          $cbc = $xml->createElement('cbc:Name', 'DESARROLLO DE SISTEMAS INTEGRADOS DE GESTION'); $cbc = $cac->appendChild($cbc);
          $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
          $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
          $cbc = $xml->createElement('cbc:URI', '#signatureKG'); $cbc = $cac->appendChild($cbc);


          // Datos Surmotriz
          $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
          $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
          $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
          $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
          $cac = $xml->createElement('cac:PartyName'); $cac = $cac_party->appendChild($cac);
          $cbc = $xml->createElement('cbc:Name', 'TOYOTA SURMOTRIZ'); $cbc = $cac->appendChild($cbc);
          $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
          $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);


          foreach ($boletas as $index => $bol){

              $SummaryDocumentsLine = $xml->createElement('sac:SummaryDocumentsLine'); $SummaryDocumentsLine = $Invoice->appendChild($SummaryDocumentsLine);
              $cbc = $xml->createElement('cbc:LineID',$index+1); $cbc = $SummaryDocumentsLine->appendChild($cbc);
              $cbc = $xml->createElement('cbc:DocumentTypeCode',$bol['serie_tipo']); $cbc = $SummaryDocumentsLine->appendChild($cbc);
              $sac = $xml->createElement('sac:DocumentSerialID',$bol['serie']); $sac = $SummaryDocumentsLine->appendChild($sac);
              $sac = $xml->createElement('sac:StartDocumentNumberID',$bol['first']); $sac = $SummaryDocumentsLine->appendChild($sac);
              $sac = $xml->createElement('sac:EndDocumentNumberID',$bol['last']); $sac = $SummaryDocumentsLine->appendChild($sac);
              $sac = $xml->createElement('sac:TotalAmount', number_format($bol['total'], 2, '.', '')); $sac = $SummaryDocumentsLine->appendChild($sac); $sac->setAttribute('currencyID',"PEN");

              // Gravado
              $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
              $cbc = $xml->createElement('cbc:PaidAmount', number_format($bol['gravadas'], 2, '.', '')); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cbc = $xml->createElement('cbc:InstructionID','01'); $cbc = $sac->appendChild($cbc);
              // Exonerado
              $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
              $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cbc = $xml->createElement('cbc:InstructionID','02'); $cbc = $sac->appendChild($cbc);
              // Inafecto
              $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
              $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cbc = $xml->createElement('cbc:InstructionID','03'); $cbc = $sac->appendChild($cbc);
              // otros cargos
              $cac = $xml->createElement('cac:AllowanceCharge'); $cac = $SummaryDocumentsLine->appendChild($cac);
              $cbc = $xml->createElement('cbc:ChargeIndicator','true'); $cbc = $cac->appendChild($cbc);
              $cbc = $xml->createElement('cbc:Amount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              // Total ISC
              $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
              $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
              $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
              $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
              $cbc = $xml->createElement('cbc:ID','2000'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:Name','ISC'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:TaxTypeCode','EXC'); $cbc = $TaxScheme->appendChild($cbc);
              // Total IGV
              $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
              $cbc = $xml->createElement('cbc:TaxAmount', number_format($bol['igv'], 2, '.', '')); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
              $cbc = $xml->createElement('cbc:TaxAmount', number_format($bol['igv'], 2, '.', '')); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
              $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
              $cbc = $xml->createElement('cbc:ID','1000'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:Name','IGV'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:TaxTypeCode','VAT'); $cbc = $TaxScheme->appendChild($cbc);
              // Total Otros tributos
              $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
              $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
              $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
              $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
              $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
              $cbc = $xml->createElement('cbc:ID','9999'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:Name','OTROS'); $cbc = $TaxScheme->appendChild($cbc);
              $cbc = $xml->createElement('cbc:TaxTypeCode','OTH'); $cbc = $TaxScheme->appendChild($cbc);

          }

          $xml->formatOutput = true;
          //$strings_xml = $xml->saveXML();
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


          $wsdlURL = "../billService.wsdl";
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
          //$resul = soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString);
          //echo $resul;


          preg_match_all('/<ticket>(.*?)<\/ticket>/is', soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString), $ticket); $ticket= $ticket[1][0];
          //esperar 3 minutos
          sleep(180);

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

          preg_match_all('/<statusCode>(.*?)<\/statusCode>/is',soapCall($wsdlURL, $callFunction = "getStatus", $XMLString) , $codigo); $codigo = $codigo[1][0];
          //echo $codigo;


          // guarda las boletas y sus notas en cada uno de sus items
          if($codigo == '0'){

              foreach ( $boletas as $bol ){


                  //para saber si es boleta o nota
                  if ($bol['serie']=='BN03' || $bol['serie']=='BN04'){
                      $tip_doc='A';

                  }else{
                      $tip_doc='B';
                  }
                  //Actualiza cab_doc_gen por grupo boletas o notas
                  $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='0001' WHERE cdg_num_doc >= '".$bol['first']."' and cdg_num_doc <= '".$bol['last']."' and cdg_ser_doc='".$bol['serie'][3]."' and cdg_tip_doc='".$tip_doc."' ";
                  $stmt = oci_parse($conn, $update);
                  oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                  oci_free_statement($stmt);



                  //RESUMENES ACTUALIZA
                  // consulta los anteriores para los ticket
                  $sql_anterior = "select * from resumenes where serie='".$bol['serie']."' and  inicio='".$bol['first']."' ";
                  $sql_parse = oci_parse($conn,$sql_anterior);
                  oci_execute($sql_parse);
                  oci_fetch_all($sql_parse, $anteriores, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                  foreach ($anteriores as $anterior){
                      // eliminar los anteriores
                      $sql_delete = "DELETE FROM resumenes WHERE ticket= '".$anterior['TICKET']."' ";
                      $stmt_delete = oci_parse($conn, $sql_delete);
                      oci_execute($stmt_delete);
                  }

                  $sql_insert = "insert into resumenes (FECHA,TICKET,SERIE,INICIO,FINAL,SUBTOTAL,DESCUENTO,GRAVADA,IGV,TOTAL,CODIGO,EMP) values (to_date('".$fecha."','dd-mm-yyyy'),'".$ticket."','".$bol['serie']."','".$bol['first']."','".$bol['last']."','".$bol['sub']."','".$bol['descuentos']."','".$bol['gravadas']."','".$bol['igv']."','".$bol['total']."','".$codigo."','".$bol['emp']."')";
                  $stmt_insert = oci_parse($conn, $sql_insert);
                  oci_execute($stmt_insert);

              }


          }

      }elseif ($check == 1) {

      }


  }
  //echo $check;

  /*resumen algoritmo
  ***********************/
  //include "../__resumen.php";
 ?>
