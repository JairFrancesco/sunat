<?php
header('Content-Type: text/html; charset=UTF-8');
require("fpdf/fpdf.php");
require_once ("PDF417/vendor/autoload.php");
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;

// Variables correspondientes a la factura.
$RUC = "";       // RUC.
$NomRazSoc = ""; // Nombre o Razón social.
$FecEmi = "";    // Fecha de emisión.
$Domicilio = ""; // Domicilio.
$CodHash = "";   // Código Hash.
$TipoDoc = "";   // Tipo de documento.
$TotGrav = 0;    // Total gravado.
$TotIGV = 0;     // Total IGV.
$TotMonto = 0;   // Total importe. 

// Variables correspondientes a los productos o servicios de la factura.

$CodProdServ = ""; // Código.
$ProdServ = ""; // Descripción.
$Cant = 0; // Cantidad. 
$UniMed = ""; // Unidad de medida.
$Precio = 0; // Precio unitario.
$Importe = 0;  // Importe.


// Obteniendo datos del archivo .XML (factura electrónica)======================

$xml = file_get_contents('20532710066-01-F002-00000026.xml');

#== Obteniendo datos del archivo .XML 

    $DOM = new DOMDocument('1.0', 'ISO-8859-1');
    $DOM->preserveWhiteSpace = FALSE;
    $DOM->loadXML($xml);

    ### DATOS DE LA FACTURA ####################################################
    
    // Obteniendo RUC.
    $DocXML = $DOM->getElementsByTagName('CustomerAssignedAccountID');
        foreach($DocXML as $Nodo){
        $RUC = $Nodo->nodeValue; 
    }    
    
    // Obteniendo Fecha de emisión.
    $DocXML = $DOM->getElementsByTagName('IssueDate');
    foreach($DocXML as $Nodo){
        $FecEmi = $Nodo->nodeValue; 
    }    

    // Obteniendo Nombre o Razón social.
    $DocXML = $DOM->getElementsByTagName('RegistrationName');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $NomRazSoc = $Nodo->nodeValue; 
        }
        $i++;
    }    

    // Obteniendo domicilio.
    $DocXML = $DOM->getElementsByTagName('StreetName');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $Domicilio = $Nodo->nodeValue; 
        }
        $i++;
    }    

    // Obteniendo Codigo Hash.
    $DocXML = $DOM->getElementsByTagName('DigestValue');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $CodHash = $Nodo->nodeValue; 
        }
        $i++;
    }    
    
    // Clave del tipo de documento.
    $DocXML = $DOM->getElementsByTagName('InvoiceTypeCode');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $TipoDoc = $Nodo->nodeValue; 
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
    
    // Descripción del producto o servicio.
    $DocXML = $DOM->getElementsByTagName('Description');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $ProdServ = $Nodo->nodeValue; 
        }
        $i++;
    }    
    
    // Cantidad de producto o servicio.
    $DocXML = $DOM->getElementsByTagName('InvoicedQuantity');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $Cant = $Nodo->nodeValue; 
        }
        $i++;
    }    
    
    // Unidad de medida del producto o servicio.
    $DocXML = $DOM->getElementsByTagName('InvoicedQuantity');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            //$UniMed = $Nodo->nodeValue; 
            $UniMed = $Nodo->getAttribute('unitCode');
        }
        $i++;
    }    
 
    // Precio unitario. 
    $DocXML = $DOM->getElementsByTagName('PriceAmount');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==1){
            $Precio = $Nodo->nodeValue; 
        }
        $i++;
    }    

    // Importe.
    $DocXML = $DOM->getElementsByTagName('LineExtensionAmount');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==0){
            $Importe = $Nodo->nodeValue; 
        }
        $i++;
    }    
    
    ### TOTALES DE LA FACTURA ##################################################
    
    // Total gravado.
    $DocXML = $DOM->getElementsByTagName('PayableAmount');
    $i=0;
    foreach($DocXML as $Nodo){
        if ($i==1){
            $TotGrav = $Nodo->nodeValue;
        }
        $i++;
    }    
    
    // Total IGV.
    $DocXML = $DOM->getElementsByTagName('TaxAmount');
    $i=0;
    foreach($DocXML as $Nodo){
        $TotIGV = $Nodo->nodeValue; 
    }    
    
    // Monto total.
    $DocXML = $DOM->getElementsByTagName('PayableAmount');
    $i=0;
    foreach($DocXML as $Nodo){
        $TotMonto = $Nodo->nodeValue; 
    }    
    
    
