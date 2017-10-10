<?php

    $gen = '02';
    $emp = '01';
    $fecha = '09-10-2017';

    /*Conexion
    ******************/
    include "conexion.php";

    /*Consulta Facturas
    **********************/
    $sql_facturas_dia = "select * from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy')='09-10-2017' and cdg_cod_emp='01' 
      and cdg_tip_doc in ('A') and  cdg_tip_ref in ('FS','FR','FC')
      union 
      select * from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy')='09-10-2017' and cdg_cod_emp='01' 
      and cdg_tip_doc in ('F')";
    $sql_parse = oci_parse($conn,$sql_facturas_dia);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros


    //$rfacturas=1;
    foreach ($documentos as $documento){

    }

    //echo $rfacturas;
    //print_r($facturas);

?>