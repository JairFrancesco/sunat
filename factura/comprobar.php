<?php

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

try {
    class feedSoap extends SoapClient
    {
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
            try {
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
        $reply = $client->SoapClientCall($XMLString);
        //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
        $client->__call("$callFunction", array(), array());
        //$request = prettyXml($client->__getLastRequest());
        //echo highlight_string($request, true) . "<br/>\n";
        return $client->__getLastResponse();
        //print_r($client);
    }

    require('conexion.php');
    /* PARAMETROS GET
    ***********************************/
    $gen = $_GET['gen'];
    $emp = $_GET['emp'];
    $tip = $_GET['tip'];
    $num = $_GET['num'];


    /* CONSULTA CAB_DOC_GEN
    *****************************************************************************/
    $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='" . $gen . "' and cdg_cod_emp='" . $emp . "' and cdg_tip_doc='" . $tip . "' and cdg_num_doc='" . $num . "'";
    $sql_parse = oci_parse($conn, $sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    $cab_doc_gen = $cab_doc_gen[0];

    /* DOC Y SERIE 01-F001
    *******************/
    if ($cab_doc_gen['CDG_TIP_DOC'] == 'F') {
        $doc = '01';
        $serie = 'F00' . $cab_doc_gen['CDG_SER_DOC'];
    } elseif ($cab_doc_gen['CDG_TIP_DOC'] == 'B') {
        $doc = '03';
        $serie = 'B00' . $cab_doc_gen['CDG_SER_DOC'];
    } elseif ($cab_doc_gen['CDG_TIP_DOC'] == 'A') {
        $doc = '07';
        if ($cab_doc_gen['CDG_TIP_REF'] == 'BR' || $cab_doc_gen['CDG_TIP_REF'] == 'BS') {
            $serie = 'BN0' . $cab_doc_gen['CDG_SER_DOC'];
        } elseif ($cab_doc_gen['CDG_TIP_REF'] == 'FR' || $cab_doc_gen['CDG_TIP_REF'] == 'FS' || $cab_doc_gen['CDG_TIP_REF'] == 'FC') {
            $serie = 'FN0' . $cab_doc_gen['CDG_SER_DOC'];
        }
    }


    //header('Content-Type: text/xml; charset=UTF-8');
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
 <numeroComprobante>'.$cab_doc_gen['CDG_NUM_DOC'].'</numeroComprobante>
 </ser:getStatus>
 </soapenv:Body>
</soapenv:Envelope>';

    $result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
    //echo $result;
    preg_match_all('/<statusCode>(.*?)<\/statusCode>/is', $result, $matches_codigo);
    preg_match_all('/<statusMessage>(.*?)<\/statusMessage>/is', $result, $matches_mensaje);
    if($matches_codigo[1][0] == '0001' || $matches_codigo[1][0] == '0003'){
        $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='".$matches_codigo[1][0]."' WHERE cdg_num_doc='".$cab_doc_gen['CDG_NUM_DOC']."' and cdg_cla_doc='".$cab_doc_gen['CDG_CLA_DOC']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."'";
        $stmt = oci_parse($conn, $update);
        oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stmt);

        echo '<img src="images/successful.jpg" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
        echo '<div style="text-align: center;">'.$matches_codigo[1][0].'-'.$matches_mensaje[1][0].' '.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'];
        //echo '<a href="terminar.php?gen='.$cab_doc_gen['CDG_COD_GEN'].'&emp='.$cab_doc_gen['CDG_COD_EMP'].'&tip='.$cab_doc_gen['CDG_TIP_DOC'].'&num='.$cab_doc_gen['CDG_NUM_DOC'].'"><button class="action bluebtn"><span class="label"><strong>Terminar '.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong></span></button></a></div>';
        echo '</div>';
    }else{
        echo '<img src="images/error.png" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
        echo '<div style="text-align: center;">'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].' '.$matches_codigo[1][0].' '.$matches_mensaje[1][0].'</div>';
    }
}catch (Exception $e) {
    echo '<img src="images/error.png" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
    echo '<div style="text-align: center;"><strong>'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong> No existe conexion con sunat intentar mas tarde o avisar a sistemas@surmotriz.com</div>';
}
?>