<?php
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

    $wsdlURL = 'https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
    $XMLString = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
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
        <numeroComprobante>17287</numeroComprobante>
        </m:getStatusCdr>
        </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';
    $result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
    echo $result;
    preg_match_all('/<content>(.*?)<\/content>/is',$result,$matches);
    //echo $matches[1][0];

    $cdr=base64_decode($matches[1][0]);
    $archivo = fopen('./17076.zip','w+');
    fputs($archivo,$cdr);
    fclose($archivo);
    chmod('./17076.zip', 0777);
?>