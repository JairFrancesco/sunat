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

    $nameXml = '20532710066-RA-20170716-1';
    $wsdlURL = '../../billService.wsdl';
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
    <fileName>'.$nameXml.'.zip</fileName>
    <contentFile>'.base64_encode(file_get_contents($nameXml.'.zip')).'</contentFile>
    </ser:sendSummary>
    </soapenv:Body>
    </soapenv:Envelope>';

    $result = soapCall($wsdlURL, $callFunction = "sendSummary", $XMLString);
    echo $result;

?>