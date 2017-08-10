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
<tipoComprobante>' . $doc . '</tipoComprobante>
<serieComprobante>' . $serie . '</serieComprobante>
<numeroComprobante>' . $cab_doc_gen['CDG_NUM_DOC'] . '</numeroComprobante>
</m:getStatusCdr>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';
    $result = soapCall($wsdlURL, $callFunction = "getStatusCdr", $XMLString);
    //echo $result;
    preg_match_all('/<statusCode>(.*?)<\/statusCode>/is', $result, $matches_codigo);
    preg_match_all('/<statusMessage>(.*?)<\/statusMessage>/is', $result, $matches_mensaje);
    if($matches_codigo[1][0]=='0004' || $matches_codigo[1][0] == '0001'){
        echo '<img src="images/successful.jpg" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
        echo '<div style="text-align: center;"><strong>'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong> Si el codigo sale 0004 o 0001 esta bien, pero si sale otro esta mal. <br><strong>Codigo :</strong> ' . $matches_codigo[1][0] . '<br> <strong>Mensaje :</strong> ' . $matches_mensaje[1][0].'<br>';
        echo '<a href="terminar.php?gen='.$cab_doc_gen['CDG_COD_GEN'].'&emp='.$cab_doc_gen['CDG_COD_EMP'].'&tip='.$cab_doc_gen['CDG_TIP_DOC'].'&num='.$cab_doc_gen['CDG_NUM_DOC'].'"><button class="action bluebtn"><span class="label"><strong>Terminar '.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong></span></button></a></div>';
    }else{
        echo '<img src="images/error.png" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
        echo '<div style="text-align: center;"><strong>'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong> Si el codigo sale 0004 o 0001 esta bien, pero si sale otro esta mal. <br><strong>Codigo :</strong> ' . $matches_codigo[1][0] . '<br> <strong>Mensaje :</strong> ' . $matches_mensaje[1][0].'</div>';
    }
}catch (Exception $e) {
    echo '<img src="images/error.png" width="400" height="395" style="display:block; margin:auto;" alt=""><br>';
    echo '<div style="text-align: center;"><strong>'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'</strong> No existe conexion con sunat intentar mas tarde o avisar a sistemas@surmotriz.com</div>';
}
?>