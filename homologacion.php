<?php
//$soap = new SoapClient('https://www.sunat.gob.pe:443/ol-ti-itcpgem-sqa/billService?wsdl');
//$soap = new SoapClient('https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl');
//var_dump($soap->__getFunctions()); 35 73 84
$nameXml = '20532710066-08-BB50-1';

// 2.- Firmar documento xml
// ========================
require './robrichards/src/xmlseclibs.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
// Cargar el XML a firmar
$doc = new DOMDocument();
$doc->load('homo/'.$nameXml.'.xml');
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
// Guardar el XML firmado
$doc->save('homo/'.$nameXml.'.xml');
chmod('homo/'.$nameXml.'.xml', 0777);
// 3.- Enviar documento xml y obtener respuesta
// ============================================
require('./lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP
## Creación del archivo .ZIP
$zip = new PclZip('homo/'.$nameXml.'.zip');
$zip->create('homo/'.$nameXml.'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
chmod('homo/'.$nameXml.'.zip', 0777);

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
        try{
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
    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array(), array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
    //print_r($client);
}
//URL para enviar las solicitudes a SUNAT
$wsdlURL = 'https://www.sunat.gob.pe/ol-ti-itcpgem-sqa/billService?wsdl';
//Estructura del XML para la conexión

$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope 
xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:ser="http://service.sunat.gob.pe" 
xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
<soapenv:Header>
<wsse:Security>
<wsse:UsernameToken>
<wsse:Username>20532710066ROMANTRE</wsse:Username>
<wsse:Password>T3E4A5M6S7</wsse:Password>
</wsse:UsernameToken>
</wsse:Security>
</soapenv:Header>
<soapenv:Body>
<ser:sendSummary>
<fileName>'.$nameXml.'.zip</fileName>
<contentFile>' . base64_encode(file_get_contents('./homo/'.$nameXml.'.zip')) . '</contentFile>
</ser:sendSummary>
</soapenv:Body>
</soapenv:Envelope>';

//echo $XMLString;
//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);
echo $result;
/*
//Descargamos el Archivo Response
$archivo = fopen('homo/'.'C'.$nameXml.'.xml','w+');
fputs($archivo,$result);
fclose($archivo);
//LEEMOS EL ARCHIVO XML
$xml = simplexml_load_file('homo/'.'C'.$nameXml.'.xml');
foreach ($xml->xpath('//applicationResponse') as $response){ }
//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
$cdr=base64_decode($response);
$archivo = fopen('homo/'.'R-'.$nameXml.'.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod('homo/'.'R-'.$nameXml.'.zip', 0777);
$archive = new PclZip('homo/'.'R-'.$nameXml.'.zip');
/*
if ($archive->extract('homo/')==0) {
    die("Error : ".$archive->errorInfo(true));
}else{
    chmod('homo/'.$nameXml.'.xml', 0777);
}

//Eliminamos el Archivo Response
unlink('homo/'.'C'.$nameXml.'.xml');
*/
?>