// Crear el gráfico con el código de barras. ===================================
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
$image->save('20532710066-01-F002-00000026.png');    


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

$NomArchPDF = "factura.pdf";


$pdf=new PDF('P','cm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetLineWidth(0.02);
$pdf->SetFillColor(0,0,0);

   
$pdf->image("archs_graf/Membrete_Fact.jpg",1, 1, 10, 2.5);
$pdf->image("20532710066-01-F002-00000026.png",0.7, 10, 9, 3);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);
$pdf->SetXY(1.2,13);
$pdf->Cell(8, 0.25, utf8_decode("Representación impresa de la factura electrónica."), 0, 1,'C', 0);

$pdf->RoundedRect(12, 1, 8, 2.5, 0.2, ''); 

$pdf->SetTextColor(170,0,0);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, "RUC: 20138998780", 0, 1,'C', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',14);
$pdf->SetXY(12,2.2);
$pdf->Cell(8, 0.25, utf8_decode("FACTURA ELECTRÓNICA"), 0, 1,'C', 0);


$pdf->SetTextColor(0,0,150);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,2.9);
$pdf->Cell(8, 0.25, "F002-026", 0, 1,'C', 0);


$pdf->RoundedRect(1, 4, 19, 3.2, 0.2, ''); 

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',10);

$pdf->SetXY(1.1,4.2);
$pdf->Cell(1, 0.35, utf8_decode("Señores: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+0.6);
$pdf->Cell(1, 0.35, utf8_decode("Dirección: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode("RUC: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode("Fecha de emisión: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode("Moneda: "), 0, 1,'L', 0);


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY(4.7,4.2);
$pdf->Cell(1, 0.35, utf8_decode($NomRazSoc), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+0.6);
$pdf->Cell(1, 0.35, utf8_decode($Domicilio), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode($RUC), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode($FecEmi), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode("SOL"), 0, 1,'L', 0);


$X = 0;
$Y = 0;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.5, utf8_decode("Código"), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.5, utf8_decode("Descripción"), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("Cantidad"), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.5, utf8_decode("Unidad"), 1, 1,'L', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.5, utf8_decode("Precio unitario"), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.5, utf8_decode("Precio venta"), 1, 1,'R', 0);


$Y = $Y + 0.5;

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.8, utf8_decode($CodProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.8, utf8_decode($ProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.8, utf8_decode($Cant), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.8, utf8_decode($UniMed), 1, 1,'L', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.8, number_format($Precio,2), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.8, number_format($Importe,2), 1, 1,'R', 0);



$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY(9.9,10);
$pdf->Cell(7.6, 0.5, utf8_decode("Total Valor de Venta - Operaciones Gravadas:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10);
$pdf->Cell(2.5, 0.5, number_format($TotGrav,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+0.5);
$pdf->Cell(7.6, 0.5, utf8_decode("IGV:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+0.5);
$pdf->Cell(2.5, 0.5, number_format($TotIGV,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+(0.5*2));
$pdf->Cell(7.6, 0.5, utf8_decode("Importe Total:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+(0.5*2));
$pdf->Cell(2.5, 0.5, number_format($TotMonto,2), 1, 1,'R', 0);

$pdf->line(1, 24.8, 20.5, 24.8);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9);
$pdf->SetXY(1,25);
$pdf->MultiCell(19.5, 0.35, utf8_decode("Representación Impresa de la Factura ElectrónicaCódigo Hash: $CodHash
Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 0180050002185/SUNAT
Para consultar el comprobante ingresar a : https://portal.efacturacion.pe/appefacturacion"), 0, 'C');

//==============================================================================
    
$pdf->Output($NomArchPDF, 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
chmod ($NomArchPDF,0777);  // Se dan permisos de lectura y escritura.
    
$pdf->Output($NomArchPDF, 'I'); // Se muestra el documento .PDF en el navegador.    



