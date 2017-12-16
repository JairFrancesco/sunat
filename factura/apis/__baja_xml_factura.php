<?php

    /*Firmado Electronico*/
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    /*conexion bd
    *****************/
    include "../conexion.php";

    /* CONSULTA CAB_DOC_GEN
    *****************************************************************************/
    $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_cla_doc='".$cla."' and cdg_num_doc='".$num."'";
    $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];
    //print_r($cab_doc_gen);

    /*ruta*/
    $ruta = '../../app/bajas/'.date('Y').'/'.date('m').'/'.date('d').'/';
    if (!file_exists($ruta)) { mkdir($ruta, 0777, true); }
    $i=1;
    while(file_exists($ruta.'20532710066-RA-'.date('Ymd').'-'.$i.'.zip')){
        $i++; // el valor de i es el actual que se va crear
    }

    /* DOC Y SERIE 01-F001
    *********************/
    if ($cab_doc_gen['CDG_TIP_DOC'] == 'F') {
        $doc = '01';
        $serie = 'F00' . $cab_doc_gen['CDG_SER_DOC'];
    } elseif ($cab_doc_gen['CDG_TIP_DOC'] == 'B') {
        $doc = '03';
        $serie = 'B00' . $ser;
    } elseif ($cab_doc_gen['CDG_TIP_DOC'] == 'A') {
        $doc = '07';
        $serie = 'FN0' . $ser;

    }

    // Crear documento XML
    $xml = new DomDocument('1.0', 'ISO-8859-1'); $xml->standalone = false; $xml->preserveWhiteSpace = false;
    $Invoice = $xml->createElement('VoidedDocuments'); $Invoice = $xml->appendChild($Invoice);
    $Invoice->setAttribute('xmlns',"urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1");
    $Invoice->setAttribute('xmlns:cac',"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
    $Invoice->setAttribute('xmlns:cbc',"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
    $Invoice->setAttribute('xmlns:ds',"http://www.w3.org/2000/09/xmldsig#");
    $Invoice->setAttribute('xmlns:ext',"urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
    $Invoice->setAttribute('xmlns:sac',"urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
    $Invoice->setAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");
    $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $Invoice->appendChild($UBLExtension);
    $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
    $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);


    $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $Invoice->appendChild($cbc);
    $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $Invoice->appendChild($cbc);
    $cbc = $xml->createElement('cbc:ID', 'RA-'.date('Ymd').'-'.$i); $cbc = $Invoice->appendChild($cbc);
    $cbc = $xml->createElement('cbc:ReferenceDate', $fecha = date("Y-m-d", strtotime($cab_doc_gen['CDG_FEC_GEN']))); $cbc = $Invoice->appendChild($cbc);
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


    // Datos del emisor de la factura (surmotriz)
    $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
    $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
    $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
    $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
    $cac = $xml->createElement('cac:PartyName'); $cac = $cac_party->appendChild($cac);
    $cbc = $xml->createElement('cbc:Name', 'TOYOTA SURMOTRIZ'); $cbc = $cac->appendChild($cbc);
    $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
    $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);


    $VoidedDocumentsLine = $xml->createElement('sac:VoidedDocumentsLine'); $VoidedDocumentsLine = $Invoice->appendChild($VoidedDocumentsLine);
    $cbc = $xml->createElement('cbc:LineID','1'); $cbc = $VoidedDocumentsLine->appendChild($cbc);
    $cbc = $xml->createElement('cbc:DocumentTypeCode',$doc); $cbc = $VoidedDocumentsLine->appendChild($cbc);
    $sac = $xml->createElement('sac:DocumentSerialID',$serie); $sac = $VoidedDocumentsLine->appendChild($sac);
    $sac = $xml->createElement('sac:DocumentNumberID',$cab_doc_gen['CDG_NUM_DOC']); $sac = $VoidedDocumentsLine->appendChild($sac);
    $sac = $xml->createElement('sac:VoidReasonDescription','Error Sistema'); $sac = $VoidedDocumentsLine->appendChild($sac);


    $xml->formatOutput = true;
    //$strings_xml = $xml->saveXML();
    //$xml->save($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml');

    $nom = '20532710066-RA-'.date('Ymd').'-'.($i);
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



    //20532710066SURMOTR1      TOYOTA2051
    $wsdlURL = '../billService.wsdl';
    //$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
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
            <fileName>'.$nom.'.zip</fileName>
            <contentFile>'.base64_encode(file_get_contents($ruta.$nom.'.zip')).'</contentFile>
         </ser:sendSummary>
     </soapenv:Body>
    </soapenv:Envelope>';


    //echo $XMLString;
    //Realizamos la llamada a nuestra función
    $result = soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString);
    //print_r($result);


?>