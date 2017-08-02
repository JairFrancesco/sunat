<?php
    require 'vendor/autoload.php';
    require ('conexion.php');

    date_default_timezone_set('America/Lima');

    /* PARAMETROS GET
    ***********************************/

    $gen = $_GET['gen'];
    $emp = $_GET['emp'];
    $tip = $_GET['tip'];
    $num = $_GET['num'];

    /* CONSULTA CAB_DOC_GEN
    *****************************************************************************/
    $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_tip_doc='".$tip."' and cdg_num_doc='".$num."'";
    $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];


    /* FECHA 26-07-2017
     ********************/
    $fecha = date("d-m-Y", strtotime($cab_doc_gen['CDG_FEC_GEN']));


    /* DOC Y SERIE 01-F001
    *******************/
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


    /* RUTA   ../../app/repo/26/07/2017/
    ************************************************************/
    $ruta = explode('-',$fecha);
    $ruta = '../app/repo/'.$ruta[2].'/'.$ruta[1].'/'.$ruta[0].'/';
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }


    /* CODIGO DE BARRAS
    **************************************************/
    $textoCodBar = "| ".$cab_doc_gen['CDG_TIP_DOC']." | A | 123 | TotIGV | TotMonto | ".date("d-m-Y", strtotime($cab_doc_gen['CDG_FEC_GEN']))." | TipoDoc | F002-026 | VALOR RESUMEN | CodHash |";
    use BigFish\PDF417\PDF417;
    use BigFish\PDF417\Renderers\ImageRenderer;
    $pdf417 = new PDF417();
    $codigo_barra = $pdf417->encode($textoCodBar);
    $renderer = new ImageRenderer(['format' => 'png']);
    $image = $renderer->render($codigo_barra);
    $image->save($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.png');


    /*  RUC O DNI
     *******************/
    if(strlen(trim($cab_doc_gen['CDG_DOC_CLI']))==11){
        $tipo_doc = 'RUC';
    }elseif(strlen(trim($cab_doc_gen['CDG_DOC_CLI']))==8){
        $tipo_doc = 'DNI';
    }else{
        $tipo_doc = 'Carnet Extranj';
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
        if($cab_doc_gen['CDG_CO_CR'] != 'AN'){
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
    $i=0;
    if($cab_doc_gen['CDG_TIP_IMP'] != 'R'){
        $sql_repuestos = "select * from det_doc_rep inner join LIS_PRE_REP on lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro where DDR_COD_GEN='".$cab_doc_gen['CDG_COD_GEN']."' and DDR_COD_EMP='".$cab_doc_gen['CDG_COD_EMP']."' and DDR_NUM_DOC='".$cab_doc_gen['CDG_NUM_DOC']."' and DDR_CLA_DOC='".$cab_doc_gen['CDG_CLA_DOC']."' ORDER BY rownum Desc";
        $sql_repuestos_parse = oci_parse($conn,$sql_repuestos);
        oci_execute($sql_repuestos_parse);
        oci_fetch_all($sql_repuestos_parse, $repuestos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($repuestos as $repuesto){
            $items[$i][0] = $repuesto['DDR_COD_PRO']; // codigo
            $items[$i][1] = $repuesto['LPR_DES_PRO']; // descripcion
            $items[$i][2] = $repuesto['DDR_CAN_PRO']; // cantidad
            $items[$i][3] = $repuesto['DDR_VVP_SOL']; // precio unitario
            $items[$i][4] = ($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']); // importe
            $i++;
        }
    }

    if($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
        $sql_servicios = "select * from det_doc_ser where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
        $sql_servicios_parse = oci_parse($conn, $sql_servicios);
        oci_execute($sql_servicios_parse);
        oci_fetch_all($sql_servicios_parse, $servicios, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($servicios as $servicio) {
            $items[$i][0] = $servicio['DDS_COD_PRO'];
            $items[$i][1] = $servicio['DDS_DES_001'];
            $items[$i][2] = $servicio['DDS_CAN_PRO'];
            $items[$i][3] = $servicio['DDS_VVP_SOL'];
            $items[$i][4] = ($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']);
            $i++;
        }
    }

    if($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
        $sql_otros = "select * from det_doc_otr where DDO_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDO_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDO_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDO_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
        $sql_otros_parse = oci_parse($conn, $sql_otros);
        oci_execute($sql_otros_parse);
        oci_fetch_all($sql_otros_parse, $otros, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($otros as $otro) {  // DDO_DES_OTR
            $items[$i][0] = '';
            $items[$i][1] = $otro['DDO_DES_OTR'];
            $items[$i][2] = '';
            $items[$i][3] = '';
            $items[$i][4] = '';
            $i++;
        }
    }

    if($cab_doc_gen['CDG_TIP_IMP'] == 'R') {
        if ($cab_doc_gen['CDG_TEN_RES'] != '') {
            $items[$i][0] = '';
            $items[$i][1] = $cab_doc_gen['CDG_TEN_RES'];
            $items[$i][2] = '';
            $items[$i][3] = '';
            $items[$i][4] = '';
        }
    }


    print_r($items);

    ob_start();

?>
    <table style="width: 100%;  margin-bottom: 20px;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 15%; border-left: solid 1px #000; border-top: solid 1px #000; border-bottom: solid 1px #000;">
                <img src="images/logo.jpg" style="height: 100px;">
            </td>
            <td style="width: 41%; border-top: solid 1px #000; border-right: solid 1px #000; border-bottom: solid 1px #000; font-size: 9px; line-height: 13px;">
                TACNA: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015
                cel.:952869639 (repuestos) cel.: 992566630 (servicios)
                email: tacna@surmotriz.com y repuestos@surmotriz.com
                MOQUEGUA: Sector Yaracachi Mz.D Lte.09 Mariscal Nieto/Moquegua
                Telef:(53) 479365 Cel: #953922105 email: moquegua@surmotriz.com
                Venta de vehiculos-repuestos y accesorios legitimos Toyota
                Reparacion y mantenimiento de automoviles y camionetas.
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 40%; border: solid 1px #000;">
                <div style="text-align: center; color: red; font-size: 18px; line-height: 25px;">RUC: 20532710066</div>
                <div style="text-align: center; font-weight: bold; font-size: 18px; line-height: 25px; ">
                    <?php echo $doc_nombre; ?>
                </div>
                <div style="text-align: center; color: blue; font-size: 19px; line-height: 25px;">
                    <?php echo $serie.'-'.$cab_doc_gen['CDG_NUM_DOC']; ?>
                </div>
            </td>
        </tr>
    </table>


    <table style="width: 100%; font-size: 12px; border: solid 1px #000; margin-bottom: 20px; padding: 5px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 16%;"><strong>Fecha</strong></td>
            <td style="width: 44%;">: <?php echo $fecha; ?></td>
            <?php
                if ($cabezera_tipo==1){
                    echo '<td style="width: 15%;"><strong>Ord. Trab</strong></td>';
                    echo '<td style="width: 25%;">: '.$ord_trab.'</td>';
                }else {
                    echo '<td style="width: 15%;"></td>';
                    echo '<td style="width: 25%;"></td>';
                }
            ?>
        </tr>
        <tr>
            <td><strong>Cliente</strong></td>
            <td>: <?php echo substr($cab_doc_gen['CDG_NOM_CLI'],0,40); ?></td>
            <?php
                if ($cabezera_tipo==1){
                    echo '<td><strong>Placa/Serie</strong></td>';
                    echo '<td>: '.$placa.'</td>';
                }else{
                    echo '<td></td>';
                    echo '<td></td>';
                }
            ?>
        </tr>
        <tr>
            <td><strong><?php echo $tipo_doc; ?></strong></td>
            <td>: <?php echo $cab_doc_gen['CDG_DOC_CLI']; ?></td>
            <?php
                if($cabezera_tipo==1){
                    echo '<td><strong>Modelo/Año</strong></td>';
                    echo '<td>: '.$modelo_anho.'</td>';
                }else{
                    echo '<td></td>';
                    echo '<td></td>';
                }
            ?>
        </tr>
        <tr>
            <td><strong>Dirección</strong></td>
            <td>: <?php echo substr($cab_doc_gen['CDG_DIR_CLI'],0,40); ?></td>
            <?php
                if($cabezera_tipo==1){
                    echo '<td><strong>Motor/Chasis</strong></td>';
                    echo '<td>: '.$motor_chasis.'</td>';
                }else{
                    echo '<td></td>';
                    echo '<td></td>';
                }
            ?>
        </tr>
        <tr>
            <td><strong>Forma de Pago</strong></td>
            <td>: <?php echo $forma_pago; ?></td>
            <?php
                if($cabezera_tipo==1){
                    echo '<td><strong>Color</strong></td>';
                    echo '<td>: '.$color.'</td>';
                }else{
                    echo '<td></td>';
                    echo '<td></td>';
                }
            ?>
        </tr>
        <tr>
            <td><strong>Ubigeo</strong></td>
            <td>: <?php echo $ubigeo; ?></td>
            <?php
                if($cabezera_tipo==1){
                    echo '<td><strong>Km</strong></td>';
                    echo '<td>: '.$kilometraje.'</td>';
                }else{
                    echo '<td></td>';
                    echo '<td></td>';
                }
            ?>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border-bottom: solid 1px #000; border-left: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">
                Nro
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">Codigo
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">
                Descripcion
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">
                Cant
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">
                P. Unit
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">
                Importe
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">
                Desct
            </td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; border-right: solid 1px #000; text-align: right;">
                Valor Venta
            </td>
        </tr>
        <?php
        for ($i = 1; $i <= 3; $i++) {
            echo '<tr>';
            echo '<td style="width: 5%; border-left: solid 1px #000;">' . $i . '</td>';
            echo '<td style="width: 13%;">9046710183</td>';
            echo '<td style="width: 37%;">JGO PASTILLA FRENO 1 JGO PASTILLA</td>';
            echo '<td style="width: 5%; text-align: right;">5</td>';
            echo '<td style="width: 10%; text-align: right;">5.00</td>';
            echo '<td style="width: 10%; text-align: right;">5.00</td>';
            echo '<td style="width: 10%; text-align: right;">5.00</td>';
            echo '<td style="width: 10%; border-right: solid 1px #000; text-align: right;">5.00</td>';
            echo '</tr>';
        }
        ?>
        <tr>
            <td colspan="4" rowspan="8"
                style="width: 60%; border-top: solid 1px #000; border-right: solid 1px #000; line-height: 14px;">
                Nota: Esta nota sale en todos y esto es de dos lineas seguidas. Esta nota sale en todos y esto es de dos
                lineas seguidas.<br>
                <span style="font-style: italic;">Operación sujeta al Sistema de pago de Oblig. trib. con el Gob. Central, R.S. 343-2014-SUNAT, Tasa 10%., Cta. Cte Bco.</span><br>
                <span style="font-style: italic;">Son : Ochenta y ocho con 97/100 soles.</span><br>
                <img src="images/20532710066-07-FN03-2917.png" style="height: 55px; width: 300px; text-align: center;">
            </td>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Sub Total S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Total Descuentos S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Operaciones Gravadas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Operaciones Inafectas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Operaciones Exoneradas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">Operaciones Gratuitas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right;">I.G.V. 18% S/ :</td>
            <td style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000;">200.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; border-bottom: solid 1px #000; text-align: right;">
                <strong>IMPORTE TOTAL S/ :</strong></td>
            <td style="border-top: solid 1px #000; border-bottom: solid 1px #000; border-right: solid 1px #000; text-align: right;">
                <strong>200.00</strong></td>
        </tr>
    </table>
    <hr style="border: none; height: 1px; background-color: #414141; margin-top: 30px;">
    <span style="text-align: center; font-size: 11px;">Representación Impresa de la Factura Electrónica. SURMOTRIZ S.R.L. Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 112-005-0000143/SUNAT Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php</span>

<?php

    $content = ob_get_clean();
    use Spipu\Html2Pdf\Html2Pdf;
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(8, 8, 8, 8));
    $html2pdf->writeHTML($content);
    //$html2pdf->output('factura.pdf');

?>