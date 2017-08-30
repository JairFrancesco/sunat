<?php

    require("conexion.php");
    include "__soap.php";
    date_default_timezone_set('America/Lima');
    $gen = $_GET['gen'];
    $emp = $_GET['emp'];
    $dia = date("d-m-Y", strtotime($_GET['fecha']));
    include "__resumen_boleta_notas.php";

    if (isset($bols)){
        $i = 0;
        foreach ($bols as $bol)
        {
            $sub = 0;
            $desc = 0;
            $grabadas = 0;
            $igv = 0;
            $total = 0;
            $sql_rboletas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_num_doc between ".$bol[0]." and ".$bol[1]." and cdg_tip_doc='B' order by cdg_fec_gen ASC"); oci_execute($sql_rboletas);
            while($res_rboletas = oci_fetch_array($sql_rboletas))
            {
                $bols[$i]['subtotal'] = $sub = $sub +  $res_rboletas['CDG_VVP_TOT'];
                $bols[$i]['descuento'] = $desc = $desc + $res_rboletas['CDG_DES_TOT'];
                $bols[$i]['gravada'] = $grabadas = $grabadas + ($res_rboletas['CDG_VVP_TOT'] - $res_rboletas['CDG_DES_TOT']);
                $bols[$i]['igv'] = $igv = $igv + $res_rboletas['CDG_IGV_TOT'];
                $bols[$i]['total'] = $total = $total + $res_rboletas['CDG_IMP_NETO'];
            }
        }
    }

    if (isset($nots)) {
        foreach ($nots as $not){
            $sub = 0;
            $grabadas = 0;
            $igv = 0;
            $total = 0;
            $desc = 0;
            $sql_rnotas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='" . $gen . "' and cdg_cod_emp='" . $emp . "' and cdg_num_doc between " . $not[0] . " and " . $not[1] . " and cdg_tip_doc='A' order by cdg_fec_gen ASC");
            oci_execute($sql_rnotas);
            while ($res_rnotas = oci_fetch_array($sql_rnotas)){
                $nots[$i]['subtotal'] = $sub = $sub +  $res_rnotas['CDG_VVP_TOT'];
                $nots[$i]['descuento'] =  $desc = $desc + $res_rnotas['CDG_DES_TOT'];
                $nots[$i]['gravada'] = $grabadas = $grabadas + ($res_rnotas['CDG_VVP_TOT'] - $res_rnotas['CDG_DES_TOT']);
                $nots[$i]['igv'] = $igv = $igv + $res_rnotas['CDG_IGV_TOT'];
                $nots[$i]['total'] = $total = $total + $res_rnotas['CDG_IMP_NETO'];
            }
        }
    }
    
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
        
    // guarda en la tabla resumenes
    if (isset($bols)){
        foreach ($bols as $bol){

            // consulta los anteriores para los ticket
            $sql_anterior = "select * from resumenes where serie='".$bol[2]."' and  inicio='".$bol[0]."' and emp='".$emp."' ";
            $sql_parse = oci_parse($conn,$sql_anterior);
            oci_execute($sql_parse);
            oci_fetch_all($sql_parse, $anteriores, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            foreach ($anteriores as $anterior){
                // eliminar los anteriores
                $sql_delete = "DELETE FROM resumenes WHERE ticket= '".$anterior['TICKET']."' ";
                $stmt_delete = oci_parse($conn, $sql_delete);
                oci_execute($stmt_delete);
            }

            $sql_insert = "insert into resumenes (FECHA,TICKET,SERIE,INICIO,FINAL,SUBTOTAL,DESCUENTO,GRAVADA,IGV,TOTAL,CODIGO,EMP) values (to_date('".$_GET['fecha']."','yyyy-mm-dd'),'".$_GET['ticket']."','".$bol[2]."','".$bol[0]."','".$bol[1]."','".$bol['subtotal']."','".$bol['descuento']."','".$bol['gravada']."','".$bol['igv']."','".$bol['total']."','0','".$emp."')";
            $stmt_insert = oci_parse($conn, $sql_insert);
            oci_execute($stmt_insert);
        }
    }
    if (isset($nots)){
        foreach ($nots as $not){
            $sql_insert = "insert into resumenes (FECHA,TICKET,SERIE,INICIO,FINAL,SUBTOTAL,DESCUENTO,GRAVADA,IGV,TOTAL,CODIGO,EMP) values (to_date('".$_GET['fecha']."','yyyy-mm-dd'),'".$_GET['ticket']."','".$not[2]."','".$not[0]."','".$not[1]."','".$not['subtotal']."','".$not['descuento']."','".$not['gravada']."','".$not['igv']."','".$not['total']."','0','".$emp."')";
            $stmt_insert = oci_parse($conn, $sql_insert);
            oci_execute($stmt_insert);
        }
    }
?>