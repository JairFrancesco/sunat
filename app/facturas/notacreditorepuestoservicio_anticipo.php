<?php

    // obtener cabezera de nota de credito
    // ===================================
    $sql_ncc_cab = "begin PKG_ELECTRONICA.ncc('".$gem."','".$emp."',".$num_doc.",:doc); end;";
    $stid_ncc = oci_parse($conn,$sql_ncc_cab);
    $curs_ncc = oci_new_cursor($conn);
    oci_bind_by_name($stid_ncc, ":doc", $curs_ncc, -1, OCI_B_CURSOR);
    oci_execute($stid_ncc);
    oci_execute($curs_ncc);
    while (($row_ncc = oci_fetch_array($curs_ncc, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        $cab = $row_ncc;
    }

    // Detalle
    // =======
    $sql_fecha_doc_modifica = "select b.cdg_not_001 as b_cdg_not_001, a.CDG_NOT_001 as A_CDG_NOT_001 from cab_doc_gen a inner join cab_doc_gen b 
    on b.cdg_num_doc=a.CDG_DOC_REF and b.cdg_cla_doc=a.cdg_tip_ref where a.cdg_num_doc=".$num_doc." and a.cdg_cod_gen=".$gem." and a.cdg_cod_emp=".$emp." and a.cdg_cla_doc='".$cla_doc."'";
    $fecha_doc_modifica = oci_parse($conn, $sql_fecha_doc_modifica);
    oci_execute($fecha_doc_modifica);
    while ($filas = oci_fetch_array($fecha_doc_modifica, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $det = $filas['B_CDG_NOT_001'];
        $nota = 'MOTIVO : '.$filas['A_CDG_NOT_001'];
    }

    // variables de la factura del c1 ... c20 el c18 es el que identifica el detalle
    // ============================================================================


    // C1 nombre de la factura
    if ($cla_doc=='FS'){
        $title = "Factura de Servicios ".substr($cab['NOMBRE20'],15,13);
    }elseif ($cla_doc=='FR'){
        $title = "Factura de Repuestos ".substr($cab['NOMBRE20'],15,13);
    }elseif ($cla_doc=='BS'){
        $title = "Boleta de Servicios ".substr($cab['NOMBRE20'],15,13);
    }elseif ($cla_doc=='BR'){
        $title = "Boleta de Repuestos ".substr($cab['NOMBRE20'],15,13);
    }elseif ($cla_doc=='AR'){
        $title = "Nota Credito de Repuestos ".substr($cab['NOMBRE20'],15,13);
    }elseif ($cla_doc=='AS'){
        $title = "Nota Credito de Servicios ".substr($cab['NOMBRE20'],15,13);
    }
    $c1 = $title;

    // C2 fecha
    $c2 =  'Fecha';
    $c3 = $cab['FECEMISION0'];;

    // C4 Forma de Pago
    $c4 = "Forma de Pago";
    $c5 = $cab['CDG_CO_CR'];

    // Cliente
    $c6 = 'Cliente';
    $c7 = $cab['RZNSOCIALUSUARIO7'];

    // Nombre
    $c8 = 'Nombre';
    $c9 = 'Surmotriz S.R.L - 20532710066';

    // ruc o dni
    if ($cab['TIPDOCUSUARIO5'] == '6') {
        $documento = 'RUC';
    }elseif($cab['TIPDOCUSUARIO5'] == '1'){
        $documento = 'DNI';
    }else{
        $documento = 'Carnet Extranjero';
    }
    $c10 = $documento;
    $c11 = $cab['NUMDOCUSUARIO6'];


    // Factura que modifica
    $c12 = 'Factura que Modifica';
    $c13 = $cab['NUMDOCUSUARIO4'];

    // Direccion
    $c14 = 'Direccion';
    $c15 = $cab['CDG_DIR_CLI'];

    // Fecha Documento que modifica
    $sql_fecha_doc_modifica = "select b.cdg_fec_gen from cab_doc_gen a inner join cab_doc_gen b 
            on b.cdg_num_doc=a.CDG_DOC_REF and b.cdg_cla_doc=a.cdg_tip_ref where a.cdg_num_doc=".$num_doc." and a.cdg_cod_gen=".$gem." and a.cdg_cod_emp=".$emp." and a.cdg_cla_doc='".$cla_doc."'";
    $fecha_doc_modifica = oci_parse($conn, $sql_fecha_doc_modifica);
    oci_execute($fecha_doc_modifica);
    while ($filas = oci_fetch_array($fecha_doc_modifica, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $fecha = $filas['CDG_FEC_GEN'];
    }
    $c16 = 'Fecha que Doc Modf';
    $c17 = $fecha;

    $c18 = 'N'; // identificador del detalle

    $c19 = 'PEN';

    $c20 = $nota;
    $c21 = 'OP. GRATUITAS';
    $c22 = '0.00';

    $c23 = '';
    $c24 = 'OP. EXONERADA';
    $c25 = $cab['MTOOPEREXONERADAS12'];

    $c26 = '';
    $c27 = 'OP. INAFECTA';
    $c28 = $cab['MTOOPERINAFECTAS11'];

    $c29 = '';
    $c30 = 'OP. GRAVADA';
    $c31 = $cab['MTOOPERGRAVADAS10'];

    $c32 = '';
    $c33 = 'TOTAL. DSCTO';
    $c34 = $cab['SUMOTROSCARGOS9'];

    $c35 = '';
    $c36 = 'I.S.C.';
    $c37 = '0.00';

    $c38 = '';
    $c39 = 'I.G.V.';
    $c40 = $cab['MTOIGV13'];

    $c41 = '';
    $c42 = 'Total';
    $c43 = $cab['MTOIMPVENTA16'];
    $c44 = '';





