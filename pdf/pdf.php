<?php

require('mc_table.php');

$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',7);
$pdf->Rect($pdf->GetX(), $pdf->GetY(), 103, 25, 'D');
$pdf->Rect($pdf->GetX()+113, $pdf->GetY(), 77, 25, 'D');
$pdf->image("../archs_graf/logo.jpg",$pdf->GetX()+0.5, $pdf->GetY()+0.5, 21.5, 24);

$text = 'TACNA: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015 
cel.:952869639 (repuestos) cel.: 992566630 (servicios)
email: tacna@surmotriz.com y repuestos@surmotriz.com
MOQUEGUA:Sector Yaracachi Mz.D Lte.09 Mariscal Nieto/Moquegua
Telef:(53) 479365 Cel: #953922105 email: moquegua@surmotriz.com
Venta de vehiculos-repuestos y accesorios legitimos Toyota
Reparacion y mantenimiento de automoviles y camionetas.';
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 23, $y+1.3); $pdf->MultiCell(80, 3.2, $text, 0);
$pdf->SetFont('Arial','',14); $pdf->SetTextColor(170,0,0); $pdf->SetXY($x + 115, $y+1.5); $pdf->MultiCell(73, 7, 'RUC: 20532710066', 0,'C');
$pdf->SetFont('Arial','B',14); $pdf->SetTextColor(0,0,0); $pdf->SetXY($x + 113, $y+9); $pdf->MultiCell(77, 7, utf8_decode('NOTA CREDITO ELECTRÓNICA'), 0,'C');
$pdf->SetTextColor(0,0,150); $pdf->SetFont('Arial','',14); $pdf->SetXY($x + 115, $y+16); $pdf->MultiCell(73, 7, 'RUC: 20532710066', 0,'C');

$pdf->Ln();
$pdf->Rect($pdf->GetX(), $pdf->GetY(), 190, 33, 'D');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();



$pdf->SetFont('Arial','',9); $pdf->SetTextColor(0,0,0);
$pdf->Cell(22,5,'Codigo',1);
$pdf->Cell(60,5,'Descripcion',1);
$pdf->Cell(22,5,'Cantidad',1);
$pdf->Cell(20,5,'P. Unitario',1);
$pdf->Cell(22,5,'Importe',1);
$pdf->Cell(22,5,'Descuento',1);
$pdf->Cell(22,5,'Valor Venta',1);
$pdf->Ln();

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(22,60,22,20,22,22,22));

for($i=0;$i<80;$i++)
    $pdf->Row(array('Codigo numero dos','Descripcion','Cantidad','P. Unitario','Importe','Descuento','Valor Venta'));
$pdf->Output();
    /*
    include ('fpdf181/fpdf.php');
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(22,5,'Codigo',1);
    $pdf->Cell(60,5,'Descripcion',1);
    $pdf->Cell(22,5,'Cantidad',1);
    $pdf->Cell(20,5,'P. Unitario',1);
    $pdf->Cell(22,5,'Importe',1);
    $pdf->Cell(22,5,'Descuento',1);
    $pdf->Cell(22,5,'Valor Venta',1);
    $pdf->Ln();
     $pdf->MultiCell(22,5,$pdf->GetY() ,'LRB');
    $pdf->MultiCell(22,5,$pdf->GetX(),'LRB');
    */
    /*
    for($i=0;$i<=80;$i++){
        $pdf->MultiCell(22,5,'Codigo',1);
        $pdf->MultiCell(60,5,'Descripcion',1);
        $pdf->MultiCell(22,5,'Cantidad',1);
        $pdf->MultiCell(20,5,'P. Unitario',1);
        $pdf->MultiCell(22,5,'Importe',1);
        $pdf->MultiCell(22,5,'Descuento',1);
        $pdf->MultiCell(22,5,'Valor Venta',1);
        $pdf->Ln();
    }

    //$pdf->Cell(146,5,'',0);
    //$pdf->Cell(44,5,'Valor Venta',1);
    $pdf->Output();
    */

?>