<?php
    require 'vendor/autoload.php';
    date_default_timezone_set('America/Lima');    
    include "__docs.php";
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
                echo '<td style="width: 12%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">'.$item['codigo'].'</td>'; // codigo
                echo '<td style="width: 41%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">'.$item['descripcion'].'</td>'; // descripcion
                echo '<td style="width: 5%; text-align: center; border-right: solid 1px #000; border-bottom: solid 1px #000;">'.$item['cantidad'].'</td>'; // cantidad
                echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item['unitario'].'</td>'; // unitario
                echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item['importe'].'</td>'; // importe
                echo '<td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">'.$item['descuento'].'</td>'; // descuento
                echo '<td style="width: 11%; border-right: solid 1px #000; text-align: right; border-bottom: solid 1px #000; padding-right: 3px;">'.$item['venta'].'</td>'; // valor venta
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
                            // si es franquisia anticipo
                            if($reference==2){                                
                                echo 'Ref. '.$fra['CDG_TIP_DOC'].'00'.$fra['CDG_SER_DOC'].'-'.$fra['CDG_NUM_DOC'].' Fecha Ref. '.date("d-m-Y", strtotime($fra['CDG_FEC_GEN'])).'<br>';
                            }elseif ($reference==3) { // anticipo
                                echo 'Anticipo '.$anticipo_serie_numero_doc.' Fecha '.$anticipo_fecha.'<br>';
                            }
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
                        echo "<span style='font-style: italic;'>Operación sujeta al Sistema de pago de Oblig. trib. con el Gob. Central, R.S. 343-2014-SUNAT, Tasa 10%., Cta. Cte Bco. Nación no. 00-151-084257</span><br>";
                    }
                    echo "<span style='font-style: italic;'>Son: ".$letras."</span><br>";
                ?>
                <img src='images/20532710066-07-FN03-2917.png' style='height: 55px; width: 300px; text-align: center;'>
            </td>
            <td colspan="3" style="text-align: right; border-right: solid 1px #000; padding-right: 3px;">Sub Total <?php echo $moneda_nombre; ?></td>
            <td style="border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $subtotal; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;" >Total Descuentos <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $descuentos; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gravadas <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><?php echo $gravadas; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Inafectas <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Exoneradas <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gratuitas <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">I.G.V. 18% <?php echo $moneda_nombre; ?></td>
            <td style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;"><?php echo $igv; ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: solid 1px #000; border-bottom: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">
                <strong>IMPORTE TOTAL <?php echo $moneda_nombre; ?></strong></td>
            <td style="border-top: solid 1px #000; border-bottom: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><strong><?php echo $total; ?></strong></td>
        </tr>
    </table>
    <hr style="border: none; height: 1px; background-color: #414141; margin-top: 30px;">
    <span style="text-align: center; font-size: 11px;">Representación Impresa de la Factura Electrónica. SURMOTRIZ S.R.L. Autorizado para ser Emisor electrónico mediante Resolución de Intendencia N° 112-005-0000143/SUNAT Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php</span>

<?php
    
    $content = ob_get_clean();
    use Spipu\Html2Pdf\Html2Pdf;
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(8, 8, 8, 8));
    $html2pdf->writeHTML($content);
    $html2pdf->output($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.pdf','F');
    $html2pdf->output('20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.pdf');
    //unlink($ruta.'20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.png');

    /*
    if($cab_doc_gen['CDG_TIP_DOC']=='B'){
        $update = "update cab_doc_gen SET cdg_sun_env='S' WHERE cdg_num_doc='".$cab_doc_gen['CDG_NUM_DOC']."' and cdg_cla_doc='".$cab_doc_gen['CDG_CLA_DOC']."' and cdg_cod_emp='".$cab_doc_gen['CDG_COD_EMP']."' and cdg_cod_gen='".$cab_doc_gen['CDG_COD_GEN']."'";
        $stmt = oci_parse($conn, $update);
        oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stmt);
    }
    */
?>