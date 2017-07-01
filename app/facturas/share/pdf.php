<?php

// 5.- Generar PDF y codigo de barras
// ==================================
header('Content-Type: text/html; charset=UTF-8');
require("./fpdf/fpdf.php");
require_once ("./PDF417/vendor/autoload.php");
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;


// Obteniendo datos del archivo .XML (factura electrónica)======================

$xml = file_get_contents($ruta.$f8.'.xml');


#== Obteniendo datos del archivo .XML
$DOM = new DOMDocument('1.0', 'ISO-8859-1');
$DOM->preserveWhiteSpace = FALSE;
$DOM->loadXML($xml);

### DATOS DE LA FACTURA ####################################################






// Obteniendo domicilio.
$DocXML = $DOM->getElementsByTagName('StreetName');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $Domicilio = $Nodo->nodeValue;
    }
    $i++;
}







### DATOS DEL PRODUCTO O SERVICIO. #########################################

// Código del producto o servicio.
$DocXML = $DOM->getElementsByTagName('PriceTypeCode');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $CodProdServ = $Nodo->nodeValue;
    }
    $i++;
}

// Total IGV
$DocXML = $DOM->getElementsByTagName('TaxAmount');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $total_igv_ = $Nodo->nodeValue;
    }
    $i++;
}


// Crear el gráfico con el código de barras. ===================================

// Clave del tipo de documento.
$TipoDoc = $DOM->getElementsByTagName('InvoiceTypeCode')->item(0)->nodeValue;
// Total IGV.
$TotIGV = 0;
// Total importe.
$TotMonto = 0;
// Obteniendo Fecha de emisión.
$FecEmi = $DOM->getElementsByTagName('IssueDate')->item(0)->nodeValue;

$time = strtotime($FecEmi);
$FecEmi = date('d-m-Y',$time);

// Obteniendo Codigo Hash.
$CodHash = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;
$textoCodBar =
    "| $TipoDoc 
    | A
    | 123
    | $TotIGV
    | $TotMonto
    | $FecEmi
    | $TipoDoc
    | F002-026
    | VALOR RESUMEN
    | $CodHash
    |";

$pdf417 = new PDF417();
$codigo_barra = $pdf417->encode($textoCodBar);

// Create a PNG image
$renderer = new ImageRenderer([
    'format' => 'png'
]);

$image = $renderer->render($codigo_barra);
$image->save($ruta.$f8.'.png');

//= Creación del documetno .PDF ================================================

class PDF extends FPDF{

    function Header(){

    }

    function Footer(){

        $this->SetTextColor(0,0,0);
        $this->SetFont('arial','',12);
        $this->SetXY(18,26.2);
        $this->Cell(0.8, 0.25, utf8_decode("Pág. ").$this->PageNo().' de {nb}', 0, 1,'L', 0);
    }
}

// nombre del pdf
$nombre_pdf = '20532710066-'.$DOM->getElementsByTagName('InvoiceTypeCode')->item(0)->nodeValue.'-'.$DOM->getElementsByTagName('ID')->item(6)->nodeValue;
$NomArchPDF = $nombre_pdf.'.pdf';

