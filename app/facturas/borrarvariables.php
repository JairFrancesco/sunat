<?php

    // obtener cabezera
    // =================
    $sql_cab = "begin PKG_ELECTRONICA.fbc('".$gem."','".$emp."',".$num_doc.",'".$cla_doc."',:doc); end;";
    $stid = oci_parse($conn,$sql_cab);
    $curs_cab = oci_new_cursor($conn);
    oci_bind_by_name($stid, ":doc", $curs_cab, -1, OCI_B_CURSOR);
    oci_execute($stid);
    oci_execute($curs_cab);
    while (($row_cab = oci_fetch_array($curs_cab, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        // cab es todas las variables de cabezera
        $cab = $row_cab;
        print_r($cab);
    }


    // detalle unico de resumen
    // ========================
    $sql_factura_detalle_resumen = "select cdg_ten_res from cab_doc_gen where cdg_num_doc=".$num_doc." and cdg_cod_gen=02 and cdg_cod_emp=01 and cdg_cla_doc='FS'";
    $factura_detalle_resumen = oci_parse($conn, $sql_factura_detalle_resumen);
    oci_execute($factura_detalle_resumen);
    while ($fila_factura_detalle_resumen = oci_fetch_array($factura_detalle_resumen, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $det = $fila_factura_detalle_resumen['CDG_TEN_RES'];
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
    }
    $c1 = $title;

    // C2 fecha
    $c2 =  'Fecha';
    $c3 = $cab['FECEMISION1'];;

    // C4 Forma de Pago
    $c4 = "Forma de Pago";
    $c5 = $cab['DOC23'];

    // Cliente
    $c6 = 'Cliente';
    $c7 = $cab['RZNSOCIALUSUARIO5'];

    // Nombre
    $c8 = 'Nombre';
    $c9 = 'Surmotriz S.R.L';

    // ruc o dni
    if ($cab['TIPDOCUSUARIO3'] == '6') {
        $documento = 'RUC';
    }elseif($cab['TIPDOCUSUARIO3'] == '1'){
        $documento = 'DNI';
    }else{
        $documento = 'Carnet Extranjero';
    }
    $c10 = $documento;
    $c11 = $cab['NUMDOCUSUARIO4'];


    // ruc surmotriz
    $c12 = 'RUC';
    $c13 = '20532710066';

    // Direccion
    $c14 = 'Direccion';
    $c15 = $cab['DOC24'];

    // Direccion local
    $c16 = 'Direccion';
    $c17 = 'Av. Leguia Nº 1870';

    $c18 = 'N'; // identificador del detalle

    $c19 = 'PEN';

    $c20 = '';
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
    $c34 = $cab['MTODESCUENTOS9'];

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

    // VARIABLES PARA IDENTIFICAR FACTURA ELECTRONICA
    // ==============================================

    $f1 = $cab['NOMBRE17'];
    $f2 = $cab['NUMDOCUSUARIO4'];

    // tipo de factura
    if ($cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC'){
        $tipo = '01';
    }elseif ($cla_doc=='BS' || $cla_doc=='BR'){
        $tipo = '03';
    }else{
        $tipo = '';
    }

    // factura o boleta 03 01
    $f3 = $tipo;

    // nombre de factura o boleta
    $f4 = substr($cab['NOMBRE20'],15,13);

    $f5 = $cab['NOMBRE20'];

    $f6 = substr($cab['NOMBRE20'],0,28);

    $f7 = './app/repo/'.date("Y").'/'.date("m").'/'.date("d").'/';
    $f8 = substr($cab['NOMBRE20'],0,28);

    // dni o factura 1 dni, 6 factura, 4 carnet extranjeria
    $f9 = $cab['TIPDOCUSUARIO3'];

    // nombre comercial surmotriz
    $f10 = 'TOYOTA SURMOTRIZ';

    // f11 tipo de factura o boleta

    // soles o dolares
    if ($cab['TIPMONEDA6']=='PEN'){
        $f12 = 'SOLES';
        $f13 = ' S/';
    }else{
        $f12 = 'DOLARES';
        $f13 = ' $';
    }
