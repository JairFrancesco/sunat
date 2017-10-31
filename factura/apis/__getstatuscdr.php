<?php
    include "../__soap.php";


    $wsdlURL = 'https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';

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
    <ticket>201701477452608</ticket>
    </ser:getStatus>
    </soapenv:Body>
    </soapenv:Envelope>';
    $result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
    //preg_match_all('/<statusCode>(.*?)<\/statusCode>/is',soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString) , $codigo); //$codigo = $codigo[1][0];

    print_r($result);
    //echo "hello";
?>