<table class="table table-bordered table-condensed">

    <!-- Titulo  -->

    <tr class="thead-default info">
        <th colspan="7" class="text-center">
            <h3><?php echo $c1;?></h3>
        </th>
    </tr>

    <!-- Fecha y forma de pago -->
    <tr>
        <td colspan="1" width="15%" class="info"><strong>Fecha</strong></td>
        <td colspan="2" width="35%">
            <?php
                echo date('d-m-Y',strtotime($c3));
            ?>
        </td>
        <?php
        if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
            echo '<td colspan="4" class="text-right"></td>';
        } else {
            echo '<td colspan="1" width="15%" class="info"><strong>Ord. Trab</strong></td>';
            echo '<td colspan="3" width="35%" class="text-left">'.$ord_trab.'</td>';
        }
        ?>
    </tr>

    <!-- Nombre cliente o empresa customer - Nombre de la empresa supplier -->
    <tr>
        <td colspan="1" class="info"><strong>Cliente</strong></td>
        <td colspan="2"><?php echo $c7 ?></td>
        <?php
            if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
                echo '<td colspan="4" class="text-right"></td>';
            } else {
                echo '<td colspan="1" class="info"><strong>Placa/Serie</strong></td>';
                echo '<td colspan="3" class="text-left">'.$placa.'</td>';
            }
        ?>
    </tr>

    <!-- Dni o ruc customer - ruc supplier -->
    <tr>
        <td colspan="1" class="info"><strong><?php echo $c10 ?></strong></td>
        <td colspan="2"><?php echo $c11 ?></td>
        <?php
            if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
                echo '<td colspan="4" class="text-right"></td>';
            } else {
                echo '<td colspan="1" class="info"><strong>Modelo/Año</strong></td>';
                echo '<td colspan="3" class="text-left">'.$modelo_anho.'</td>';
            }
        ?>
    </tr>

    <!-- Direccion Customer - Direccion Supplier -->
    <tr>
        <td colspan="1" class="info"><strong>Direccion</strong></td>
        <td colspan="2"><?php echo $c15 ?></td>
        <?php
            if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
                echo '<td colspan="4" class="text-right"></td>';
            } else {
                echo '<td colspan="1" class="info"><strong>Motor/Chasis</strong></td>';
                echo '<td colspan="3" class="text-left">'.$motor_chasis.'</td>';
            }
        ?>
    </tr>
    <tr>
        <td colspan="1" class="info"><strong>Forma de Pago</strong></td>
        <td colspan="2"><?php echo $c5 ?></td>
        <?php
            if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
                echo '<td colspan="4" class="text-right"></td>';
            } else {
                echo '<td colspan="1" class="info"><strong>Color</strong></td>';
                echo '<td colspan="3" class="text-left">'.$color.'</td>';
            }
        ?>
    </tr>
    <tr>
        <td colspan="1" class="info"><strong>Ubigeo</strong></td>
        <td colspan="2"><?php echo $ubigeo ?></td>
        <?php
            if ($cabezera_tipo == 1 || $cabezera_tipo == 3){
                echo '<td colspan="4" class="text-right"></td>';
            } else {
                echo '<td colspan="1" class="info"><strong>Km</strong></td>';
                echo '<td colspan="3" class="text-left">'.number_format($kilometraje, 0, ',', ',').'</td>';
            }
        ?>



    </tr>

    <!-- Delatalles -->

    <tr class="thead-default info">
        <th>Nº Pieza</th>
        <th>Descripcion</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Importe</th>
        <th>Descuento</th>
        <th>Valor Venta</th>
    </tr>

    <?php
        // si hay detalle

        if ($c18 == 'COCRD') {
            //sort($dets, SORT_FLAG_CASE);
            foreach ($dets as $det ) {
                echo '<tr>';
                echo '<td>'.$det['CODPRODUCTO2'].'</td>';
                echo '<td>'.$det['DESITEM4'].'</td>';
                echo '<td>'.$det['CTDUNIDADITEM1'].'</td>';
                echo '<td>'.$det['MTOVALORUNITARIO5'].'</td>';
                echo '<td>'.$det['MTOPRECIOVENTAITEM11'].'</td>';
                echo '<td>'.$det['MTODSCTOITEM6'].'</td>';
                echo '<td class="text-right">'.$det['MTOVALORVENTAITEM12'].'</td>';
                echo '</tr>';
            }
            // si no hay detalles
        }elseif ($c18 == 'COCRR') {
            echo '<tr>';
            echo '<td>--</td>';
            echo '<td>'.substr(preg_replace('([^A-Za-z0-9/\s\s+])', '', $det),0,200).'</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '</tr>';
        } elseif ($c18 == 'AND') {
            echo '<tr>';
            echo '<td>--</td>';
            echo '<td>'.substr(preg_replace('([^A-Za-z0-9/\s\s+])', '', $det),0,200).'</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '</tr>';
        } elseif ($c18 == 'ARAND') {
            //sort($dets, SORT_FLAG_CASE);
            foreach ($dets as $det ) {
                echo '<tr>';
                echo '<td>'.$det['CODPRODUCTO2'].'</td>';
                echo '<td>'.$det['DESITEM4'].'</td>';
                echo '<td>'.$det['CTDUNIDADITEM1'].'</td>';
                echo '<td>'.$det['MTOVALORUNITARIO5'].'</td>';
                echo '<td>'.$det['MTOPRECIOVENTAITEM11'].'</td>';
                echo '<td>'.$det['MTODSCTOITEM6'].'</td>';
                echo '<td class="text-right">'.$det['MTOVALORVENTAITEM12'].'</td>';
                echo '</tr>';
            }
        } elseif ($c18 == 'NANDR') {
            echo '<tr>';
            echo '<td>--</td>';
            echo '<td>'.substr(preg_replace('([^A-Za-z0-9/\s\s+])', '', $det),0,200).'</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '</tr>';
        }
    ?>

    <!-- Final Cabezera  -->
    <tr>
        <?php
            if ($cab['NOTA'] != ''){
                if ($cab['CDG_CLA_DOC'] != 'BR' && $cab['CDG_CO_CR'] != 'AN'){
                    echo '<td colspan="4">'.$cab['NOTA'].'</td>';
                }else {
                    echo '<td colspan="4"></td>';
                }
            }
        ?>
        <td class="well"><?php echo $c36 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c37 ?></td>
    </tr>
    <tr>
        <td colspan="4">Son : <?php echo $leyenda_100; ?></td>
        <td class="well"><?php echo $c33 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c34 ?></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $c29 ?></td>
        <td class="well"><?php echo $c30 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c31 ?></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $c20 ?></td>
        <td class="well"><?php echo $c21 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c22 ?></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $c23 ?></td>
        <td class="well"><?php echo $c24 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c25 ?></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $c26 ?></td>
        <td class="well"><?php echo $c27 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c28 ?></td>
    </tr>

    <tr>
        <td colspan="4"><?php echo $c38 ?></td>
        <td class="well"><?php echo $c39 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c40 ?></td>
    </tr>


    <?php
        echo '<tr class="thead-default info">';
        if ($anticipo_current != 0) {
            echo '<th colspan="4"> Documento Anticipo :'.$anticipo_doc.' Documento Cliente : '.$anticipo_documento.' ANTICIPO : '.$anticipo_tot.' '.$anticipo_moneda.'</th>';
            echo '<th class="well">'.$c42.'</th>';
            echo '<th class="well">'.$c19.'</th>';
            echo '<th class="text-right well">'.$c43.'</th>';
        }else {
            if ($cabezera_tipo==3){
                // cuando hay documento relacionado con nota de credito
                echo '<th colspan="4">Documento Relacionado : '.$num_doc_ref.' Fecha : '.date('d-m-Y',strtotime($dir_doc_value)).'</th>';
                echo '<th class="well">'.$c42.'</th>';
                echo '<th class="well">'.$c19.'</th>';
                echo '<th class="text-right well">'.$c43.'</th>';
            } else {
                // cuando es una impresion normal
                echo '<th colspan="4">'.$c41.'</th>';
                echo '<th class="well">'.$c42.'</th>';
                echo '<th class="well">'.$c19.'</th>';
                echo '<th class="text-right">'.$c43.'</th>';
            }
        }
        echo '</tr>';
    ?>

</table>