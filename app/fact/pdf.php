<?php
    include '../coneccion.php';
    date_default_timezone_set('America/Lima');

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
    $ruta = '../../app/repo/'.$ruta[2].'/'.$ruta[1].'/'.$ruta[0].'/';
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }


    /* CODIGO DE BARRAS
    **************************************************/
    $textoCodBar = "| ".$cab_doc_gen['CDG_TIP_DOC']." | A | 123 | TotIGV | TotMonto | ".date("d-m-Y", strtotime($cab_doc_gen['CDG_FEC_GEN']))." | TipoDoc | F002-026 | VALOR RESUMEN | CodHash |";
    header('Content-Type: text/html; charset=UTF-8');
    require_once ("../../PDF417/vendor/autoload.php");
    use BigFish\PDF417\PDF417;
    use BigFish\PDF417\Renderers\ImageRenderer;
    $pdf417 = new PDF417();
    $codigo_barra = $pdf417->encode($textoCodBar);
    $renderer = new ImageRenderer(['format' => 'png']);
    $image = $renderer->render($codigo_barra);
    $image->save($ruta.'20532710066-'.$doc.'-'.$serie.'.png');

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


    /* SEGUNDA FILA
    ****************************************/
    if ($cab_doc_gen['CDG_CLA_DOC']=='FS' || $cab_doc_gen['CDG_CLA_DOC']=='BS'  ){
        if($cab_doc_gen['CDG_CO_CR'] != 'AN'){
            $cabezera_tipo = 1;
            $sql_extendido = "select * from cab_ord_ser 
                inner join det_ing_ser on dis_pla_veh=cab_ord_ser.cos_pla_veh and dis_cod_gen=cab_ord_ser.cos_cod_gen
                inner join cab_fam_veh on cfv_cod_gen=cab_ord_ser.cos_cod_gen and cfv_cod_mar=det_ing_ser.dis_mar_veh and cfv_cod_fam=det_ing_ser.dis_cod_fam
                where cos_cod_gen='02' and cos_cod_emp='01' and cos_num_ot='23341'";
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
    /*
    $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_tip_doc='".$tip."' and cdg_num_doc='".$num."'";
    $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];
    */


require("../../fpdf/fpdf.php");
class PDF extends FPDF{
    function Header(){}
    function Footer(){}
}
$pdf=new PDF('P','cm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
//$pdf->SetMargins(0, 0, 0);
//$pdf->SetLineWidth(0.02);
//$pdf->SetFillColor(0,0,0);
//$pdf->image($ruta.'20532710066-'.$doc.'-'.$serie.'.png',1, 1, 10, 2.5);


/* MEBRETE IZQUIERDO
**********************/
$pdf->RoundedRect(1, 1, 10.2, 2.6, 0.2, '');
$pdf->image("../../archs_graf/logo.jpg",1.12, 1.1, 2.1, 2.4);
$text = 'TACNA: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015 
cel.:952869639 (repuestos) cel.: 992566630 (servicios)
email: tacna@surmotriz.com y repuestos@surmotriz.com
MOQUEGUA:Sector Yaracachi Mz.D Lte.09 Mariscal Nieto/Moquegua
Telef:(53) 479365 Cel: #953922105 email: moquegua@surmotriz.com
Venta de vehiculos-repuestos y accesorios legitimos Toyota
Reparacion y mantenimiento de automoviles y camionetas.';
$pdf->SetTextColor(0,0,0); $pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,1.1); $pdf->MultiCell(8, 0.35, utf8_decode($text), 0, 'L');


/* MEBRETE DERECHO
*************************/
$pdf->RoundedRect(12, 1, 8, 2.6, 0.2, '');
$pdf->SetTextColor(170,0,0); $pdf->SetFont('arial','',14); $pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, "RUC: 20532710066", 0, 1,'C', 0);
$pdf->SetTextColor(0,0,0); $pdf->SetFont('arial','B',14);
$pdf->SetXY(12,2.2); $pdf->Cell(8, 0.25, utf8_decode($doc_nombre), 0, 1,'C', 0);
$pdf->SetTextColor(0,0,150); $pdf->SetFont('arial','',14);
$pdf->SetXY(12,2.9); $pdf->Cell(8, 0.25, $serie.'-'.$cab_doc_gen['CDG_NUM_DOC'], 0, 1,'C', 0);

/* PRIMERA FILA
*******************************/
$pdf->RoundedRect(1, 4.2, 19, 3.25, 0.1, ''); $pdf->SetTextColor(0,0,0); $pdf->SetFont('arial','B',9);
$pdf->SetXY(1.1,4.4);           $pdf->Cell(3, 0.35, utf8_decode("Fecha"), 0, 1,'L', 0);
$pdf->SetXY(1.1,4.4+0.5);       $pdf->Cell(3, 0.35, utf8_decode("Cliente"), 0, 1,'L', 0);
$pdf->SetXY(1.1,4.4+(0.5*2));   $pdf->Cell(3, 0.35, utf8_decode($tipo_doc), 0, 1,'L', 0);
$pdf->SetXY(1.1,4.4+(0.5*3));   $pdf->Cell(3, 0.35, utf8_decode("Dirección"), 0, 1,'L', 0);
$pdf->SetXY(1.1,4.4+(0.5*4));   $pdf->Cell(3, 0.35, utf8_decode("Forma Pago"), 0, 1,'L', 0);
$pdf->SetXY(1.1,4.4+(0.5*5));   $pdf->Cell(3, 0.35, utf8_decode("Ubigeo"), 0, 1,'L', 0);
$pdf->SetFont('arial','',9);
$pdf->SetXY(3.5,4.4);           $pdf->Cell(8, 0.35, utf8_decode(':  '.$fecha), 0, 1,'L', 0);
$pdf->SetXY(3.5,4.4+0.5);       $pdf->Cell(8, 0.35, utf8_decode(':  '.substr($cab_doc_gen['CDG_NOM_CLI'], 0, 42)), 0, 1,'L', 0);
$pdf->SetXY(3.5,4.4+(0.5*2));   $pdf->Cell(8, 0.35, utf8_decode(':  '.$cab_doc_gen['CDG_DOC_CLI']), 0, 1,'L', 0);
$pdf->SetXY(3.5,4.4+(0.5*3));   $pdf->Cell(8, 0.35, utf8_decode(':  '.substr($cab_doc_gen['CDG_DIR_CLI'], 0, 42)), 0, 1,'L', 0);
$pdf->SetXY(3.5,4.4+(0.5*4));   $pdf->Cell(8, 0.35, utf8_decode(':  '.$forma_pago), 0, 1,'L', 0);
$pdf->SetXY(3.5,4.4+(0.5*5));   $pdf->Cell(8, 0.35, utf8_decode(':  '.$ubigeo), 0, 1,'L', 0);

/* SEGUNDA FILA
************************************/
if($cabezera_tipo==1){
$pdf->SetTextColor(0,0,0); $pdf->SetFont('arial','B',9);
$pdf->SetXY(12,4.4); $pdf->Cell(2.5, 0.35, utf8_decode("Ord. Trab"), 0, 1,'L', 0);
$pdf->SetXY(12,4.4+(0.5)*1); $pdf->Cell(2.5, 0.35, utf8_decode("Placa/Serie"), 0, 1,'L', 0);
$pdf->SetXY(12,4.4+(0.5)*2); $pdf->Cell(2.5, 0.35, utf8_decode("Modelo/Año"), 0, 1,'L', 0);
$pdf->SetXY(12,4.4+(0.5)*3); $pdf->Cell(2.5, 0.35, utf8_decode("Motor/Chasis"), 0, 1,'L', 0);
$pdf->SetXY(12,4.4+(0.5)*4); $pdf->Cell(2.5, 0.35, utf8_decode("Color"), 0, 1,'L', 0);
$pdf->SetXY(12,4.4+(0.5)*5); $pdf->Cell(2.5, 0.35, utf8_decode("Km"), 0, 1,'L', 0);
$pdf->SetTextColor(0,0,0); $pdf->SetFont('arial','',9);
$pdf->SetXY(14.6,4.4); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$ord_trab), 0, 1,'L', 0);
$pdf->SetXY(14.6,4.4+(0.5)*1); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$placa), 0, 1,'L', 0);
$pdf->SetXY(14.6,4.4+(0.5)*2); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$modelo_anho), 0, 1,'L', 0);
$pdf->SetXY(14.6,4.4+(0.5)*3); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$motor_chasis), 0, 1,'L', 0);
$pdf->SetXY(14.6,4.4+(0.5)*4); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$color), 0, 1,'L', 0);
$pdf->SetXY(14.6,4.4+(0.5)*5); $pdf->Cell(3.8, 0.35, utf8_decode(': '.$kilometraje), 0, 1,'L', 0);
}

$pdf->Output($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.pdf', 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
chmod ($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.pdf',0777);  // Se dan permisos de lectura y escritura.
$pdf->Output($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.pdf', 'I'); // Se muestra el documento .PDF en el navegador.

?>