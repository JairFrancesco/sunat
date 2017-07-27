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
        $doc_nombre = 'FACTURA ELECTRONICA';
        $serie = 'F00'.$cab_doc_gen['CDG_SER_DOC'];
    }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'B'){
        $doc = '03';
        $serie = 'B00'.$cab_doc_gen['CDG_SER_DOC'];
        $doc_nombre = 'BOLETA ELECTRONICA';
    }elseif($cab_doc_gen['CDG_TIP_DOC'] == 'A'){
        $doc = '07';
        $doc_nombre = 'NOTA CREDITO ELECTRONICA';
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


    /* CODIGO DE BARRAS
    **************************************************/
    $textoCodBar = "| ".$cab_doc_gen['CDG_TIP_DOC']." | A | 123 | TotIGV | TotMonto | ".date("d-m-Y", strtotime($cab_doc_gen['CDG_FEC_GEN']))." | TipoDoc | F002-026 | VALOR RESUMEN | CodHash |";
    header('Content-Type: text/html; charset=UTF-8');
    require("../../fpdf/fpdf.php");
    require_once ("../../PDF417/vendor/autoload.php");
    use BigFish\PDF417\PDF417;
    use BigFish\PDF417\Renderers\ImageRenderer;
    $pdf417 = new PDF417();
    $codigo_barra = $pdf417->encode($textoCodBar);
    $renderer = new ImageRenderer(['format' => 'png']);
    $image = $renderer->render($codigo_barra);
    $image->save($ruta.'20532710066-'.$doc.'-'.$serie.'.png');


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

$pdf->RoundedRect(1, 1, 10, 2.55, 0.2, '');
$pdf->RoundedRect(12, 1, 8, 2.55, 0.2, '');

$pdf->Output($ruta.'20532710066-'.$doc.'-'.$serie.'.pdf', 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
chmod ($ruta.'20532710066-'.$doc.'-'.$serie.'.pdf',0777);  // Se dan permisos de lectura y escritura.
$pdf->Output($ruta.'20532710066-'.$doc.'-'.$serie.'.pdf', 'I'); // Se muestra el documento .PDF en el navegador.

?>