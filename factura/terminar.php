<?php

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

    $update = "update cab_doc_gen SET cdg_sun_env='S' WHERE cdg_num_doc='".$cab_doc_gen['CDG_NUM_DOC']."' and cdg_cla_doc='".$cab_doc_gen['CDG_CLA_DOC']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."'";
    $stmt = oci_parse($conn, $update);
    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    oci_free_statement($stmt);

    echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";

?>