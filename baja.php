<?php
    // conexion
    require("app/coneccion.php");
    date_default_timezone_set('America/Lima');
    // recoge datos de la url
    $gem = $_GET['gen'];
    $emp = $_GET['emp'];
    $num_doc = $_GET['num_doc'];
    $cla_doc = $_GET['cla_doc'];
    $moneda = $_GET['moneda'];
    $co_cr_an = $_GET['co_cr_an'];
    $exi_fra = $_GET['exi_fra'];
    $tip_imp = $_GET['tip_imp'];
    $anu_sn = $_GET['anu_sn'];
    $doc_anu = $_GET['doc_anu'];

    // Tipos de factura
    if ( $cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BS' || $cla_doc=='BR') {

        // obtener cabezera
        // =================
        $sql_cab = "begin PKG_ELECTRONICA.fbc('".$gem."','".$emp."',".$num_doc.",'".$cla_doc."',:doc); end;";
        $stid = oci_parse($conn,$sql_cab);
        $curs_cab = oci_new_cursor($conn);
        oci_bind_by_name($stid, ":doc", $curs_cab, -1, OCI_B_CURSOR);
        oci_execute($stid);
        oci_execute($curs_cab);
        while (($row_cab = oci_fetch_array($curs_cab, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            // cab es todas las variables de cabezera
            $cab = $row_cab;
        }

        /*
        if ($co_cr_an=='CO' || $co_cr_an=='CR') {
            if ($tip_imp == 'D') {

            }elseif($tip_imp=='R'){

            }
        }elseif($co_cr_an=='AN'){

        }
        */

        //$ruta_fecha = explode('-',$cab['FECEMISION1']);
        //$ruta = './app/repo/'.$ruta_fecha[0].'/'.$ruta_fecha[1].'/'.$ruta_fecha[2].'/';


        $ruta = './app/bajas/'.date('Y').'/'.date('m').'/'.date('d').'/';

        if (!file_exists($ruta)) { mkdir($ruta, 0777, true); }
        $i=1;
        while(file_exists($ruta.'20532710066-RA-'.date('Ymd').'-'.$i.'.xml')){
            $i++;
            // el valor de i es el actual que se va crear
        }

        // 1.- crear documento XML
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
        $cbc = $xml->createElement('cbc:ReferenceDate', $cab['FECEMISION1']); $cbc = $Invoice->appendChild($cbc);
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
                $cbc = $xml->createElement('cbc:DocumentTypeCode',$cab['TIPODOC']); $cbc = $VoidedDocumentsLine->appendChild($cbc);
                $sac = $xml->createElement('sac:DocumentSerialID',$cab['SERIE']); $sac = $VoidedDocumentsLine->appendChild($sac);
                $sac = $xml->createElement('sac:DocumentNumberID',$cab['DOCUMENTO']); $sac = $VoidedDocumentsLine->appendChild($sac);
                $sac = $xml->createElement('sac:VoidReasonDescription','Error Sistema'); $sac = $VoidedDocumentsLine->appendChild($sac);

        /*else {
            $import = file_get_contents($ruta.'20532710066-RA-'.$ruta_fecha[0].$ruta_fecha[1].$ruta_fecha[2].'-'.($i-1).'.xml');
            $doc = new DOMDocument('1.0', 'ISO-8859-1');
            $doc->preserveWhiteSpace = FALSE;
            $doc->loadXML($import);
            //$lineas = $doc->getElementsByTagName('InvoiceLine')->item(1)->getElementsByTagName('ID')->item(0)->nodeValue;
            for ($h=0;$h<$doc->getElementsByTagName('LineID')->length;$h++){
                $VoidedDocumentsLine = $xml->createElement('sac:VoidedDocumentsLine'); $VoidedDocumentsLine = $Invoice->appendChild($VoidedDocumentsLine);
                    $cbc = $xml->createElement('cbc:LineID',$doc->getElementsByTagName('LineID')->item($h)->nodeValue); $cbc = $VoidedDocumentsLine->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:DocumentTypeCod',$doc->getElementsByTagName('DocumentTypeCod')->item($h)->nodeValue); $cbc = $VoidedDocumentsLine->appendChild($cbc);
                    $sac = $xml->createElement('sac:DocumentSerialID',$doc->getElementsByTagName('DocumentSerialID')->item($h)->nodeValue); $sac = $VoidedDocumentsLine->appendChild($sac);
                    $sac = $xml->createElement('sac:DocumentNumberID',$doc->getElementsByTagName('DocumentNumberID')->item($h)->nodeValue); $sac = $VoidedDocumentsLine->appendChild($sac);
                    $sac = $xml->createElement('sac:VoidReasonDescription',$doc->getElementsByTagName('VoidReasonDescription')->item($h)->nodeValue); $sac = $VoidedDocumentsLine->appendChild($sac);
            }
            $VoidedDocumentsLine = $xml->createElement('sac:VoidedDocumentsLine'); $VoidedDocumentsLine = $Invoice->appendChild($VoidedDocumentsLine);
                $cbc = $xml->createElement('cbc:LineID',$i); $cbc = $VoidedDocumentsLine->appendChild($cbc);
                $cbc = $xml->createElement('cbc:DocumentTypeCod',$cab['TIPODOC']); $cbc = $VoidedDocumentsLine->appendChild($cbc);
                $sac = $xml->createElement('sac:DocumentSerialID',$cab['SERIE']); $sac = $VoidedDocumentsLine->appendChild($sac);
                $sac = $xml->createElement('sac:DocumentNumberID',$cab['DOCUMENTO']); $sac = $VoidedDocumentsLine->appendChild($sac);
                $sac = $xml->createElement('sac:VoidReasonDescription','Error Sistema'); $sac = $VoidedDocumentsLine->appendChild($sac);
        }
        */

        $xml->formatOutput = true;
        $strings_xml = $xml->saveXML();
        $xml->save($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml');

    }


    // 2.- Firmar documento xml
    // ========================

    require './robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    // Cargar el XML a firmar
    $doc = new DOMDocument();
    $doc->load($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml');

    // Crear un nuevo objeto de seguridad
    $objDSig = new XMLSecurityDSig();

    // Utilizar la canonización exclusiva de c14n
    $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

    // Firmar con SHA-256
    $objDSig->addReference(
        $doc,
        XMLSecurityDSig::SHA1,
        array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
        array('force_uri' => true)
    );

    //Crear una nueva clave de seguridad (privada)
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

    //Cargamos la clave privada
    $objKey->loadKey('./archivos_pem/private_key.pem', true);
    $objDSig->sign($objKey);

    // Agregue la clave pública asociada a la firma
    $objDSig->add509Cert(file_get_contents('./archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));

    // Anexar la firma al XML
    $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(0));

    $doc->save($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml');
    chmod($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml', 0777);



// 3.- Enviar documento xml y obtener respuesta
// ============================================

require('./lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

## Creación del archivo .ZIP
$zip = new PclZip($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.zip');
$zip->create($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
chmod($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.zip', 0777);



# Procedimiento para enviar comprobante a la SUNAT
class feedSoap extends SoapClient{

    public $XMLStr = "";

    public function setXMLStr($value)
    {
        $this->XMLStr = $value;
    }

    public function getXMLStr()
    {
        return $this->XMLStr;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
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

    public function SoapClientCall($SOAPXML)
    {
        return $this->setXMLStr($SOAPXML);
    }
}


function soapCall($wsdlURL, $callFunction = "", $XMLString)
{
    $client = new feedSoap($wsdlURL, array('trace' => true));
    $reply  = $client->SoapClientCall($XMLString);

    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array(), array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
}

//URL para enviar las solicitudes a SUNAT
$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

//Estructura del XML para la conexión
$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
 <soapenv:Header>
     <wsse:Security>
         <wsse:UsernameToken Id="ABC-123">
             <wsse:Username>20532710066MODDATOS</wsse:Username>
             <wsse:Password>MODDATOS</wsse:Password>
         </wsse:UsernameToken>
     </wsse:Security>
 </soapenv:Header>
 <soapenv:Body>
     <ser:sendSummary>
        <fileName>'.'20532710066-RA-'.date('Ymd').'-'.($i).'.zip</fileName>
        <contentFile>' . base64_encode(file_get_contents($ruta.'20532710066-RA-'.date('Ymd').'-'.($i).'.zip')) . '</contentFile>
     </ser:sendSummary>
 </soapenv:Body>
</soapenv:Envelope>
';

//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString);
echo $result;
?>