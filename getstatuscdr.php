<?php

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
$wsdlURL = 'https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';

$XMLString = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
<SOAP-ENV:Header xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope">
<wsse:Security>
<wsse:UsernameToken>
<wsse:Username>20532710066SURMOTR1</wsse:Username>
<wsse:Password>TOYOTA2051</wsse:Password>
</wsse:UsernameToken>
</wsse:Security>
</SOAP-ENV:Header>
<SOAP-ENV:Body>
<m:getStatusCdr xmlns:m="http://service.sunat.gob.pe">
<rucComprobante>20532710066</rucComprobante>
<tipoComprobante>01</tipoComprobante>
<serieComprobante>F001</serieComprobante>
<numeroComprobante>17076</numeroComprobante>
</m:getStatusCdr>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';


//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
//echo $result;
//$porciones = explode("content>", $result);
preg_match_all('/<content>(.*?)<\/content>/is',$result,$matches);
//$claves = preg_split('/content(.*?)content/', $result);
//print_r($matches[1][0]);

//$nameXml = '17076';

//Descargamos el Archivo Response
//$archivo = fopen('cdr/'.'C'.$nameXml.'.xml','w+'); fputs($archivo,$result); fclose($archivo);

//LEEMOS EL ARCHIVO XML
//$xml = simplexml_load_file('homo/'.'C'.$nameXml.'.xml');
//$xml = DOMDocument::loadXML($result);
//print_r($xml);
//foreach ($xml->xpath('//content') as $response){ }
//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)

$cdr=base64_decode($matches[1][0]);

$archivo = fopen('app/cdr/17076.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod('app/cdr/17076.zip', 0777);
require('./lib/pclzip.lib.php');
$archive = new PclZip('app/cdr/17076.zip');

/*
if ($archive->extract('homo/')==0) {
    die("Error : ".$archive->errorInfo(true));
}else{
    chmod('homo/'.$nameXml.'.xml', 0777);
}
*/
//Eliminamos el Archivo Response
//unlink('homo/'.'C'.$nameXml.'.xml');

?>