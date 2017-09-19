<?php 
    // CONEXION ORACLE
    // ***************
    require ('conexion.php');
    
    /* PARAMETROS GET
    ***********************************/
    $gen = $_GET['gen'];
    $emp = $_GET['emp'];
    $tip = $_GET['tip']; // F
    $num = $_GET['num'];

    /* CONSULTA CAB_DOC_GEN
    *****************************************************************************/
    $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_tip_doc='".$tip."' and cdg_num_doc='".$num."'";
    $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];


    /*  MONEDA
    *********************************************/
    if($cab_doc_gen['CDG_TIP_CAM'] != 0){
        $moneda = 'USD';
        $moneda_nombre = '$$';
        $moneda_leyenda = 'dolares';
    }else{
        $moneda = 'PEN';
        $moneda_nombre = 'S/';
        $moneda_leyenda = 'soles';
    }

    /* FECHA 26-07-2017
        ********************/
    $fecha = date("d-m-Y", strtotime($cab_doc_gen['CDG_FEC_GEN']));

    /* DOC Y SERIE 01-F001
    **********************/
    if($cab_doc_gen['CDG_TIP_DOC'] == 'F'){
        $doc = '01';
        $doc_nombre = 'FACTURA ELECTRÓNICA';
        $serie = 'F00'.$cab_doc_gen['CDG_SER_DOC'];
    }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'B'){
        $doc = '03';
        $serie = 'B00'.$cab_doc_gen['CDG_SER_DOC'];
        $doc_nombre = 'BOLETA ELECTRÓNICA';
    }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'A'){
        $doc = '07';
        $doc_nombre = 'NOTA CREDITO ELECTRÓNICA';
        if($cab_doc_gen['CDG_TIP_REF'] == 'BR' || $cab_doc_gen['CDG_TIP_REF'] == 'BS'){
            $serie = 'BN0'.$cab_doc_gen['CDG_SER_DOC'];
        }elseif($cab_doc_gen['CDG_TIP_REF'] == 'FR' || $cab_doc_gen['CDG_TIP_REF'] == 'FS' || $cab_doc_gen['CDG_TIP_REF'] == 'FC'){
            $serie = 'FN0'.$cab_doc_gen['CDG_SER_DOC'];
        }
    }

    /* RUTA   ../app/repo/2017/08/08/
    ************************************************************/
    $ruta = explode('-', $fecha);
    $ruta = '../app/repo/'.$ruta[2].'/'.$ruta[1].'/'.$ruta[0].'/';
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    

    /*  RUC O DNI
        *******************/
    if (strlen(trim($cab_doc_gen['CDG_DOC_CLI'])) == 11) {
        $tipo_doc = 'RUC';
        $tipo_doc_num = 6;
    } elseif (strlen(trim($cab_doc_gen['CDG_DOC_CLI'])) == 8) {
        $tipo_doc = 'DNI';
        $tipo_doc_num = 1;
    } else {
        $tipo_doc = 'Carnet Extranj';
        $tipo_doc_num = 4;
    }

    /* FORMA DE PAGO
    *********************/
    if($cab_doc_gen['CDG_CO_CR']=='CR'){
        $forma_pago = 'CREDITO';
    }else{
        $forma_pago = 'CONTADO';
    }

    /* UBIGEO
    ******************************/
    $ubigeo = '';
    $sql_ubigeo1 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab_doc_gen['CDG_UBI_GEO'][0].$cab_doc_gen['CDG_UBI_GEO'][1]."0000'");
    oci_execute($sql_ubigeo1);
    while($res_ubigeo1 = oci_fetch_array($sql_ubigeo1)){ $ubigeo = ucwords(strtolower(trim($res_ubigeo1['UBI_NOMBRE']))); }
    $sql_ubigeo2 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab_doc_gen['CDG_UBI_GEO'][0].$cab_doc_gen['CDG_UBI_GEO'][1].$cab_doc_gen['CDG_UBI_GEO'][2].$cab_doc_gen['CDG_UBI_GEO'][3]."00'");
    oci_execute($sql_ubigeo2);
    while($res_ubigeo2 = oci_fetch_array($sql_ubigeo2)){ $ubigeo = $ubigeo.'-'.ucwords(strtolower(trim($res_ubigeo2['UBI_NOMBRE']))); }
    $sql_ubigeo3 = oci_parse($conn, "select ubi_nombre from ubigeo where ubi_id='".$cab_doc_gen['CDG_UBI_GEO']."'");
    oci_execute($sql_ubigeo3);
    while($res_ubigeo3 = oci_fetch_array($sql_ubigeo3)){ $ubigeo = $ubigeo.'-'.ucwords(strtolower(trim($res_ubigeo3['UBI_NOMBRE']))); }


    /* SEGUNDA FILA cabezera_tipo 0 (no sale nada), 1 si sale
    ***********************************************************/
    if ($cab_doc_gen['CDG_CLA_DOC']=='FS' || $cab_doc_gen['CDG_CLA_DOC']=='BS'  ){
        if($cab_doc_gen['CDG_CO_CR'] != 'AN' && $cab_doc_gen['CDG_CO_CC'] != 'GR'){
            $cabezera_tipo = 1;
            $sql_extendido = "select * from cab_ord_ser 
                    inner join det_ing_ser on dis_pla_veh=cab_ord_ser.cos_pla_veh and dis_cod_gen=cab_ord_ser.cos_cod_gen
                    inner join cab_fam_veh on cfv_cod_gen=cab_ord_ser.cos_cod_gen and cfv_cod_mar=det_ing_ser.dis_mar_veh and cfv_cod_fam=det_ing_ser.dis_cod_fam
                    where cos_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."' and cos_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cos_num_ot='".$cab_doc_gen['CDG_ORD_TRA']."'";
            $sql_parse_extendido = oci_parse($conn,$sql_extendido);
            oci_execute($sql_parse_extendido);
            oci_fetch_all($sql_parse_extendido, $res_extendido, null, null, OCI_FETCHSTATEMENT_BY_ROW); $res_extendido = $res_extendido[0];

            $ord_trab = $cab_doc_gen['CDG_ORD_TRA'];
            $placa = $res_extendido['DIS_PLA_VEH'];
            $modelo_anho = $res_extendido['CFV_DES_FAM'].' - '.$res_extendido['DIS_ANO_VEH'];
            $motor_chasis = $res_extendido['DIS_CHA_VEH'];
            $color = $res_extendido['DIS_COL_VEH'];
            $kilometraje = $res_extendido['COS_KIL_VEH'];

        }else{
            $cabezera_tipo = 0;
        }
    }else{
        $cabezera_tipo = 0;
    }

    /* ITEMS
    ***********************************/
    $i = 0;
    if ($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
        if($cab_doc_gen['CDG_TIP_DOC']=='A'){
            $sql_repuestos = "select * from det_doc_rep inner join LIS_PRE_REP on lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro where DDR_COD_GEN='".$cab_doc_gen['CDG_COD_GEN']."' and DDR_COD_EMP='".$cab_doc_gen['CDG_COD_EMP']."' and DDR_NUM_DOC='".$cab_doc_gen['CDG_DOC_REF']."' and DDR_CLA_DOC='".$cab_doc_gen['CDG_TIP_REF']."' ORDER BY rownum Desc";
        }else{
            $sql_repuestos = "select * from det_doc_rep inner join LIS_PRE_REP on lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro where DDR_COD_GEN='".$cab_doc_gen['CDG_COD_GEN']."' and DDR_COD_EMP='".$cab_doc_gen['CDG_COD_EMP']."' and DDR_NUM_DOC='".$cab_doc_gen['CDG_NUM_DOC']."' and DDR_CLA_DOC='".$cab_doc_gen['CDG_CLA_DOC']."' ORDER BY rownum Desc";
        }
        $sql_repuestos_parse = oci_parse($conn, $sql_repuestos);
        oci_execute($sql_repuestos_parse);
        oci_fetch_all($sql_repuestos_parse, $repuestos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($repuestos as $repuesto) {
            $items[$i]['codigo'] = $repuesto['DDR_COD_PRO']; // codigo
            $items[$i]['descripcion'] = $repuesto['LPR_DES_PRO']; // descripcion
            $items[$i]['cantidad'] = $repuesto['DDR_CAN_PRO']; // cantidad
            $items[$i]['unitario'] = number_format($repuesto['DDR_VVP_SOL'], 2, '.', ''); // precio unitario
            $items[$i]['importe'] = number_format(($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']), 2, '.', ''); // importe
            $items[$i]['descuento'] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES']) / 100), 2, '.', ''); // descuento esta en % hay que sacarle del importe
            $items[$i]['venta'] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']) - (($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES']) / 100)), 2, '.', ''); // valor venta (importe - descuento)
            $i++;
        }
    }

    if ($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
        if($cab_doc_gen['CDG_TIP_DOC']=='A'){
            $sql_servicios = "select * from det_doc_ser where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_DOC_REF'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_TIP_REF'] . "' ORDER BY rowid Desc";
        }else{
            if($cab_doc_gen['CDG_CO_CC'] == 'GR'){
                $sql_servicios = "select * from det_doc_ser inner join cab_ord_ser on cos_num_ot=dds_num_ot and cos_cod_emp=dds_cod_emp and cos_cod_gen=dds_cod_gen where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ";
            }else{
                $sql_servicios = "select * from det_doc_ser where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
            }
        }
        
        $sql_servicios_parse = oci_parse($conn, $sql_servicios);
        oci_execute($sql_servicios_parse);
        oci_fetch_all($sql_servicios_parse, $servicios, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($servicios as $servicio) {
            if($cab_doc_gen['CDG_CO_CC'] == 'GR'){
                $items[$i]['codigo'] = $servicio['COS_COD_SAP']; // codigo
            }else{
                $items[$i]['codigo'] = $servicio['DDS_COD_PRO']; // codigo
            }            
            $items[$i]['descripcion'] = $servicio['DDS_DES_001']; // descripcion
            $items[$i]['cantidad'] = $servicio['DDS_CAN_PRO']; // cantidad
            $items[$i]['unitario'] = number_format($servicio['DDS_VVP_SOL'], 2, '.', ''); // precio unitario
            $items[$i]['importe'] = number_format(($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']), 2, '.', ''); // importe
            $items[$i]['descuento'] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES']) / 100), 2, '.', ''); // descuento
            $items[$i]['venta'] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']) - (($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES']) / 100)), 2, '.', ''); // valor venta (importe - descuento)
            $i++;
        }
    }

    // otros contabilidad
    if ($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
        $sql_otros = "select * from det_doc_otr where DDO_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDO_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDO_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDO_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
        $sql_otros_parse = oci_parse($conn, $sql_otros);
        oci_execute($sql_otros_parse);
        oci_fetch_all($sql_otros_parse, $otros, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($otros as $otro) {  // DDO_DES_OTR
            $items[$i]['codigo'] = '-- -- --';
            $items[$i]['descripcion'] = $otro['DDO_DES_OTR'];
            $items[$i]['cantidad'] = '1';
            if($moneda == 'PEN'){
                $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',''); // subtotal
                $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',''); // subtotal
                $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',''); // descuento
                $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.','');  // gravadas
            }else{
                $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_DOL'],2,'.',''); // subtotal
                $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_DOL'],2,'.',''); // subtotal
                $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_DOL'],2,'.',''); // descuento
                $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_DOL']-$cab_doc_gen['CDG_DES_DOL']),2,'.','');  // gravadas
            }
            $i++;
        }
    }

    /*Resumen*/
    if ($cab_doc_gen['CDG_TIP_IMP'] == 'R') { // solo si es resumen se imprime cdg_ten_res, nunca va ver un R que sea AN
        if ($cab_doc_gen['CDG_TEN_RES'] != '') {
            $items[$i]['codigo'] = '-- -- --';
            $items[$i]['descripcion'] = $cab_doc_gen['CDG_TEN_RES'];
            $items[$i]['cantidad'] = '1';
            if ($cab_doc_gen['CDG_EXI_FRA'] == 'S') {
                $items[$i]['unitario'] = number_format((($cab_doc_gen['CDG_VVP_TOT']) - ($cab_doc_gen['CDG_TOT_FRA'] / (1 + $cab_doc_gen['CDG_POR_IGV'] / 100))), 2, '.', '');
                $items[$i]['importe'] = number_format((($cab_doc_gen['CDG_VVP_TOT']) - ($cab_doc_gen['CDG_TOT_FRA'] / (1 + $cab_doc_gen['CDG_POR_IGV'] / 100))), 2, '.', '');
                $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'], 2, '.','');
                $items[$i]['venta'] = number_format((($cab_doc_gen['CDG_VVP_TOT']) - ($cab_doc_gen['CDG_TOT_FRA'] / (1 + $cab_doc_gen['CDG_POR_IGV'] / 100)) - $cab_doc_gen['CDG_DES_TOT']), 2, '.', '');
            } else {
                $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', '');
                $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', '');
                $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'], 2, '.', ''); //descuentos
                $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_TOT'] - $cab_doc_gen['CDG_DES_TOT']), 2, '.', '');  // gravadas cdg_vvp_tot-cdg_des_tot;
            }
        }
    }

    // anticipo pero factura
    if ($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] != 'A') { // solo si es anticipo se imprime la nota en arriba anticipo es contado
        $items[$i]['codigo'] = '-- -- --';
        $items[$i]['descripcion'] = $cab_doc_gen['CDG_NOT_001'] . ' ' . $cab_doc_gen['CDG_NOT_002'] . ' ' . $cab_doc_gen['CDG_NOT_003'];
        $items[$i]['cantidad'] = '1';
        $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', ''); // precio unitario
        $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', ''); //importe
        $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'], 2, '.', ''); //descuentos
        $items[$i]['venta'] = number_format(($cab_doc_gen['CDG_VVP_TOT'] - $cab_doc_gen['CDG_DES_TOT']), 2, '.', '');  // gravadas cdg_vvp_tot-cdg_des_tot
    }

    // anticipo pero nota de credito
    if ($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] == 'A') {
        $sql_nota = "select cdg_not_001,cdg_not_002,cdg_not_003 from cab_doc_gen where cdg_cod_gen ='" . $cab_doc_gen['CDG_COD_GEN'] . "' and cdg_cod_emp='" . $cab_doc_gen['CDG_COD_EMP'] . "' and cdg_cla_doc='" . $cab_doc_gen['CDG_TIP_REF'] . "' and cdg_num_doc='" . $cab_doc_gen['CDG_DOC_REF'] . "'";
        $sql_nota_parse = oci_parse($conn, $sql_nota);
        oci_execute($sql_nota_parse);
        oci_fetch_all($sql_nota_parse, $nota, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $items[$i]['codigo'] = '-- -- --';
        $items[$i]['descripcion'] = $nota[0]['CDG_NOT_001'] . ' ' . $nota[0]['CDG_NOT_002'] . ' ' . $nota[0]['CDG_NOT_003'];
        $items[$i]['cantidad'] = '1';
        $items[$i]['unitario'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', '');;
        $items[$i]['importe'] = number_format($cab_doc_gen['CDG_VVP_TOT'], 2, '.', '');;
        $items[$i]['descuento'] = number_format($cab_doc_gen['CDG_DES_TOT'], 2, '.', '');;
        $items[$i]['venta'] = number_format($cab_doc_gen['CDG_IMP_NETO'], 2, '.', ''); // total cdg_imp_neto
        //print_r($nota);
    }

    //print_r($items);

    /* TOTALES
    ***********************************************/
    if ($cab_doc_gen['CDG_EXI_FRA'] == 'S') {
        $subtotal = number_format(round((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2),2,'.','');
        $descuentos = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.','');
        $gravadas = number_format((round((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2) - $cab_doc_gen['CDG_DES_TOT']),2,'.','');
        $igv = number_format(round(($cab_doc_gen['CDG_IGV_TOT'] -($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))*($cab_doc_gen['CDG_POR_IGV']/100)),2),2,'.','');        
    } else {
        if($moneda == 'PEN'){
            $subtotal = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.','');
            $descuentos = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.','');
            $gravadas = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.','');  // gravadas cdg_vvp_tot-cdg_des_tot
            $igv = number_format($cab_doc_gen['CDG_IGV_TOT'],2,'.',''); // igv total
        }else{ // dolares
            $subtotal = number_format($cab_doc_gen['CDG_VVP_DOL'],2,'.','');
            $descuentos = number_format($cab_doc_gen['CDG_DES_DOL'],2,'.','');
            $gravadas = number_format(($cab_doc_gen['CDG_VVP_DOL']-$cab_doc_gen['CDG_DES_DOL']),2,'.','');  // gravadas cdg_vvp_tot-cdg_des_tot
            $igv = number_format($cab_doc_gen['CDG_IGV_DOL'],2,'.',''); // igv total
        }
    }
    $total = number_format($cab_doc_gen['CDG_IMP_NETO'], 2, '.', ''); // total cdg_imp_neto

    /*LETRAS DEL TOTAL
    *******************************/
    include ("convertir_a_letras.php");
    if($cab_doc_gen['CDG_IMP_NETO']==0 || $cab_doc_gen['CDG_IMP_NETO']=='0.00' || $cab_doc_gen['CDG_IMP_NETO']=='0'){
        $letras = 'cero con 0/00 '.$moneda_leyenda;
    }else{
        $letras = convertir_a_letras(number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.','')).' '.$moneda_leyenda.'.';   
    }


    /*REFERENCIA 0:sin  1:nota  2:franquisia 3:anticipo
    *****************************************************/
    if ($cab_doc_gen['CDG_TIP_DOC'] == 'A') {
        $reference = 1;
        $sql_ref = "select * from cab_doc_gen where cdg_cod_gen='" . $cab_doc_gen['CDG_COD_GEN'] . "' and cdg_cod_emp='" . $cab_doc_gen['CDG_COD_EMP'] . "' and cdg_cla_doc='" . $cab_doc_gen['CDG_TIP_REF'] . "' and cdg_num_doc='" . $cab_doc_gen['CDG_DOC_REF'] . "'";
        $sql_ref_parse = oci_parse($conn, $sql_ref);
        oci_execute($sql_ref_parse);
        oci_fetch_all($sql_ref_parse, $ref, null, null, OCI_FETCHSTATEMENT_BY_ROW);        
        $ref_fecha = date("d-m-Y", strtotime($ref[0]['CDG_FEC_GEN']));
        $fecha_actual = strtotime(date("d-m-Y", strtotime($ref_fecha)));
        $fecha_fija = strtotime('13-07-2017');
        if($ref[0]['CDG_TIP_DOC'] != 'B') {
            if($fecha_actual >= $fecha_fija){
                // referencia electronico
                $ref_doc = $ref[0]['CDG_TIP_DOC'][0].'00'.$ref[0]['CDG_SER_DOC'].'-'.$ref[0]['CDG_NUM_DOC'];
            }else{
                // referencia fisica
                $ref_doc = '000'.$ref[0]['CDG_SER_DOC'].'-'.$ref[0]['CDG_NUM_DOC'];
            }
        }

        if($ref[0]['CDG_TIP_DOC'] == 'F'){
            $doc_ref_tipo = '01';
        }elseif($ref[0]['CDG_TIP_DOC'] == 'B'){
            $doc_ref_tipo = '03';
        }elseif($ref[0]['CDG_TIP_DOC'] == 'A'){
            $doc_ref_tipo = '07';
        }
        
    } elseif ($cab_doc_gen['CDG_EXI_FRA'] == 'S' && $cab_doc_gen['CDG_TIP_DOC'] != 'A' && $cab_doc_gen['CDG_EXI_ANT']!='AN') {
        $reference = 2; // franquicia
    } elseif ($cab_doc_gen['CDG_EXI_ANT'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] != 'A' && $cab_doc_gen['CDG_EXI_ANT']=='AN') {
        $reference = 3; // anticipo
    } else {
        $reference = 0;
    }

    /*Franquicias    
    ******************/
    if($reference == 2){
        $sql_fra = "select * from cab_doc_gen where cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_FRA']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_FRA']."'";
        $sql_fra_parse = oci_parse($conn, $sql_fra);
        oci_execute($sql_fra_parse);
        oci_fetch_all($sql_fra_parse, $fra, null, null, OCI_FETCHSTATEMENT_BY_ROW); $fra = $fra[0];
    }

    /*Anticipos
    *******************/
    if($reference == 3){
        $sql_fra = "select * from cab_doc_gen where cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_FRA']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_FRA']."'";
        $sql_fra_parse = oci_parse($conn, $sql_fra);
        oci_execute($sql_fra_parse);
        oci_fetch_all($sql_fra_parse, $anticipo, null, null, OCI_FETCHSTATEMENT_BY_ROW); $anticipo = $anticipo[0];

        /*Variables anticipo tag
        ********************************/
        //tipo documento - catalogo 12
        if($anticipo['CDG_TIP_DOC'] == 'F'){
            $anticipo_tipo_doc = '03';
        }elseif ($anticipo['CDG_TIP_DOC'] == 'B') {
            $anticipo_tipo_doc = '02';
        }       
        $anticipo_serie_numero_doc = $anticipo['CDG_TIP_DOC'].'00'.$anticipo['CDG_SER_DOC'].'-'.$anticipo['CDG_NUM_DOC'];

        $anticipo_documento = $anticipo['CDG_DOC_CLI'];
        /*  RUC O DNI catalogo 6
        *******************/
        if (strlen(trim($anticipo['CDG_DOC_CLI'])) == 11) {            
            $anticipo_tipo_documento = 6; // ruc
        } elseif (strlen(trim($anticipo['CDG_DOC_CLI'])) == 8) {            
            $anticipo_tipo_documento = 1; // dni
        } else {            
            $anticipo_tipo_documento = 4; //extranj
        }
        

        /*  MONEDA
        *********************************************/
        if($anticipo['CDG_TIP_CAM'] != 0){
            $anticipo_moneda = 'USD';
            $anticipo_moneda_nombre = '$$ ';
            $anticipo_moneda_leyenda = 'dolares';
        }else{
            $anticipo_moneda = 'PEN';
            $anticipo_moneda_nombre = 'S/ ';
            $anticipo_moneda_leyenda = 'soles';
        }
        
        $anticipo_total = number_format($anticipo['CDG_IMP_NETO'], 2, '.', '');;

        //echo 'Ref. '.$fra['CDG_TIP_DOC'].'00'.$fra['CDG_SER_DOC'].'-'.$fra['CDG_NUM_DOC'].' Fecha Ref. '.date("d-m-Y", strtotime($fra['CDG_FEC_GEN'])).'<br>';
        //echo $anticipo_moneda_nombre;    
    }
    
    
?>