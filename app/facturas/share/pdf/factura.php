<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

ob_start();
?>
<table style="width: 100%;  margin-bottom: 20px;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 15%; border-left: solid 1px #000; border-top: solid 1px #000; border-bottom: solid 1px #000;">
            <img src="logo.jpg" style="height: 100px;">
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
            <div style="text-align: center; font-weight: bold; font-size: 18px; line-height: 25px; ">NOTA CREDITO ELECTRÓNICA</div>
            <div style="text-align: center; color: blue; font-size: 19px; line-height: 25px;">F001-17194</div>
        </td>
    </tr>
</table>


<table style="width: 100%; font-size: 12px; border: solid 1px #000; margin-bottom: 20px; padding: 5px;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width: 16%;"><strong>Fecha</strong></td>
        <td style="width: 44%;">: 31-07-2017</td>
        <td style="width: 15%;"><strong>Ord. Trab</strong></td>
        <td style="width: 25%;">: 23365</td>
    </tr>
    <tr>
        <td><strong>Cliente</strong></td>
        <td>: CHAVEZ GUZMAN HONORATO HUGO</td>
        <td><strong>Placa/Serie</strong></td>
        <td>: Z2X069</td>
    </tr>
    <tr>
        <td><strong>RUC</strong></td>
        <td>: 10004188298</td>
        <td><strong>Modelo/Año</strong></td>
        <td>: AVENSIS - 2009</td>
    </tr>
    <tr>
        <td><strong>Dirección</strong></td>
        <td>: NRO. MZ41 INT. LT36 ASC. LOS CLAVELES</td>
        <td><strong>Motor/Chasis</strong></td>
        <td>: SB1BL76L09E004564</td>
    </tr>
    <tr>
        <td><strong>Forma de Pago</strong></td>
        <td>: Contado</td>
        <td><strong>Color</strong></td>
        <td>: GRIS OSCURO METALICO</td>
    </tr>
    <tr>
        <td><strong>Ubigeo</strong></td>
        <td>: Tacna-Tacna-Coronel Gregorio Albarracin</td>
        <td><strong>Km</strong></td>
        <td>: 85696</td>
    </tr>
</table>

<table style="width: 100%; font-size: 12px;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="border-bottom: solid 1px #000; border-left: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">Nro</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">Codigo</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7;">Descripcion</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">Cant</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">P. Unit</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">Importe</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; text-align: right;">Desct</td>
        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; background-color: #e7e7e7; border-right: solid 1px #000; text-align: right;">Valor Venta</td>
    </tr>
    <?php
        for ($i = 1; $i <=3; $i++) {
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
        <td colspan="4" rowspan="8" style="width: 60%; border-top: solid 1px #000; border-right: solid 1px #000; line-height: 14px;">
            Nota: Esta nota sale en todos y esto es de dos lineas seguidas. Esta nota sale en todos y esto es de dos lineas seguidas.<br>
            <span style="font-style: italic;">Operación sujeta al Sistema de pago de Oblig. trib. con el Gob. Central, R.S. 343-2014-SUNAT, Tasa 10%., Cta. Cte Bco.</span><br>
            <span style="font-style: italic;">Son : Ochenta y ocho con 97/100 soles.</span><br>
            <img src="20532710066-07-FN03-2917.png" style="height: 55px; width: 300px; text-align: center;">
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
        <td colspan="3" style="border-top: solid 1px #000; border-bottom: solid 1px #000; text-align: right;"><strong>IMPORTE TOTAL S/ :</strong></td>
        <td style="border-top: solid 1px #000; border-bottom: solid 1px #000; border-right: solid 1px #000; text-align: right;"><strong>200.00</strong></td>
    </tr>
</table>
<hr style="border: none; height: 1px; background-color: #414141; margin-top: 30px;">
<span style="text-align: center; font-size: 11px;">Representación Impresa de la Factura Electrónica. SURMOTRIZ S.R.L. Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 112-005-0000143/SUNAT
Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php</span>
<?php
$content = ob_get_clean();
$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(8, 8, 8, 8));
$html2pdf->writeHTML($content);
$html2pdf->output('bookmark.pdf');
?>