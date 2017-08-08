<?php

require 'vendor/autoload.php';
require ('conexion.php');
use Spipu\Html2Pdf\Html2Pdf;
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
date_default_timezone_set('America/Lima');
if(isset($_GET['fisico'])) {
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
    $doc_nombre = 'FACTURA ';
    $serie = 'F00'.$cab_doc_gen['CDG_SER_DOC'];
}elseif($cab_doc_gen['CDG_TIP_DOC'] == 'B'){
    $doc = '03';
    $serie = 'B00'.$cab_doc_gen['CDG_SER_DOC'];
    $doc_nombre = 'BOLETA ';
}elseif($cab_doc_gen['CDG_TIP_DOC'] == 'A'){
    $doc = '07';
    $doc_nombre = 'NOTA CREDITO ';
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
        $items[$i][3] = number_format($repuesto['DDR_VVP_SOL'],2,'.',','); // precio unitario
        $items[$i][4] = number_format(($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']), 2, '.',','); // importe
        $items[$i][5] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES'])/100), 2, '.', ','); // descuento esta en % hay que sacarle del importe
        $items[$i][6] = number_format((($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL']) - (($repuesto['DDR_CAN_PRO'] * $repuesto['DDR_VVP_SOL'] * $repuesto['DDR_POR_DES'])/100)),2,'.',','); // valor venta (importe - descuento)
        $i++;
    }
}

if($cab_doc_gen['CDG_TIP_IMP'] != 'R') {
    $sql_servicios = "select * from det_doc_ser where DDS_COD_GEN='" . $cab_doc_gen['CDG_COD_GEN'] . "' and DDS_COD_EMP='" . $cab_doc_gen['CDG_COD_EMP'] . "' and DDS_NUM_DOC='" . $cab_doc_gen['CDG_NUM_DOC'] . "' and DDS_CLA_DOC='" . $cab_doc_gen['CDG_CLA_DOC'] . "' ORDER BY rowid Desc";
    $sql_servicios_parse = oci_parse($conn, $sql_servicios);
    oci_execute($sql_servicios_parse);
    oci_fetch_all($sql_servicios_parse, $servicios, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    foreach ($servicios as $servicio) {
        $items[$i][0] = $servicio['DDS_COD_PRO']; // codigo
        $items[$i][1] = $servicio['DDS_DES_001']; // descripcion
        $items[$i][2] = $servicio['DDS_CAN_PRO']; // cantidad
        $items[$i][3] = number_format($servicio['DDS_VVP_SOL'],2,'.',','); // precio unitario
        $items[$i][4] = number_format(($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']), 2, '.', ','); // importe
        $items[$i][5] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES'])/100), 2, '.', ','); // descuento
        $items[$i][6] = number_format((($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL']) - (($servicio['DDS_CAN_PRO'] * $servicio['DDS_VVP_SOL'] * $servicio['DDS_POR_DES'])/100)),2,'.',','); // valor venta (importe - descuento)
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
        $items[$i][5] = '';
        $items[$i][6] = '';
        $i++;
    }
}

if($cab_doc_gen['CDG_TIP_IMP'] == 'R') { // solo si es resumen se imprime cdg_ten_res, nunca va ver un R que sea AN
    if ($cab_doc_gen['CDG_TEN_RES'] != '') {
        $items[$i][0] = '-- -- --';
        $items[$i][1] = $cab_doc_gen['CDG_TEN_RES'];
        $items[$i][2] = '1';
        if($cab_doc_gen['CDG_EXI_FRA'] == 'S'){
            $items[$i][3] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.',',');
            $items[$i][4] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.',',');
            $items[$i][5] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',',');
            $items[$i][6] = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))-$cab_doc_gen['CDG_DES_TOT']),2,'.',',');
        }else{
            $items[$i][3] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',',');
            $items[$i][4] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',',');
            $items[$i][5] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',','); //descuentos
            $items[$i][6] = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.',',');  // gravadas cdg_vvp_tot-cdg_des_tot;
        }
    }
}

// anticipo pero factura
if($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] != 'A') { // solo si es anticipo se imprime la nota en arriba anticipo es contado
    $items[$i][0] = '-- -- --';
    $items[$i][1] = $cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003'];
    $items[$i][2] = '1';
    $items[$i][3] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',','); // precio unitario
    $items[$i][4] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',','); //importe
    $items[$i][5] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',','); //descuentos
    $items[$i][6] = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.',',');  // gravadas cdg_vvp_tot-cdg_des_tot
}

// anticipo pero nota de credito
if($cab_doc_gen['CDG_CO_CR'] == 'AN' && $cab_doc_gen['CDG_TIP_DOC'] == 'A') {
    $sql_nota = "select cdg_not_001,cdg_not_002,cdg_not_003 from cab_doc_gen where cdg_cod_gen ='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_REF']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_REF']."'";
    $sql_nota_parse = oci_parse($conn, $sql_nota);
    oci_execute($sql_nota_parse);
    oci_fetch_all($sql_nota_parse, $nota, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    $items[$i][0] = '-- -- --';
    $items[$i][1] = $nota[0]['CDG_NOT_001'].' '.$nota[0]['CDG_NOT_002'].' '.$nota[0]['CDG_NOT_003'];
    $items[$i][2] = '1';
    $items[$i][3] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',',');;
    $items[$i][4] = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',',');;
    $items[$i][5] = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',',');;
    $items[$i][6] = number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.',','); // total cdg_imp_neto
    //print_r($nota);
}
//print_r($repuestos);
//print_r($servicios);
//print_r($otros);
//print_r($items);


/* TOTALES
***********************************************/
if($cab_doc_gen['CDG_EXI_FRA'] == 'S'){
    $subtotal = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))),2,'.',',');
    $gravadas = number_format((($cab_doc_gen['CDG_VVP_TOT'])-($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))-$cab_doc_gen['CDG_DES_TOT']),2,'.',',');
    $igv = number_format(($cab_doc_gen['CDG_IGV_TOT'] -($cab_doc_gen['CDG_TOT_FRA']/(1+$cab_doc_gen['CDG_POR_IGV']/100))*($cab_doc_gen['CDG_POR_IGV']/100)),2,'.',',');
}else{
    $subtotal = number_format($cab_doc_gen['CDG_VVP_TOT'],2,'.',',');
    $gravadas = number_format(($cab_doc_gen['CDG_VVP_TOT']-$cab_doc_gen['CDG_DES_TOT']),2,'.',',');  // gravadas cdg_vvp_tot-cdg_des_tot
    $igv = number_format($cab_doc_gen['CDG_IGV_TOT'],2,'.',','); // igv total
}
$descuentos = number_format($cab_doc_gen['CDG_DES_TOT'],2,'.',',');
$total = number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.',','); // total cdg_imp_neto


/*LETRAS DEL TOTAL
*******************************/
include ("convertir_a_letras.php");
$letras = convertir_a_letras(number_format($cab_doc_gen['CDG_IMP_NETO'],2,'.',''));


/*REFERENCIA 0:sin  1:nota  2:franquisia 3:anticipo
*****************************************************/
if($cab_doc_gen['CDG_TIP_DOC']=='A'){
    $reference = 1;
    $sql_ref = "select * from cab_doc_gen where cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cla_doc='".$cab_doc_gen['CDG_TIP_REF']."' and cdg_num_doc='".$cab_doc_gen['CDG_DOC_REF']."'";
    $sql_ref_parse = oci_parse($conn, $sql_ref);
    oci_execute($sql_ref_parse);
    oci_fetch_all($sql_ref_parse, $ref, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    $ref_doc = $ref[0]['CDG_TIP_DOC'][0].'00'.$ref[0]['CDG_SER_DOC'].'-'.$ref[0]['CDG_NUM_DOC'];
    $ref_fecha = date("d-m-Y", strtotime($ref[0]['CDG_FEC_GEN']));
    //print_r($ref);
    //echo $ref_serie;



}elseif($cab_doc_gen['CDG_EXI_FRA']=='S' && $cab_doc_gen['CDG_TIP_DOC'] !='A'){
    $reference = 2;
}elseif($cab_doc_gen['CDG_EXI_ANT']=='AN' && $cab_doc_gen['CDG_TIP_DOC'] !='A'){
    $reference = 3;
}else{
    $reference = 0;
}

ob_start();

?>
    <div style="margin-top: 155px;"></div>
    <table style=" width: 100%; text-align: center;">
        <tr>
            <td style="width: 100%; text-align: center;"><?php echo $doc_nombre.' Nro '.$cab_doc_gen['CDG_SER_DOC'].' - '.$_GET['fisico']; ?> </td>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px; border: solid 1px #000; margin-bottom: 20px; padding: 5px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 16%;"><strong>Fecha:</strong></td>
            <td style="width: 44%;"><?php echo $fecha; ?></td>
            <?php
            if ($cabezera_tipo==1){
                echo '<td style="width: 15%;"><strong>Ord. Trab:</strong></td>';
                echo '<td style="width: 25%;">'.$ord_trab.'</td>';
            }else {
                echo '<td style="width: 15%;"></td>';
                echo '<td style="width: 25%;"></td>';
            }
            ?>
        </tr>
        <tr>
            <td style="width: 16%;"><strong>Cliente:</strong></td>
            <td style="width: 44%;"><?php echo $cab_doc_gen['CDG_NOM_CLI']; ?></td>
            <?php
            if ($cabezera_tipo==1){
                echo '<td><strong>Placa/Serie:</strong></td>';
                echo '<td>'.$placa.'</td>';
            }else{
                echo '<td></td>';
                echo '<td></td>';
            }
            ?>
        </tr>
        <tr>
            <td><strong><?php echo $tipo_doc; ?>:</strong></td>
            <td><?php echo $cab_doc_gen['CDG_DOC_CLI']; ?></td>
            <?php
            if($cabezera_tipo==1){
                echo '<td><strong>Modelo/Año:</strong></td>';
                echo '<td>'.$modelo_anho.'</td>';
            }else{
                echo '<td></td>';
                echo '<td></td>';
            }
            ?>
        </tr>
        <tr>
            <td style="width: 16%;"><strong>Dirección:</strong></td>
            <td style="width: 44%;"><?php echo substr($cab_doc_gen['CDG_DIR_CLI'],0,40); ?></td>
            <?php
            if($cabezera_tipo==1){
                echo '<td><strong>Motor/Chasis:</strong></td>';
                echo '<td>'.$motor_chasis.'</td>';
            }else{
                echo '<td></td>';
                echo '<td></td>';
            }
            ?>
        </tr>
        <tr>
            <td style="vertical-align: text-top;"><strong>Forma de Pago:</strong></td>
            <td style="vertical-align: text-top;"><?php echo $forma_pago; ?></td>
            <?php
            if($cabezera_tipo==1){
                echo '<td style="width: 15%; vertical-align: text-top;"><strong>Color:</strong></td>';
                echo '<td style="width: 25%;">'.$color.'</td>';
            }else{
                echo '<td></td>';
                echo '<td></td>';
            }
            ?>
        </tr>
        <tr>
            <td><strong>Ubigeo:</strong></td>
            <td><?php echo $ubigeo; ?></td>
            <?php
            if($cabezera_tipo==1){
                echo '<td><strong>Km:</strong></td>';
                echo '<td>'.$kilometraje.'</td>';
            }else{
                echo '<td></td>';
                echo '<td></td>';
            }
            ?>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px;" cellspacing="0" cellpadding="0">
        <tr style="font-weight: bold;">
            <td style="border-bottom: solid 1px #000; border-left: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; text-align: center;">Nro</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; padding-left: 3px;">Codigo</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; padding-left: 3px;">Descripcion</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: center;">Cant</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">P. Unit</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">Importe</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">Desct</td>
            <td style="border-bottom: solid 1px #000; border-top: solid 1px #000;  border-right: solid 1px #000; text-align: right; padding-right: 3px;">Valor Venta</td>
        </tr>
        <?php
        $i=1;
        foreach($items as $item){
            echo '<tr>';
            echo '<td style="width: 4%; border-left: solid 1px #000; border-right: solid 1px #000; border-bottom: solid 1px #000; text-align: center;">' . $i . '</td>';
            echo '<td style="width: 12%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">'.$item[0].'</td>'; // codigo
            echo '<td style="width: 41%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">'.$item[1].'</td>'; // descripcion
            echo '<td style="width: 5%; text-align: center; border-right: solid 1px #000; border-bottom: solid 1px #000;">'.$item[2].'</td>'; // cantidad
            echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item[3].'</td>'; // unitario
            echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item[4].'</td>'; // importe
            echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item[5].'</td>'; // descuento
            echo '<td style="width: 11%; border-right: solid 1px #000; text-align: right; border-bottom: solid 1px #000; padding-right: 3px;">'.$item[6].'</td>'; // valor venta
            echo '</tr>';
            $i++;
        }
        ?>
        <tr>
            <td colspan="4" rowspan="8" style="width: 60%;border-right: solid 1px #000; line-height: 14px; ">
                <?php
                // notas
                if($cab_doc_gen['CDG_TIP_DOC']=='F' || $cab_doc_gen['CDG_TIP_DOC']=='B'){
                    if($cab_doc_gen['CDG_CO_CR'] != 'AN'){
                        echo $cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003'].'<br>';
                    }
                }elseif($cab_doc_gen['CDG_TIP_DOC']=='A'){
                    echo 'MOTIVO : '.$cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003'].'<br>';
                }
                // referencia
                if($reference == 1){
                    echo '<strong>Ref Doc: '.$ref_doc.' Ref Fecha: '.$ref_fecha.'</strong><br>';
                }
                // facturas por servicios mayores a 700
                if ($cab_doc_gen['CDG_CLA_DOC'] == 'FS' && $cab_doc_gen['CDG_IMP_NETO'] > 700 ){
                    echo "<span style='font-style: italic;'>Operación sujeta al Sistema de pago de Oblig. trib. con el Gob. Central, R.S. 343-2014-SUNAT, Tasa 10%., Cta. Cte Bco.</span><br>";
                }
                echo "<span style='font-style: italic;'>Son: ".$letras."</span><br>";
                ?>

            </td>
            <td colspan="3" style="text-align: right; border-right: solid 1px #000; padding-right: 3px;">Sub Total S/ :</td>
            <td style="border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $subtotal; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;" >Total Descuentos S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $descuentos; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gravadas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $gravadas; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Inafectas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Exoneradas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gratuitas S/ :</td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">I.G.V. 18% S/ :</td>
            <td style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;"><?php echo $igv; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; border-bottom: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">
                <strong>IMPORTE TOTAL S/ :</strong></td>
            <td style="border-top: solid 1px #000; border-bottom: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><strong><?php echo $total; ?></strong></td>
        </tr>
    </table>

<?php

    $content = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(8, 8, 8, 8));
    $html2pdf->writeHTML($content);
    $html2pdf->output('factura.pdf');
}else{
?>
    <form action="">
        <input type="hidden" name="gen" value="<?php echo $_GET['gen']; ?>">
        <input type="hidden" name="emp" value="<?php echo $_GET['emp']; ?>">
        <input type="hidden" name="tip" value="<?php echo $_GET['tip']; ?>">
        <input type="hidden" name="num" value="<?php echo $_GET['num']; ?>">
        <label for="">Numero Nota Credito</label> <input type="text" name="fisico">
        <input type="submit">
    </form>    
<?php
}

?>