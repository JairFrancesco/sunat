<?php
header('Content-Type: text/html; charset=UTF-8');
require('lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16pt; color: #000000; margin-bottom: 10px;">';
echo 'SUNAT. Facturación electrónica Perú.<br>';
echo '<span style="color: #6A0888; font-size: 15pt;">';
echo 'Envío de factura electrónica al servidor de SUNAT<br>';
echo 'y obtención de la Constancia de Recepción (CDR).';
echo '</span>';
echo '<hr width="100%"></div>';


// NOMBRE DE ARCHIVO A PROCESAR.
$NomArch='20532710066-01-F002-00000026';


## =============================================================================
## Creación del archivo .ZIP
echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-bottom: 10px;">';
echo 'Archivo .XML (factura electrónica) a comprimir.<br>';
echo '<span style="color: #000000;">'.$NomArch.'.xml</span>';
echo '</div>';

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-bottom: 10px;">';
echo 'Archivo .ZIP (conteniendo el doc. electrónico) a enviar al servidor de SUNAT.<br>';
echo '<span style="color: #000000;">'.$NomArch.'.zip</span>';
echo '</div>';

$zip = new PclZip($NomArch.".zip");
$zip->create($NomArch.".xml");
chmod($NomArch.".zip", 0777);



# ==============================================================================
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

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-bottom: 10px;">';
echo 'URL Beta de SUNAT:<br>';
echo '<span style="color: #000000;">'.$wsdlURL.'</span>';
echo '</div>';

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
     <ser:sendBill>
        <fileName>'.$NomArch.'.zip</fileName>
        <contentFile>' . base64_encode(file_get_contents($NomArch.'.zip')) . '</contentFile>
     </ser:sendBill>
 </soapenv:Body>
</soapenv:Envelope>';

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099;">';
echo 'SOAP de envío al servidor de SUNAT con el método sendBill.';
echo '</div>';
echo $XMLString;


//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-top: 10px;">';
echo 'Respuesta del servidor de SUNAT:<br>';
echo '<span style="color: #000000;">'.$result.'</span>';
echo '</div>';

//Descargamos el Archivo Response
$archivo = fopen('C'.$NomArch.'.xml','w+');
fputs($archivo,$result);
fclose($archivo);


/*LEEMOS EL ARCHIVO XML*/
$xml = simplexml_load_file('C'.$NomArch.'.xml'); 
foreach ($xml->xpath('//applicationResponse') as $response){ }

/*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
$cdr=base64_decode($response);
$archivo = fopen('R-'.$NomArch.'.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod('R-'.$NomArch.'.zip', 0777);

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-top: 10px;">';
echo 'Archivo .ZIP recibido.<br>';
echo '<span style="color: #000000;">R-'.$NomArch.'.zip</span>';
echo '</div>';

$archive = new PclZip('R-'.$NomArch.'.zip');
if ($archive->extract()==0) { 
    die("Error : ".$archive->errorInfo(true)); 
}else{
    chmod('R-'.$NomArch.'.xml', 0777);    
} 

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000099; margin-top: 10px;">';
echo 'Archivo .XML constancia de recepción (CRD) ya descomprimido.<br>';
echo '<span style="color: #A70202;">R-'.$NomArch.'.xml</span>';
echo '</div>';


/*Eliminamos el Archivo Response*/
unlink('C'.$NomArch.'.xml');


