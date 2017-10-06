<?php

    $gen = '02';
    $fecha = date("d-m-Y");

    /*Consulta Facturas
    **********************/
    $sql_facturas_dia = "select * from cab_doc_gen where cdg_tip_doc='F' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$fecha."' and cdg_cod_emp='".$emp."' and cdg_cod_gen='".$gen."'";
    $sql_parse = oci_parse($conn,$sql_facturas_dia);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $facturas, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros

    $rfacturas=1;
    foreach ($facturas as $factura){
        if ($factura['CDG_COD_SNT'] ==''){
            $rfacturas=1;
        }
    }
    //echo $rfacturas;
    //print_r($facturas);

?>