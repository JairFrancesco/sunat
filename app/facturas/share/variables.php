<?php


    if ( $cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BS' || $cla_doc=='BR') {

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
            //print_r($row_cab);
        }

        if (($co_cr_an=='CO' || $co_cr_an=='CR') && $tip_imp == 'D') {
            //echo 'ingreso';
            // obteniendo el detalle
            // =====================
            /*
            $sql_dds = "begin PKG_ELECTRONICA.dds('".$gem."','".$emp."',".$num_doc.",'".$cla_doc."','PEN',:dds); end;";
            $stid_dds = oci_parse($conn,$sql_dds);
            $curs_dds = oci_new_cursor($conn);
            oci_bind_by_name($stid_dds, ":dds", $curs_dds, -1, OCI_B_CURSOR);
            oci_execute($stid_dds);
            oci_execute($curs_dds);
            while (($row_dds = oci_fetch_array($curs_dds, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                $dets[] = $row_dds;
            }
            */
            $valor_detalle = 'COCRD';



            $sql_repuestos = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(ddr_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
                ddr_cod_pro as codProducto2, -- 2
                '0000' as codProductoSUNAT3, -- 3
                (select lpr_des_pro from LIS_PRE_REP where lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro) as desItem4, -- 4
                to_char(decode('".$moneda."','PEN',ddr_vvp_sol, ddr_vvp_dol),'FM99990.00') as mtoValorUnitario5, -- 5
                to_char(decode('".$moneda."','PEN',((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)),((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100))),'FM99990.00') as mtoDsctoItem6, --6
                to_char(decode('".$moneda."','PEN',(((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)))*0.18),(((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))*0.18)),'FM99990.00') as mtoIgvItem7, -- 7
                '10' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                to_char(decode('".$moneda."','PEN',(ddr_can_pro*ddr_vvp_sol),(ddr_can_pro*ddr_vvp_dol)),'FM99990.00')  as mtoPrecioVentaItem11, -- 11
                to_char(decode('".$moneda."','PEN',((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100))), ((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))),'FM99990.00') as mtoValorVentaItem12 --12
                from DET_DOC_REP where DDR_COD_GEN='".$gem."' and DDR_COD_EMP='".$emp."' and DDR_NUM_DOC='".$num_doc."' and DDR_CLA_DOC='".$cla_doc."' ORDER BY rowid Desc");
            oci_execute($sql_repuestos);
            while($res_repuestos = oci_fetch_array($sql_repuestos)){ $dets[] = $res_repuestos; }

            $sql_servicios = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(dds_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
                dds_cod_pro as codProducto2, -- 2
                '0000' as codProductoSUNAT3, -- 3
                dds_des_001 as desItem4, -- 4
                to_char(round((decode('".$moneda."','PEN',dds_vvp_sol,dds_vvp_dol)),2),'FM99990.00') as mtoValorUnitario5, -- 5
                to_char(round(decode('".$moneda."','PEN',((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)), ((dds_can_pro*dds_vvp_dol)*(dds_por_des/100))),2),'FM99990.00') as mtoDsctoItem6, -- 6
                to_char(round(decode('".$moneda."','PEN',(((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)))*0.18), (((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))*0.18)),2),'FM99990.00') as mtoIgvItem7, -- 7
                '10' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                to_char(round(decode('".$moneda."','PEN',(dds_can_pro*dds_vvp_sol),(dds_can_pro*dds_vvp_dol)),2),'FM99990.00') as mtoPrecioVentaItem11, -- 11 importe
                to_char(round(decode('".$moneda."','PEN',((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100))), ((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))),2),'FM99990.00') as mtoValorVentaItem12 -- 12        
                from DET_DOC_SER where DDS_COD_GEN='".$gem."' and DDS_COD_EMP='".$emp."' and DDS_NUM_DOC='".$num_doc."' and DDS_CLA_DOC='".$cla_doc."' ORDER BY rowid Desc");
            oci_execute($sql_servicios);
            while($res_servicios = oci_fetch_array($sql_servicios)){ $dets[] = $res_servicios; }

            $sql_otros = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(0,2),'FM99990.00') as ctdUnidadItem1, -- 1
                '0' as codProducto2, -- 2
                '0' as codProductoSUNAT3, -- 3
                ddo_des_otr as desItem4, -- 4
                '0.00' as mtoValorUnitario5, -- 5
                '0.00' as mtoDsctoItem6, -- 6
                '0.00' as mtoIgvItem7, -- 7
                '30' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                '0.00' as mtoPrecioVentaItem11, -- 11 importe
                '0.00' as mtoValorVentaItem12, -- 12
                'A' as orden
                from DET_DOC_OTR where DDO_COD_GEN='".$gem."' and DDO_COD_EMP='".$emp."' and DDO_NUM_DOC='".$num_doc."' and DDO_CLA_DOC='".$cla_doc."'");
            oci_execute($sql_otros);
            while($res_otros = oci_fetch_array($sql_otros)){ $dets[] = $res_otros; }



        } elseif (($co_cr_an=='CO' || $co_cr_an=='CR') && $tip_imp == 'R') {
            //echo 'ingreso';
            // detalle unico de resumen
            // ========================
            $sql_factura_detalle_resumen = "select cdg_ten_res from cab_doc_gen where cdg_num_doc=".$num_doc." and cdg_cod_gen=".$gem." and cdg_cod_emp=".$emp." and cdg_cla_doc='".$cla_doc."'";
            $factura_detalle_resumen = oci_parse($conn, $sql_factura_detalle_resumen);
            oci_execute($factura_detalle_resumen);
            while ($fila_factura_detalle_resumen = oci_fetch_array($factura_detalle_resumen, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $det = $fila_factura_detalle_resumen['CDG_TEN_RES'];
            }
            $valor_detalle = 'COCRR';
        } elseif ($co_cr_an == 'AN' && $tip_imp == 'D'){
            // detalle unico anticipo
            // ======================
            $sql_boleta_anticipo_descriptcion = "select cdg_not_001, cdg_not_002, cdg_not_003 from cab_doc_gen where cdg_num_doc=".$num_doc." and cdg_cod_gen=02 and cdg_cod_emp='".$emp."' and cdg_cla_doc='".$cla_doc."'";
            $boleta_anticipo_descriptcion = oci_parse($conn, $sql_boleta_anticipo_descriptcion);
            oci_execute($boleta_anticipo_descriptcion);
            while ($boleta_anticipo_descriptcion_detalle = oci_fetch_array($boleta_anticipo_descriptcion, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $det = $boleta_anticipo_descriptcion_detalle['CDG_NOT_001'].' '.$boleta_anticipo_descriptcion_detalle['CDG_NOT_002'].' '.$boleta_anticipo_descriptcion_detalle['CDG_NOT_003'];
            }
            $valor_detalle = 'AND';
        }
    } elseif ($cla_doc =='AR' || $cla_doc == 'AS') {

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
            //print_r($row_ncc);
        }



        if (($co_cr_an=='CO' || $co_cr_an=='CR') && $tip_imp == 'D') {
            // detalles
            // ========
            /*
            $sql_dds = "begin PKG_ELECTRONICA.dds('".$gem."','".$emp."',".$num_doc.",'".$cla_doc."','".$moneda."',:dds); end;";
            $stid_dds = oci_parse($conn,$sql_dds);
            $curs_dds = oci_new_cursor($conn);
            oci_bind_by_name($stid_dds, ":dds", $curs_dds, -1, OCI_B_CURSOR);
            oci_execute($stid_dds);
            oci_execute($curs_dds);
            while (($row_dds = oci_fetch_array($curs_dds, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                $dets[] = $row_dds;
            }
            */
            $valor_detalle = 'ARAND';


            $sql_repuestos = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(ddr_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
                ddr_cod_pro as codProducto2, -- 2
                '0000' as codProductoSUNAT3, -- 3
                (select lpr_des_pro from LIS_PRE_REP where lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro) as desItem4, -- 4
                to_char(decode('".$moneda."','PEN',ddr_vvp_sol, ddr_vvp_dol),'FM99990.00') as mtoValorUnitario5, -- 5
                to_char(decode('".$moneda."','PEN',((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)),((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100))),'FM99990.00') as mtoDsctoItem6, --6
                to_char(decode('".$moneda."','PEN',(((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)))*0.18),(((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))*0.18)),'FM99990.00') as mtoIgvItem7, -- 7
                '10' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                to_char(decode('".$moneda."','PEN',(ddr_can_pro*ddr_vvp_sol),(ddr_can_pro*ddr_vvp_dol)),'FM99990.00')  as mtoPrecioVentaItem11, -- 11
                to_char(decode('".$moneda."','PEN',((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100))), ((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))),'FM99990.00') as mtoValorVentaItem12 --12
                from DET_DOC_REP where DDR_COD_GEN='".$gem."' and DDR_COD_EMP='".$emp."' and DDR_NUM_DOC='".$num_doc."' and DDR_CLA_DOC='".$cla_doc."' ORDER BY rowid Desc");
            oci_execute($sql_repuestos);
            while($res_repuestos = oci_fetch_array($sql_repuestos)){ $dets[] = $res_repuestos; }

            $sql_servicios = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(dds_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
                dds_cod_pro as codProducto2, -- 2
                '0000' as codProductoSUNAT3, -- 3
                dds_des_001 as desItem4, -- 4
                to_char(round((decode('".$moneda."','PEN',dds_vvp_sol,dds_vvp_dol)),2),'FM99990.00') as mtoValorUnitario5, -- 5
                to_char(round(decode('".$moneda."','PEN',((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)), ((dds_can_pro*dds_vvp_dol)*(dds_por_des/100))),2),'FM99990.00') as mtoDsctoItem6, -- 6
                to_char(round(decode('".$moneda."','PEN',(((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)))*0.18), (((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))*0.18)),2),'FM99990.00') as mtoIgvItem7, -- 7
                '10' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                to_char(round(decode('".$moneda."','PEN',(dds_can_pro*dds_vvp_sol),(dds_can_pro*dds_vvp_dol)),2),'FM99990.00') as mtoPrecioVentaItem11, -- 11 importe
                to_char(round(decode('".$moneda."','PEN',((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100))), ((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))),2),'FM99990.00') as mtoValorVentaItem12 -- 12        
                from DET_DOC_SER where DDS_COD_GEN='".$gem."' and DDS_COD_EMP='".$emp."' and DDS_NUM_DOC='".$num_doc."' and DDS_CLA_DOC='".$cla_doc."' ORDER BY rowid Desc");
            oci_execute($sql_servicios);
            while($res_servicios = oci_fetch_array($sql_servicios)){ $dets[] = $res_servicios; }

            $sql_otros = oci_parse($conn, "select
                'NIU' as codUnidadMedida0, -- 0
                to_char(round(0,2),'FM99990.00') as ctdUnidadItem1, -- 1
                '0' as codProducto2, -- 2
                '0' as codProductoSUNAT3, -- 3
                ddo_des_otr as desItem4, -- 4
                '0.00' as mtoValorUnitario5, -- 5
                '0.00' as mtoDsctoItem6, -- 6
                '0.00' as mtoIgvItem7, -- 7
                '30' as tipAfeIGV8, -- 8
                '0.00' as mtoIscItem9, -- 9
                '02' as tipSisISC10, -- 10
                '0.00' as mtoPrecioVentaItem11, -- 11 importe
                '0.00' as mtoValorVentaItem12, -- 12
                'A' as orden
                from DET_DOC_OTR where DDO_COD_GEN='".$gem."' and DDO_COD_EMP='".$emp."' and DDO_NUM_DOC='".$num_doc."' and DDO_CLA_DOC='".$cla_doc."'");
            oci_execute($sql_otros);
            while($res_otros = oci_fetch_array($sql_otros)){ $dets[] = $res_otros; }


        } elseif ($co_cr_an == 'AN') {
            // Detalle
            // =======
            $sql_fecha_doc_modifica = "select b.cdg_not_001 as b_cdg_not_001, a.CDG_NOT_001 as A_CDG_NOT_001 from cab_doc_gen a inner join cab_doc_gen b on b.cdg_num_doc=a.CDG_DOC_REF and b.cdg_cla_doc=a.cdg_tip_ref where a.cdg_num_doc=".$num_doc." and a.cdg_cod_gen=".$gem." and a.cdg_cod_emp=".$emp." and a.cdg_cla_doc='".$cla_doc."'";
            $fecha_doc_modifica = oci_parse($conn, $sql_fecha_doc_modifica);
            oci_execute($fecha_doc_modifica);
            while ($filas = oci_fetch_array($fecha_doc_modifica, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $det = $filas['B_CDG_NOT_001'];
                $nota = 'MOTIVO : '.$filas['A_CDG_NOT_001'];
            }
            $valor_detalle = 'NANDR';
        } elseif ($co_cr_an=='CR' && $tip_imp == 'R' ){
            $sql_factura_detalle_resumen = "select cdg_ten_res from cab_doc_gen where cdg_num_doc=".$num_doc." and cdg_cod_gen=".$gem." and cdg_cod_emp=".$emp." and cdg_cla_doc='".$cla_doc."'";
            $factura_detalle_resumen = oci_parse($conn, $sql_factura_detalle_resumen);
            oci_execute($factura_detalle_resumen);
            while ($fila_factura_detalle_resumen = oci_fetch_array($factura_detalle_resumen, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $det = $fila_factura_detalle_resumen['CDG_TEN_RES'];
            }
            $valor_detalle = 'NANDR';
        }
    }




    // variables de la factura del c1 ... c20 el c18 es el que identifica el detalle
    // ============================================================================


    // C1 nombre de la factura
    if ($cla_doc=='FS'){
        $f11 = 'FACTURA ELECTRONICA';
        $title = "Factura de Servicios ".$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
    } elseif ($cla_doc=='FR'){
        $title = "Factura de Repuestos ".$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
        $f11 = 'FACTURA ELECTRONICA';
    } elseif ($cla_doc=='BS'){
        $title = "Boleta de Servicios ".$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
        $f11 = 'BOLETA ELECTRONICA';
    } elseif ($cla_doc=='BR'){
        $title = "Boleta de Repuestos ".$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
        $f11 = 'BOLETA ELECTRONICA';
    } elseif ($cla_doc == 'AR'){
        $f11 = "NOTA DE CREDITO";
        $title = 'Nota Credito Repuestos '.$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
    } elseif ($cla_doc == 'AS') {
        $f11 = 'NOTA CREDITO';
        $title = 'Nota Credito Servicios '.$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];
    }
    $c1 = $title;

    // C2 fecha
    $c2 =  'Fecha';
    $c3 = $cab['FECEMISION1'];

    // C4 Forma de Pago
    $c4 = "Forma de Pago";
    $c5 = $cab['DOC23'];

    // Cliente
    $c6 = 'Cliente';

    $no_permitidas= array ("&");
    $c7 = str_replace($no_permitidas, '' ,$cab['RZNSOCIALUSUARIO5']);

    //$c7 = preg_replace('([^A-Za-z0-9])', '', $cab['RZNSOCIALUSUARIO5']) ;

    // Nombre
    $c8 = 'Nombre';
    $c9 = 'Surmotriz S.R.L';

    // ruc o dni
    if ($cab['TIPDOCUSUARIO3'] == '6') {
        $documento = 'RUC';
    }elseif($cab['TIPDOCUSUARIO3'] == '1'){
        $documento = 'DNI';
    }else{
        $documento = 'Carnet Extranjeria';
    }
    $c10 = $documento;
    $c11 = $cab['NUMDOCUSUARIO4'];





    // Direccion
    $c14 = 'Direccion';
    $c15 = $cab['DOC24'];


    if ($cla_doc=='AR' || $cla_doc=='AS') {
        // Fecha Documento que modifica
        $sql_fecha_doc_modifica = "select b.cdg_fec_gen from cab_doc_gen a inner join cab_doc_gen b on b.cdg_num_doc=a.CDG_DOC_REF and b.cdg_cla_doc=a.cdg_tip_ref where a.cdg_num_doc=".$num_doc." and a.cdg_cod_gen=".$gem." and a.cdg_cod_emp=".$emp." and a.cdg_cla_doc='".$cla_doc."'";
        $fecha_doc_modifica = oci_parse($conn, $sql_fecha_doc_modifica);
        oci_execute($fecha_doc_modifica);
        while ($filas = oci_fetch_array($fecha_doc_modifica, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $fecha = $filas['CDG_FEC_GEN'];
        }

        $tip_doc_ref = 'Documento Afectado';
        $num_doc_ref = $cab['SERIE']; // documento relacionado nota credito

        $dir_doc_name = 'Fecha que Doc Modf';
        $dir_doc_value = $fecha; // fecha relacionado nota credito

        $motivo = $cab['DESMOTIVO2'];

    } else {
        $motivo = '';
        $tip_doc_ref =  'RUC';
        $num_doc_ref = '20532710066';

        $dir_doc_name = 'Direccion';
        $dir_doc_value = 'Avenida Leguia 1870';
    }

    // ruc surmotriz
    $c12 = $tip_doc_ref;
    $c13 = $num_doc_ref;

    // Direccion local
    $c16 = $dir_doc_name;
    $c17 = $dir_doc_value;

    // detalle de factura
    $c18 = $valor_detalle;


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

    $c35 = $motivo;
    $c36 = 'SUBTOTAL';
    $c37 = $cab['SUBTOTAL14'];

    $c38 = '';
    $c39 = 'I.G.V.';
    $c40 = number_format($cab['MTOIGV13'], 2, '.', '');

    $c41 = '';
    $c42 = 'Total';
    $c43 = number_format($cab['MTOIMPVENTA16'], 2, '.', '');
    $leyenda_100 = convertir_a_letras($c43);
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
    $f4 = $cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];

    $f5 = $cab['NOMBRE20'];

    $f6 = '20532710066-'.$cab['CDG_TIPO'].'-'.$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];

    $f7 = './app/repo/'.date("Y").'/'.date("m").'/'.date("d").'/';
    $f8 = '20532710066-'.$cab['CDG_TIPO'].'-'.$cab['SERIE_DOC'].'-'.$cab['CDG_NUM_DOC'];

    // dni o factura 1 dni, 6 factura, 4 carnet extranjeria
    if(strlen($cab['NUMDOCUSUARIO4']) == 11){
        $f9 = 6;
    } elseif (strlen($cab['NUMDOCUSUARIO4']) == 8){
        $f9 = 1;
    } else {
        $f9 = 4;
    }
    //$f9 = $cab['TIPDOCUSUARIO3'];

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

    // Anticipo Franquicia o Anticipo
    if ($cab['CDG_TIP_DOC'] != 'A'){
        if ($cab['CDG_EXI_FRA'] == 'N'){
            if ($cab['CDG_EXI_ANT'] == 'AN'){
                $anticipo_current = 1; // sale anticipo
            }else {
                $anticipo_current = 0; // no sale anticipo
            }
        } elseif ($cab['CDG_EXI_FRA'] == 'S') {
            $anticipo_current = 1;
        } else {
            $anticipo_current = 0;
        }
    } else {
        $anticipo_current = 0;
    }

    if ($anticipo_current == 1){
        $anticipo_current = 1;
        $anticipo_doc = $cab['CDG_TIP_FRA'][0].'00'.$cab['CDG_SER_FRA'][0].'-'.$cab['CDG_DOC_FRA'];
        $anticipo_tot = number_format($cab['CDG_TOT_FRA'], 2, '.', '');
        $sql_anticipo = oci_parse($conn, "select * from cab_doc_gen where cdg_cla_doc='".$cab['CDG_TIP_FRA']."' and cdg_num_doc='".$cab['CDG_DOC_FRA']."' and cdg_ser_doc='".$cab['CDG_SER_FRA']."'"); oci_execute($sql_anticipo);
        while($res_anticipo = oci_fetch_array($sql_anticipo)){
            $anticipo_documento = $res_anticipo['CDG_DOC_CLI'];
            if (strlen($anticipo_documento) == 8){
                $anticipo_document_type = 1;
            } elseif (strlen($anticipo_documento) == 11){
                $anticipo_document_type = 6;
            } else {
                $anticipo_document_type = 4;
            }
            if (number_format($res_anticipo['CDG_TIP_CAM'], 0, '.', '') == 0){
                $anticipo_moneda = 'PEN';
                $anticipo_moneda_pdf = 'S/';
            }else {
                $anticipo_moneda = 'DOL';
                $anticipo_moneda_pdf = '$';
            }
            if ($cab['CDG_TIP_FRA'][0] == 'F'){
                $anticipo_SchemaID = '02';
            } else {
                $anticipo_SchemaID = '03';
            }
        }
    }

    //UBIGEO
    $ubigeo = '';
    $sql_ubigeo1 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab['CDG_UBI_GEO'][0].$cab['CDG_UBI_GEO'][1]."0000'");
    oci_execute($sql_ubigeo1);
    while($res_ubigeo1 = oci_fetch_array($sql_ubigeo1)){ $ubigeo = ucwords(strtolower(trim($res_ubigeo1['UBI_NOMBRE']))); }


    $sql_ubigeo2 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab['CDG_UBI_GEO'][0].$cab['CDG_UBI_GEO'][1].$cab['CDG_UBI_GEO'][2].$cab['CDG_UBI_GEO'][3]."00'");
    oci_execute($sql_ubigeo2);
    while($res_ubigeo2 = oci_fetch_array($sql_ubigeo2)){ $ubigeo = $ubigeo.'-'.ucwords(strtolower(trim($res_ubigeo2['UBI_NOMBRE']))); }

    $sql_ubigeo3 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab['CDG_UBI_GEO']."'");
    oci_execute($sql_ubigeo3);
    while($res_ubigeo3 = oci_fetch_array($sql_ubigeo3)){ $ubigeo = $ubigeo.'-'.ucwords(strtolower(trim($res_ubigeo3['UBI_NOMBRE']))); }


    // cabezera
    if ($cab['CDG_CLA_DOC']=='FS' || $cab['CDG_CLA_DOC']=='BS' ){
        $cabezera_tipo = 2; // solo cuando tiene 2 se muestran detalles en la cabezera


        $sql_extendido = oci_parse($conn, "
          select * from cab_doc_gen 
          inner join cab_ord_ser on cos_num_ot=cab_doc_gen.cdg_ord_tra and cos_cod_gen=cab_doc_gen.cdg_cod_gen and cos_cod_emp=cab_doc_gen.cdg_cod_emp
          inner join det_ing_ser on dis_pla_veh=cab_ord_ser.cos_pla_veh
          inner join cab_fam_veh on cfv_cod_gen=cab_doc_gen.cdg_cod_gen and cfv_cod_mar=det_ing_ser.dis_mar_veh and cfv_cod_fam=det_ing_ser.dis_cod_fam
          where cdg_num_doc='".$num_doc."' and cdg_cla_doc='".$cla_doc."' and cdg_cod_emp='".$emp."' and cdg_cod_gen='".$gem."' order by cdg_fec_gen Desc");
        oci_execute($sql_extendido);
        while($res_extendido = oci_fetch_array($sql_extendido)){
            $ord_trab = $res_extendido['CDG_ORD_TRA'];
            $placa = $res_extendido['DIS_PLA_VEH'];
            $modelo_anho = $res_extendido['CFV_DES_FAM'].' - '.$res_extendido['DIS_ANO_VEH'];
            $motor_chasis = $res_extendido['DIS_CHA_VEH'];
            $color = $res_extendido['DIS_COL_VEH'];
            $kilometraje = $res_extendido['DIS_KIL_VEH'];
            $extendido[] = $res_extendido;
        }

    //print_r($extendido);

    } elseif ($cab['CDG_CLA_DOC']=='FR' || $cab['CDG_CLA_DOC']=='BR' || $cab['CDG_CLA_DOC']=='FC'){
        $cabezera_tipo = 1; // aqui no se muestra nada solo una lado
    } elseif ($cab['CDG_CLA_DOC']=='AR' || $cab['CDG_CLA_DOC']=='AS'){
        $cabezera_tipo = 3; // aqui se muestra abajo documento relacionado
    }

    // filtra nota credito de repuestos
    if ($cab['CDG_TIP_DOC']=='F'){
        $pase = 'S';
    }elseif($cab['CDG_TIP_DOC'] == 'B'){
        $pase = 'N';
    }elseif($cab['CDG_TIP_DOC'] == 'A'){
        if ($cab['CDG_TIP_REF'] == 'FR' || $cab['CDG_TIP_REF'] == 'FS' || $cab['CDG_TIP_REF'] == 'FC'){
            $pase = 'S';
        }else{
            $pase = 'N';
        }
    }

//echo $cabezera_tipo;
//print_r($cab);
//print_r($dets);
//print_r($det);
//echo $pase;
