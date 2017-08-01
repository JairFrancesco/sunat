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

$text = 'Fecha
Cliente
DNI
Direccion
Forma de Pago
Ubigeo';
$text1 = ': Fecha
: Cliente
: DNI
: Direccion
: Forma de Pago
: Ubigeo';
$x = $pdf->GetX(); $y = $pdf->GetY();
$pdf->SetFont('Arial','B',9); $pdf->SetTextColor(0,0,0); $pdf->SetXY($x,$y+1.6); $pdf->MultiCell(30, 5, utf8_decode($text), 0,'L');
$pdf->SetFont('Arial','',9); $pdf->SetXY($x+30,$y+1.6); $pdf->MultiCell(30, 5, utf8_decode($text1), 0,'L');
$text2 = 'Fecha
Cliente
DNI
Direccion
Forma de Pago
Ubigeo';
$text3 = ': Fecha
: Cliente
: DNI
: Direccion
: Forma de Pago
: Ubigeo';
$pdf->SetFont('Arial','B',9); $pdf->SetTextColor(0,0,0); $pdf->SetXY($x+115,$y+1.6); $pdf->MultiCell(30, 5, utf8_decode($text2), 0,'L');
$pdf->SetFont('Arial','',9); $pdf->SetXY($x+145,$y+1.6); $pdf->MultiCell(30, 5, utf8_decode($text3), 0,'L');

$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(238, 238, 238);
$pdf->Cell(7,5,'Nro',1,0,'C',true);
$pdf->Cell(22,5,'Codigo',1,0,'L',true);
$pdf->Cell(53,5,'Descripcion',1,0,'L',true);
$pdf->Cell(22,5,'Cantidad',1,0,'R',true);
$pdf->Cell(20,5,'P. Unitario',1,0,'R',true);
$pdf->Cell(22,5,'Importe',1,0,'R',true);
$pdf->Cell(22,5,'Descuento',1,0,'R',true);
$pdf->Cell(22,5,'Valor Venta',1,0,'R',true);
$pdf->Ln();

//Table with 20 rows and 4 columns
$pdf->SetFont('Arial','',9);
$pdf->SetAligns(array('C','L','L','R','R','R','R','R',));
$pdf->SetWidths(array(7,22,53,22,20,22,22,22));
for($i=0;$i<30;$i++){
    $pdf->Row(array($i+1,'Codigo','Descripcion','Cantidad','P. Unitario','Importe','Descuento','Valor Venta'));
}

$pdf->SetAligns(array('L','R','R'));
$pdf->SetWidths(array(124,44,22));
$pdf->Row(array('','Sub Total S/ :','Descripcion'));
$pdf->Row(array('','Total Descuentos S/ :','Descripcion'));
$pdf->Row(array('','Operaciones Gravadas S/ :','Descripcion'));
$pdf->Row(array('','Operaciones Inafectas S/ :','Descripcion'));
$pdf->Row(array('','Operaciones Exoneradas S/ :','Descripcion'));
$pdf->Row(array('','Operaciones Gratuitas S/ :','Descripcion'));
$pdf->Row(array('','I.G.V. 18% S/ :','Descripcion'));
$pdf->Row(array('','IMPORTE TOTAL S/ :','Descripcion'));

//$librerias->MultiCell(100, 30, $librerias->image("../archs_graf/20532710066-07-FN03-2917.png",$librerias->GetX(), $librerias->GetY()+10, 80, 24,'PNG'), 0, 'R');
$pdf->image("../archs_graf/20532710066-07-FN03-2917.png",$pdf->GetX(), $pdf->GetY()+10, 80, 24,'PNG');
$pdf->SetXY($pdf->GetX()+90, $pdf->GetY()+10); $pdf->MultiCell(100, 5, utf8_decode('Operación sujeta al Sistema de pago de Oblig. trib. con el Gobierno Central, R.S. 343-2014-SUNAT, Tasa 10%., Cta. Cte Bco. '), 0, 'R');
$pdf->SetXY($pdf->GetX(), $pdf->GetY()+12); $pdf->MultiCell(80, 5, utf8_decode("Representación impresa de la factura electrónica."), 0, 'C');
//$librerias->line($librerias->GetX(), $librerias->GetY()+10, $librerias->GetX()+100, $librerias->GetY()+10);
//$librerias->Ln();
$pdf->SetXY($pdf->GetX(), $pdf->GetY()+5); $pdf->MultiCell(190, 5, utf8_decode("SURMOTRIZ S.R.L Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 112-005-0000143/SUNAT. Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php"), 0, 'C');
/*
$x = $librerias->GetX(); $y = $librerias->GetY();
$librerias->SetXY($x,$y); $librerias->MultiCell(146, 5, utf8_decode('Valor Venta'), 1,'L');
$librerias->SetXY($x+146,$y); $librerias->MultiCell(44, 5, utf8_decode('Valor Venta Valor Venta Valor Venta Valor Venta'), 1,'L');
$librerias->MultiCell(44, 5, utf8_decode('Valor Venta Valor Venta Valor Venta Valor Venta'), 1,'L');
*/
//$librerias->Cell(146,5,'',0);
//$librerias->Cell(44,5,'Valor Venta',1);
$pdf->Output();


?>