$pdf=new PDF('P','cm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetLineWidth(0.02);
$pdf->SetFillColor(0,0,0);
$pdf->image("./archs_graf/Membrete_Fact.jpg",1, 1, 10, 2.5);


// Direccion surmotriz
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,1.1);
$pdf->Cell(1, 0.35, utf8_decode("PRINCIPAL: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,1.5);
$pdf->Cell(1, 0.35, utf8_decode("cel.:952869639 (repuestos) cel.: 992566630 (servicios)"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,1.9);
$pdf->Cell(1, 0.35, utf8_decode("email: tacna@surmotriz.com"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,2.3);
$pdf->Cell(1, 0.35, utf8_decode("SUCURSAL: Sector Yaracachi Mz.D Lte.09 Mariscal Nieto/Moquegua"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,2.7);
$pdf->Cell(1, 0.35, utf8_decode("Telef:(53) 479365 Cel: #953922105 email: moquegua@surmotriz.com"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.2,3.1);
$pdf->Cell(1, 0.35, utf8_decode("Reparacion y Mantenimiento con accesorios legitimos de Toyota."), 0, 1,'L', 0);


$pdf->RoundedRect(12, 1, 8, 2.5, 0.2, '');

$pdf->SetTextColor(170,0,0);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, "RUC: 20532710066", 0, 1,'C', 0);



// FACTURA ELECTRONICA, BOLETA, NOTA CREDITO
$tipo_documento = $DOM->getElementsByTagName('InvoiceTypeCode')->item(0)->nodeValue;
if ($tipo_documento == '01'){
    $t_document = 'FACTURA ELECTRONICA';
}elseif ($tipo_documento == '03'){
    $t_document = 'BOLETA ELECTRONICA';
} elseif ($tipo_documento == '07'){
    $t_document = 'NOTA CREDITO ELECTRONICA';
}
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',14);
$pdf->SetXY(12,2.2);
$pdf->Cell(8, 0.25, utf8_decode($t_document), 0, 1,'C', 0);



$num_doc = $DOM->getElementsByTagName('ID')->item(7)->nodeValue;
$pdf->SetTextColor(0,0,150);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,2.9);
$pdf->Cell(8, 0.25, $num_doc, 0, 1,'C', 0);


$pdf->RoundedRect(1, 4.2, 19, 3.2, 0.1, '');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',10);

$pdf->SetXY(1.1,4.4);
$pdf->Cell(1, 0.35, utf8_decode("Señores: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.4+0.6);
$pdf->Cell(1, 0.35, utf8_decode("Dirección: "), 0, 1,'L', 0);


// ruc o dni del cliente
$tipo_doc_cli = $DOM->getElementsByTagName('AdditionalAccountID')->item(1)->nodeValue;
if ($tipo_doc_cli == '1'){
    $tipo_doc_cli_nom = 'DNI';
} elseif ($tipo_doc_cli == '6'){
    $tipo_doc_cli_nom = 'RUC';
} elseif ($tipo_doc_cli == '4'){
    $tipo_doc_cli_nom = 'CARNET EXTRANJERIA';
}
$pdf->SetXY(1.1,4.4+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode($tipo_doc_cli_nom.": "), 0, 1,'L', 0);


$pdf->SetXY(1.1,4.4+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode("Fecha de emisión: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.4+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode("Moneda: "), 0, 1,'L', 0);


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

// razon social del cliente
$CliNomRazSoc = $DOM->getElementsByTagName('RegistrationName')->item(2)->nodeValue;
$pdf->SetXY(4.7,4.4);
$pdf->Cell(1, 0.35, utf8_decode($CliNomRazSoc), 0, 1,'L', 0);

// Direccion cliente
$CliDirecc = $DOM->getElementsByTagName('StreetName')->item(1)->nodeValue;
$pdf->SetXY(4.7,4.4+0.6);
$pdf->Cell(1, 0.35, utf8_decode($CliDirecc), 0, 1,'L', 0);


// Obteniendo RUC
$RUC = $DOM->getElementsByTagName('CustomerAssignedAccountID')->item(1)->nodeValue;

$pdf->SetXY(4.7,4.4+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode($RUC), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.4+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode($FecEmi), 0, 1,'L', 0);

// Tipo moneda
$Moneda = $DOM->getElementsByTagName('DocumentCurrencyCode')->item(0)->nodeValue;
if ($Moneda == 'PEN') {
    $Moneda = 'SOLES';
    $MonedaRes = 'S/';
} else {
    $Moneda = 'DOLARES';
    $MonedaRes = '$';
}
$pdf->SetXY(4.7,4.4+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode($Moneda), 0, 1,'L', 0);

$X = 0;
$Y = 0;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9.5);

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.5, utf8_decode("Nº Pieza"), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(7, 0.5, utf8_decode("Descripcion"), 1, 1,'L', 0);

$pdf->SetXY($X+10.5,$Y+8);
$pdf->Cell(1.5, 0.5, utf8_decode("Cantidad"), 1, 1,'C', 0);

$pdf->SetXY($X+12,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("P. Unitario"), 1, 1,'R', 0);

$pdf->SetXY($X+14,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("Importe"), 1, 1,'R', 0);

$pdf->SetXY($X+16,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("Descuento"), 1, 1,'R', 0);

$pdf->SetXY($X+18,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("Valor Venta"), 1, 1,'R', 0);



if ($c18 == 'COCRD'){
    $h = 1;
    //$lineas = $DOM->getElementsByTagName('InvoiceLine')->item(1)->getElementsByTagName('ID')->item(0)->nodeValue;
    $dinvoiceLines = $DOM->getElementsByTagName('InvoiceLine');
    foreach ($dinvoiceLines as $dinvoiceLine) {

        $det_codigo = $dinvoiceLine->getElementsByTagName('ID')->item(2)->nodeValue;
        $det_descripcion = $dinvoiceLine->getElementsByTagName('Description')->item(0)->nodeValue;
        $det_cantidad = $dinvoiceLine->getElementsByTagName('InvoicedQuantity')->item(0)->nodeValue;
        $det_precio_unitario = $dinvoiceLine->getElementsByTagName('PriceAmount')->item(1)->nodeValue;
        $det_importe = round($det_cantidad*$det_precio_unitario,2);
        $det_descuento = $dinvoiceLine->getElementsByTagName('Amount')->item(0)->nodeValue;
        $det_valor_venta = $dinvoiceLine->getElementsByTagName('LineExtensionAmount')->item(0)->nodeValue;

        $Y = $Y + 0.5;
        $pdf->SetXY($X + 1, $Y + 8);
        $pdf->Cell(2.5, 0.5, utf8_decode($det_codigo), 1, 1, 'L', 0);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('arial','',9);

        $pdf->SetXY($X + 3.5, $Y + 8);
        $pdf->Cell(7, 0.5, utf8_decode(substr($det_descripcion,0,36)), 1, 1, 'L', 0);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('arial','',9.5);

        $pdf->SetXY($X + 10.5, $Y + 8);
        $pdf->Cell(1.5, 0.5, utf8_decode($det_cantidad), 1, 1, 'C', 0);

        $pdf->SetXY($X + 12, $Y + 8);
        $pdf->Cell(2, 0.5, utf8_decode($det_precio_unitario), 1, 1, 'R', 0);

        $pdf->SetXY($X + 14, $Y + 8);
        $pdf->Cell(2, 0.5, number_format($det_importe, 2), 1, 1, 'R', 0);

        $pdf->SetXY($X + 16, $Y + 8);
        $pdf->Cell(2, 0.5, number_format($det_descuento, 2), 1, 1, 'R', 0);

        $pdf->SetXY($X + 18, $Y + 8);
        $pdf->Cell(2, 0.5, number_format($det_valor_venta, 2), 1, 1, 'R', 0);
        $h++;
    }
}elseif ($c18 == 'COCRR' || $c18 == 'AND') {
    $dinvoiceLine = $DOM->getElementsByTagName('InvoiceLine')->item(0);
    //$det_codigo = $dinvoiceLine->getElementsByTagName('ID')->item(2)->nodeValue;
    $det_descripcion = $dinvoiceLine->getElementsByTagName('Description')->item(0)->nodeValue;
    $det_cantidad = $dinvoiceLine->getElementsByTagName('InvoicedQuantity')->item(0)->nodeValue;
    $det_precio_unitario = $dinvoiceLine->getElementsByTagName('PriceAmount')->item(1)->nodeValue;
    $det_importe = round($det_cantidad*$det_precio_unitario,2);
    $det_descuento = $dinvoiceLine->getElementsByTagName('Amount')->item(0)->nodeValue;
    $det_valor_venta = $det_importe-$det_descuento;

    $Y = $Y + 0.5;

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('arial','',9);
    $pdf->SetXY($X + 1, $Y + 8);
    $pdf->Cell(9.5, 0.5, utf8_decode(substr($det_descripcion,0,49)), 1, 1, 'L', 0);

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('arial','',9.5);

    $pdf->SetXY($X + 10.5, $Y + 8);
    $pdf->Cell(1.5, 0.5, utf8_decode($det_cantidad), 1, 1, 'C', 0);

    $pdf->SetXY($X + 12, $Y + 8);
    $pdf->Cell(2, 0.5, utf8_decode($det_precio_unitario), 1, 1, 'R', 0);

    $pdf->SetXY($X + 14, $Y + 8);
    $pdf->Cell(2, 0.5, number_format($det_importe, 2), 1, 1, 'R', 0);

    $pdf->SetXY($X + 16, $Y + 8);
    $pdf->Cell(2, 0.5, number_format($det_descuento, 2), 1, 1, 'R', 0);

    $pdf->SetXY($X + 18, $Y + 8);
    $pdf->Cell(2, 0.5, number_format($det_valor_venta, 2), 1, 1, 'R', 0);

}


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9.5);

// operaciones
$DocXML = $DOM->getElementsByTagName('PayableAmount');
$i=0;
foreach($DocXML as $Nodo){
    // Descuentos Totales 2005
    if ($i==0){
        $total_descuento = $Nodo->nodeValue;

        // Operaciones Gravadas 1001
    }elseif ($i==1){
        $operaciones_gravadas = $Nodo->nodeValue;

        // Operaciones Inafectas 1002
    } elseif ($i==2){
        $operaciones_inafectas = $Nodo->nodeValue;

        // Operaciones Exoneradas 1003
    } elseif ($i==3){
        $operaciones_exoneradas = $Nodo->nodeValue;

        // Operaciones Gratuitas
    } elseif ($i==4){
        $operaciones_gratuitas = $Nodo->nodeValue;

        // subtotal
    }elseif ($i==5){
        $sub_total = $Nodo->nodeValue;

        // Importe total
    } elseif ($i==6){
        $importe_total = $Nodo->nodeValue;


    }

    $i++;
}

$pdf->SetXY(1,$Y+8.5);
$pdf->Cell(11, 0.5, utf8_decode('Son : '.$leyenda_100), 0, 1,'L', 0);

$pdf->SetXY(12,$Y+8.5);
$pdf->Cell(6, 0.5, utf8_decode("Sub Total ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+8.5);
$pdf->Cell(2, 0.5, number_format($sub_total,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+9);
$pdf->Cell(6, 0.5, utf8_decode("Total Descuentos ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+9);
$pdf->Cell(2, 0.5, number_format($total_descuento,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+9.5);
$pdf->Cell(6, 0.5, utf8_decode("Operaciones Gravadas ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+9.5);
$pdf->Cell(2, 0.5, number_format($operaciones_gravadas,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+10);
$pdf->Cell(6, 0.5, utf8_decode("Operaciones Inafectas ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+10);
$pdf->Cell(2, 0.5, number_format($operaciones_inafectas,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+10.5);
$pdf->Cell(6, 0.5, utf8_decode("Operaciones Exoneradas ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+10.5);
$pdf->Cell(2, 0.5, number_format($operaciones_exoneradas,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+11);
$pdf->Cell(6, 0.5, utf8_decode("Operaciones Gratuitas ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+11);
$pdf->Cell(2, 0.5, number_format($operaciones_gratuitas,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+11.5);
$pdf->Cell(6, 0.5, utf8_decode("I.G.V. 18% ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+11.5);
$pdf->Cell(2, 0.5, number_format($total_igv_,2), 1, 1,'R', 0);

$pdf->SetXY(12,$Y+12);
$pdf->Cell(6, 0.5, utf8_decode("IMPORTE TOTAL ".$MonedaRes." : "), 1, 1,'R', 0);
$pdf->SetXY(18,$Y+12);
$pdf->Cell(2, 0.5, number_format($importe_total,2), 1, 1,'R', 0);

// anticipo
if ($anticipo_current == 1) {
    $pdf->SetXY(1, $Y + 9);
    $pdf->Cell(8, 0.5, utf8_decode("Documento Relacionado por Anticipo"), 1, 1, 'C', 0);
    $pdf->SetXY(1, $Y + 9.5);
    $pdf->Cell(2.5, 0.5, utf8_decode("Documento "), 1, 1, 'C', 0);
    $pdf->SetXY(1, $Y + 10);
    $pdf->Cell(2.5, 0.5, utf8_decode($anticipo_doc), 1, 1, 'C', 0);

    $pdf->SetXY(3.5, $Y + 9.5);
    $pdf->Cell(3, 0.5, utf8_decode("Documt. Cliente"), 1, 1, 'C', 0);
    $pdf->SetXY(3.5, $Y + 10);
    $pdf->Cell(3, 0.5, utf8_decode($anticipo_documento), 1, 1, 'C', 0);

    $pdf->SetXY(6.5, $Y + 9.5);
    $pdf->Cell(2.5, 0.5, utf8_decode("Monto"), 1, 1, 'C', 0);
    $pdf->SetXY(6.5, $Y + 10);
    $pdf->Cell(2.5, 0.5, utf8_decode($anticipo_tot . ' ' . $anticipo_moneda_pdf), 1, 1, 'C', 0);
}

// Codigo de barras
$pdf->image($ruta.$f8.".png",0.67, $Y+10.7, 8.65, 2);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9);
$pdf->SetXY(1.12,$Y+12.7);
$pdf->Cell(8, 0.25, utf8_decode("Representación impresa de la factura electrónica."), 0, 1,'C', 0);

$pdf->line(1, 24.8, 20.5, 24.8);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9);
$pdf->SetXY(1,25);
$pdf->MultiCell(19.5, 0.35, utf8_decode("Representación Impresa de la Factura ElectrónicaCódigo Hash: $CodHash
Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 112-005-0000143/SUNAT
Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php"), 0, 'C');

//==============================================================================
$pdf->Output($ruta.$NomArchPDF, 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
chmod ($ruta.$NomArchPDF,0777);  // Se dan permisos de lectura y escritura.
$pdf->Output($ruta.$NomArchPDF, 'I'); // Se muestra el documento .PDF en el navegador.