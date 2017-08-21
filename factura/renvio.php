<?php
    date_default_timezone_set('America/Lima');
    $gen = '02';
    $emp = '01';
    $dia = '18-08-2017';

    require("conexion.php");

    $archivo = file_get_contents('http://localhost/sunat/resumen_envio.php?gen=02&emp=01&fecha=2017-08-18');

    $sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and (cdg_anu_sn, cdg_doc_anu) in (('S','N'),('N','N')) and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_num_doc Asc"); oci_execute($sql_boletas);
    while($res_boletas = oci_fetch_array($sql_boletas)){
        //echo $res_boletas['CDG_NUM_DOC'].' '.$res_boletas['CDG_FEC_GEN'].'<br>';
        $boletas[] = $res_boletas;
    }

    $sql_notas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_num_doc ASC"); oci_execute($sql_notas);
    while($res_notas = oci_fetch_array($sql_notas)){
        $notas[] = $res_notas;
    }


    // Boletas
    if (isset($boletas))
    {
        foreach ($boletas as $cab_doc_gen){
            $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='".$archivo."' WHERE cdg_num_doc='".$cab_doc_gen['CDG_NUM_DOC']."' and cdg_cla_doc='".$cab_doc_gen['CDG_CLA_DOC']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."'";
            $stmt = oci_parse($conn, $update);
            oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($stmt);
        }
    }
    // Notas
    if (isset($notas)){
        foreach ($notas as $cab_doc_gen){
            $update = "update cab_doc_gen SET cdg_sun_env='S', cdg_cod_snt='".$archivo."' WHERE cdg_num_doc='".$cab_doc_gen['CDG_NUM_DOC']."' and cdg_cla_doc='".$cab_doc_gen['CDG_CLA_DOC']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."'";
            $stmt = oci_parse($conn, $update);
            oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($stmt);
        }
    }

    echo $archivo;
?>