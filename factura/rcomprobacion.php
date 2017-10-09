<?php

    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    try {

        include ('__soap.php');
        $wsdlURL = "billService.wsdl";
        $ticket = $_GET['ticket'];
        if(isset($_GET['op'])){
            $op = $_GET['op'];
        }else{
            $op = '';
        }

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
                if($op == 'terminar'){
                    $dia = date("d-m-Y", strtotime($_GET['fecha']));
                    $gen = $_GET['gen'];
                    $emp = $_GET['emp'];

                    include "conexion.php";
                    include "__resumen.php";
                    $update = "update resumenes SET CODIGO='".$codigo."' WHERE ticket='".$ticket."'";
                    $stmt = oci_parse($conn, $update);
                    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($stmt);

                    if (isset($boletas)){
                        foreach ( $boletas as $boleta ){
                            //para saber si es boleta o nota
                            if ($boleta['serie']=='BN03' || $boleta['serie']=='BN04'){
                                $tip_doc='A';
                            }else{
                                $tip_doc='B';
                            }
                            $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='0001' WHERE cdg_num_doc >= '".$boleta['first']."' and cdg_num_doc <= '".$boleta['last']."' and cdg_ser_doc='".$boleta['serie'][3]."' and cdg_tip_doc='".$tip_doc."' and cdg_cod_emp='".$emp."'";
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