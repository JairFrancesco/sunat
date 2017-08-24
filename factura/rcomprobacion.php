<?php

    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    try {

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
        echo $codigo;
        echo '<div style="text-align: center;">';
            if($codigo == '0'){
                if($_GET['op']=='terminar'){
                    include "conexion.php";
                    include "__resumen_boleta_notas.php";
                    $update = "update resumenes SET CODIGO='".$codigo."' WHERE ticket='".$ticket."'";
                    $stmt = oci_parse($conn, $update);
                    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($stmt);

                    if (isset($boletas)){
                        foreach ( $boletas as $boleta ){
                            $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='0001' WHERE cdg_num_doc='".$boleta['CDG_NUM_DOC']."' and cdg_cla_doc='".$boleta['CDG_CLA_DOC']."' and cdg_cod_emp='".$boleta['CDG_COD_EMP']."' and cdg_cod_gen='".$boleta['CDG_COD_GEN']."'";
                            $stmt = oci_parse($conn, $update);
                            oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                            oci_free_statement($stmt);
                        }
                    }
                    if (isset($notas)) {
                        foreach ($notas as $nota) {
                            $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='0001' WHERE cdg_num_doc='".$nota['CDG_NUM_DOC']."' and cdg_cla_doc='".$nota['CDG_CLA_DOC']."' and cdg_cod_emp='".$nota['CDG_COD_EMP']."' and cdg_cod_gen='".$nota['CDG_COD_GEN']."'";
                            $stmt = oci_parse($conn, $update);
                            oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                            oci_free_statement($stmt);
                        }
                    }
                }
                echo '<img src="./images/ok.png"><br>';
                echo 'El Resumen existe y fue procesado correctamente Nro '.$ticket;
            }else{
                echo '<img src="./images/error.png"><br>';
                echo 'Error codigo '.$codigo;
            }
        echo '</div>';
    }catch (Exception $e) {
        echo '<div style="text-align: center;">';
            echo '<img src="./images/error.png"><br>';
            echo 'No hay conexion con SUNAT vuelva intentarlo mas tarde.';
        echo '</div>';
    }
?>