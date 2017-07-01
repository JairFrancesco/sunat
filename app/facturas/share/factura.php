<table class="table table-bordered table-condensed">

    <!-- Titulo  -->

    <tr class="thead-default well">
        <th colspan="7" class="text-center">
            <h3><?php echo $c1;?></h3>
        </th>
    </tr>

    <!-- Fecha y forma de pago -->
    <tr>
        <td colspan="1" width="15%"><?php echo $c2 ?></td>
        <td colspan="2" width="35%">
            <?php
                $time = strtotime($c3 );
                echo date('d-m-Y',$time);
            ?>
        </td>
        <td colspan="1" width="15%"><?php echo $c4 ?></td>
        <td colspan="3" width="35%" class="text-right"><?php echo $c5 ?></td>
    </tr>

    <!-- Nombre cliente o empresa customer - Nombre de la empresa supplier -->
    <tr>
        <td colspan="1"><?php echo $c6 ?></td>
        <td colspan="2"><?php echo $c7 ?></td>
        <td colspan="1"><?php echo $c8 ?></td>
        <td colspan="3" class="text-right"><?php echo $c9 ?></td>
    </tr>

    <!-- Dni o ruc customer - ruc supplier -->
    <tr>
        <td colspan="1"><?php echo $c10 ?></td>
        <td colspan="2"><?php echo $c11 ?></td>
        <td colspan="1"><?php echo $c12 ?></td>
        <td colspan="3" class="text-right"><?php echo $c13 ?></td>
    </tr>

    <!-- Direccion Customer - Direccion Supplier -->
    <tr>
        <td colspan="1"><?php echo $c14 ?></td>
        <td colspan="2"><?php echo $c15 ?></td>
        <td colspan="1"><?php echo $c16 ?></td>
        <td colspan="3" class="text-right"><?php echo $c17 ?></td>
    </tr>

    <!-- Delatalles -->

    <tr class="thead-default well">
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
            echo '<td>'.$det.'</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '</tr>';
        } elseif ($c18 == 'AND') {
            echo '<tr>';
            echo '<td>--</td>';
            echo '<td>'.$det.'</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '</tr>';
        } elseif ($c18 == 'ARAND') {
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
            echo '<td>'.$det.'</td>';
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
        <td colspan="4">Son : <?php echo $leyenda_100; ?></td>
        <td class="well"><?php echo $c36 ?></td>
        <td class="well"><?php echo $c19 ?></td>
        <td class="text-right well"><?php echo $c37 ?></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $c32 ?></td>
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

    <?php if ($anticipo_current != 0) { ?>
        <tr>
            <td colspan="4">
                Documento Anticipo : <?php echo $anticipo_doc; ?>
                Documento Cliente : <?php echo $anticipo_documento; ?>
                ANTICIPO : <?php echo $anticipo_tot; ?> <?php echo $anticipo_moneda; ?>

            </td>
            <td class="well"><?php echo $c39 ?></td>
            <td class="well"><?php echo $c19 ?></td>
            <td class="text-right well"><?php echo $c40 ?></td>
        </tr>
    <?php }else {?>
        <tr>
            <td colspan="4"><?php echo $c38 ?></td>
            <td class="well"><?php echo $c39 ?></td>
            <td class="well"><?php echo $c19 ?></td>
            <td class="text-right well"><?php echo $c40 ?></td>
        </tr>
    <?php } ?>


    <tr class="thead-default well">
        <th colspan="4"><?php echo $c41 ?></th>
        <th><?php echo $c42 ?></th>
        <th><?php echo $c19 ?></th>
        <th class="text-right"><?php echo $c43 ?></th>
    </tr>
</table>