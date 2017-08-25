<?php

    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    try {

        $serie = $_GET['serie'];
        $num = $_GET['num'];
        $fecha = $_GET['fecha'];

        include "conexion.php";
        include('__soap.php');
        $wsdlURL = "billService.wsdl";

        $sql_resumen = "select * from resumenes where serie='" . $serie . "' and to_char(fecha,'dd-mm-yyyy')='" . $fecha . "'";
        $parse_resumen = oci_parse($conn, $sql_resumen);
        oci_execute($parse_resumen);
        oci_fetch_all($parse_resumen, $resumenes, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        foreach ($resumenes as $resumen) {
            if ($resumen['INICIO'] <= $num && $num <= $resumen['FINAL']) {
                $check = 1;
            } else {
                $check = 0;
            }
        }

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
        <ticket>' . $resumenes[0]['TICKET'] . '</ticket>
        </ser:getStatus>
        </soapenv:Body>
        </soapenv:Envelope>';

        preg_match_all('/<statusCode>(.*?)<\/statusCode>/is', soapCall($wsdlURL, $callFunction = "getStatus", $XMLString), $codigo);
        $codigo = $codigo[1][0];

        echo '<div style="text-align: center;">';
        if ($codigo == '0') {

            echo '<img src="./images/ok.png"><br>';
            echo 'La Boleta o Nota ' . $serie . '-' . $num . ' fue procesado con exito Codigo ' . $codigo . ' , Tiket ' . $resumenes[0]['TICKET'];

        } else {
            echo '<img src="./images/error.png"><br>';
            echo 'Error codigo ' . $codigo;
        }
        echo '</div>';
    }catch (Exception $e) {
        echo '<div style="text-align: center;">';
        echo '<img src="./images/error.png"><br>';
        echo 'No hay con SUNAT';
        echo '</div>';
    }

?>