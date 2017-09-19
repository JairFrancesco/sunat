<?php
	header('Content-type: application/json');
	include('../conexion.php');
	$sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='02' and cdg_cod_emp='01' and to_char(cdg_fec_gen,'dd-mm-yyyy')='19-09-2017'";
	$sql_parse = oci_parse($conn,$sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    print_r (json_encode($documentos));
?>