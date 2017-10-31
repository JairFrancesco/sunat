<?php
    if (($cod != '0001' && $cod != '0003') || ($anu_sn=='S' && $doc_anu=='S' && $cod != '0003')){

        /* DOC Y SERIE 01-F001
        *******************/
        if ($tip == 'F') {
            $doc = '01';
            $serie = 'F00' . $ser;
        } elseif ($tip == 'B') {
            $doc = '03';
            $serie = 'B00' . $ser;
        } elseif ($tip == 'A') {
            $doc = '07';
            $serie = 'FN0' . $ser;

        }

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
        <tipoComprobante>'.$doc.'</tipoComprobante>
        <serieComprobante>'.$serie.'</serieComprobante>
        <numeroComprobante>'.$num.'</numeroComprobante>
        </ser:getStatus>
        </soapenv:Body>
        </soapenv:Envelope>';

        try {
            $result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
        } catch (Exception $e){
            echo "Error en conexion con sunat, espere y vuelva a intentarlo";
        }
        //echo $result;
        preg_match_all('/<statusCode>(.*?)<\/statusCode>/is', $result, $matches_codigo);
        preg_match_all('/<statusMessage>(.*?)<\/statusMessage>/is', $result, $matches_mensaje);

        if($matches_codigo[1][0] == '0001' || $matches_codigo[1][0] == '0003'){
            include "../conexion.php";
            $update = "update cab_doc_gen SET cdg_cod_snt='".$matches_codigo[1][0]."' WHERE cdg_num_doc='".$num."' and cdg_cla_doc='".$cla."' and cdg_cod_emp='".$emp."' and cdg_cod_gen='".$gen."'";
            $stmt = oci_parse($conn, $update);
            oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($stmt);
        }
    }

?>