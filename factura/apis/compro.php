<?php
    include "../__soap.php";

    $wsdlURL = 'https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
    $XMLString = '<soapenv:Envelope xmlns:ser="http://service.sunat.gob.pe" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
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
        <rucComprobante>20532710066</rucComprobante>
        <tipoComprobante>01</tipoComprobante>
        <serieComprobante>F001</serieComprobante>
        <numeroComprobante>17985</numeroComprobante>
        </ser:getStatus>
        </soapenv:Body>
        </soapenv:Envelope>';
    $result = soapCall($wsdlURL, $callFunction = "getStatus", $XMLString);
    print_r($result);
?>