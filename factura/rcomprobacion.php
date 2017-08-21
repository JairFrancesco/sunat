<?php
    include ('__soap.php');
    $wsdlURL = "billService.wsdl";
    $ticket = $_GET['ticket'];
    $XMLString2 = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
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
    preg_match_all('/<statusCode>(.*?)<\/statusCode>/is',soapCall($wsdlURL, $callFunction = "getStatus", $XMLString2) , $codigo); $codigo = $codigo[1][0];
    echo '<div style="text-align: center;">';
    if($codigo == '0'){
        echo '<img src="./images/ok.png"><br>';
        echo 'El Resumen existe y fue procesado correctamente Nro '.$ticket;
    }else{
        echo '<img src="./images/error.png"><br>';
        echo 'El Resumen no existe Nro '.$ticket;
    }
    echo '</div>';
?>