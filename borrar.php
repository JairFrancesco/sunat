<table class="table table-bordered table-condensed">
    <?php
    // Factura o Boleta
    if ($cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BS' || $cla_doc=='BR'){

        // obtener cabezera
        $sql_dds = "begin PKG_ELECTRONICA.fbc('02','01',".$num_doc.",'".$cla_doc."',:doc); end;";
        $stid = oci_parse($conn,$sql_dds);
        $curs = oci_new_cursor($conn);
        oci_bind_by_name($stid, ":doc", $curs, -1, OCI_B_CURSOR);
        oci_execute($stid);
        oci_execute($curs);

        while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $cabezera = $row;
        }
        ?>
        <!--  Corte para escribir la cabezera  en html -->
        <tr class="thead-default well">
            <th colspan="7" class="text-center">
                <span class="glyphicon glyphicon-list-alt"></span> <?php echo $cabezera['NOMBRE20'];?>
            </th>
        </tr>

        <tr>
            <td colspan="1" width="15%">Fecha</td>
            <td colspan="2" width="35%"><?php echo $cabezera['FECEMISION1'] ?></td>
            <td colspan="1" width="15%">Forma de Pago</td>
            <td colspan="3" width="35%" class="text-right"><?php echo $cabezera['DOC23'] ?></td>
        </tr>
        <tr>
            <td colspan="1">Cliente</td>
            <td colspan="2"><?php echo $cabezera['RZNSOCIALUSUARIO5'] ?></td>
            <td colspan="1">Nombre</td>
            <td colspan="3" class="text-right">Surmotriz S.R.L</td>
        </tr>
        <tr>
            <td colspan="1">
                <?php
                if ($cabezera['TIPDOCUSUARIO3'] == '6') {
                    echo 'RUC';
                }elseif($cabezera['TIPDOCUSUARIO3'] == '1'){
                    echo 'DNI';
                }else{
                    echo 'Carnet';
                }
                ?>

            </td>
            <td colspan="2"><?php echo $cabezera['NUMDOCUSUARIO4'] ?></td>
            <td colspan="1">RUC</td>
            <td colspan="3" class="text-right">20532710066</td>
        </tr>
        <tr>
            <td colspan="1">Direccion</td>
            <td colspan="2"><?php echo $cabezera['DOC24'] ?></td>
            <td colspan="1">Direccion</td>
            <td colspan="3" class="text-right">Av. Leguia Nº 1870</td>
        </tr>
        <!-- Corte para escribir los delatalles -->
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

        // obtener detalle
        if ($co_cr_an=='CO' || $co_cr_an=='CR'){
            if($tip_imp=='D'){
                $sql_dds = "begin PKG_ELECTRONICA.dds('02','01',".$num_doc.",'".$cla_doc."','PEN',:dds); end;";
                $stid_dds = oci_parse($conn,$sql_dds);
                $curs_dds = oci_new_cursor($conn);
                oci_bind_by_name($stid_dds, ":dds", $curs_dds, -1, OCI_B_CURSOR);
                oci_execute($stid_dds);
                oci_execute($curs_dds);
                while (($row_dds = oci_fetch_array($curs_dds, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                    $detalles[] = $row_dds;//print_r($row_dds);
                }
                ?>
                <?php foreach ($detalles as $detalle ) { ?>
                    <tr>
                        <td><?php echo $detalle['CODPRODUCTO2'] ?></td>
                        <td><?php echo $detalle['DESITEM4'] ?></td>
                        <td><?php echo $detalle['CTDUNIDADITEM1'] ?></td>
                        <td><?php echo $detalle['MTOVALORUNITARIO5'] ?></td>
                        <td><?php echo $detalle['MTOPRECIOVENTAITEM11'] ?></td>
                        <td><?php echo $detalle['MTODSCTOITEM6'] ?></td>
                        <td><?php echo $detalle['MTOVALORVENTAITEM12'] ?></td>
                    </tr>
                <?php } ?>


                <?php

            }elseif($tip_imp=='R'){
                $sql_factura_detalle_resumen = "select cdg_ten_res from cab_doc_gen where cdg_num_doc=16183 and cdg_cod_gen=02 and cdg_cod_emp=01 and cdg_cla_doc='FS'";
                $factura_detalle_resumen = oci_parse($conn, $sql_factura_detalle_resumen);
                oci_execute($factura_detalle_resumen);
                while ($fila_factura_detalle_resumen = oci_fetch_array($factura_detalle_resumen, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $fila_factura_detalle_resumen_detalle = $fila_factura_detalle_resumen['CDG_TEN_RES'];
                }
                ?>
                <tr>
                    <td>--</td>
                    <td><?php echo $fila_factura_detalle_resumen_detalle ?></td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                </tr>
                <?php
            }

            // Descripcion boleta o factura con anticipo Detalle
        }elseif($co_cr_an=='AN'){
            $sql_boleta_anticipo_descriptcion = "select cdg_not_001, cdg_not_003 from cab_doc_gen where cdg_num_doc=12811 and cdg_cod_gen=02 and cdg_cod_emp=01 and cdg_cla_doc='BR'";
            $boleta_anticipo_descriptcion = oci_parse($conn, $sql_boleta_anticipo_descriptcion);
            oci_execute($boleta_anticipo_descriptcion);
            while ($boleta_anticipo_descriptcion_detalle = oci_fetch_array($boleta_anticipo_descriptcion, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $fila_boleta_anticipo_descriptcion_detalle = $boleta_anticipo_descriptcion_detalle['CDG_NOT_001'].' <br>'.$boleta_anticipo_descriptcion_detalle['CDG_NOT_003'];
            }
            ?>
            <tr>
                <td>--</td>
                <td><?php echo $fila_boleta_anticipo_descriptcion_detalle ?></td>
                <td>--</td>
                <td>--</td>
                <td>--</td>
                <td>--</td>
                <td>--</td>
            </tr>
            <?php

        }
        ?>
        <!-- Terminacion de la cabezera  -->
        <tr>
            <td colspan="4"></td>
            <td>OP. GRATUITAS</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right">0.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. EXONERADA</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right"><?php echo $cabezera['MTOOPEREXONERADAS12'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. INAFECTA</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right"><?php echo $cabezera['MTOOPERINAFECTAS11'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. GRAVADA</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right"><?php echo $cabezera['MTOOPERGRAVADAS10'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>TOTAL. DSCTO</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right"><?php echo $cabezera['MTODESCUENTOS9'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>I.S.C.</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right">0.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>I.G.V.</td>
            <td><?php echo $cabezera['TIPMONEDA6'] ?></td>
            <td class="text-right"><?php echo $cabezera['MTOIGV13'] ?></td>
        </tr>
        <tr class="thead-default well">
            <th colspan="4"></th>
            <th>IMPORTE TOTAL</th>
            <th><?php echo $cabezera['TIPMONEDA6'] ?></th>
            <th class="text-right"><?php echo $cabezera['MTOIMPVENTA16'] ?></th>
        </tr>

        <?php

        // Nota de Credito
    }elseif($cla_doc=='AR' || $cla_doc=='AS'){

        //echo 'Nota Credito ncc';

        // obtener cabezera de nota de credito

        $sql_ncc_cab = "begin PKG_ELECTRONICA.ncc('02','01',".$num_doc.",:doc); end;";
        $stid_ncc = oci_parse($conn,$sql_ncc_cab);
        $curs_ncc = oci_new_cursor($conn);
        oci_bind_by_name($stid_ncc, ":doc", $curs_ncc, -1, OCI_B_CURSOR);
        oci_execute($stid_ncc);
        oci_execute($curs_ncc);

        while (($row_ncc = oci_fetch_array($curs_ncc, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $cabezera_ncc = $row_ncc;
        }
        //print_r($cabezera_ncc);
        ?>

        <!-- Cabezera de la nota de credito -->
        <tr class="well">
            <th colspan="7" class="text-center">
                <span class="glyphicon glyphicon-list-alt"></span> <?php echo $cabezera_ncc['NOMBRE20'];?>
            </th>
        </tr>
        <tr>
            <td colspan="1" width="15%">Fecha</td>
            <td colspan="2" width="35%"><?php echo $cabezera_ncc['FECEMISION0'] ?></td>
            <td colspan="1" width="15%">Forma de Pago</td>
            <td colspan="3" width="35%" class="text-right"><?php echo $cabezera_ncc['CDG_CO_CR'] ?></td>
        </tr>
        <tr>
            <td colspan="1">Cliente</td>
            <td colspan="2"><?php echo $cabezera_ncc['RZNSOCIALUSUARIO7'] ?></td>
            <td colspan="1">Nombre</td>
            <td colspan="3" class="text-right">Surmotriz S.R.L</td>
        </tr>
        <tr>
            <td colspan="1">
                <?php
                if ($cabezera_ncc['TIPDOCUSUARIO5'] == '6') {
                    echo 'RUC';
                }elseif($cabezera_ncc['TIPDOCUSUARIO5'] == '1'){
                    echo 'DNI';
                }else{
                    echo 'Carnet Extra';
                }
                ?>

            </td>
            <td colspan="2"><?php echo $cabezera_ncc['NUMDOCUSUARIO6'] ?></td>
            <td colspan="1">RUC</td>
            <td colspan="3" class="text-right">20532710066</td>
        </tr>
        <tr>
            <td colspan="1">Direccion</td>
            <td colspan="2"><?php echo $cabezera_ncc['CDG_DIR_CLI'] ?></td>
            <td colspan="1">Modifica</td>
            <td colspan="3" class="text-right"><?php echo $cabezera_ncc['NUMDOCUSUARIO4'] ?></td>
        </tr>
        <tr class="well">
            <th>Nº Pieza</th>
            <th>Descripcion</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Importe</th>
            <th>Descuento</th>
            <th>Valor Venta</th>
        </tr>


        <?php
        // se genera detalle
        if ($co_cr_an=='CO' || $co_cr_an=='CR') {
            if($tip_imp=='D'){
                $sql_dds = "begin PKG_ELECTRONICA.dds('02','01',".$num_doc.",'".$cla_doc."','PEN',:dds); end;";
                $stid_dds = oci_parse($conn,$sql_dds);
                $curs_dds = oci_new_cursor($conn);
                oci_bind_by_name($stid_dds, ":dds", $curs_dds, -1, OCI_B_CURSOR);
                oci_execute($stid_dds);
                oci_execute($curs_dds);
                while (($row_dds = oci_fetch_array($curs_dds, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                    $detalles[] = $row_dds;//print_r($row_dds);
                }
                // print_r($detalles);
                ?>

                <!-- Corte para escribir los delatalles -->
                <?php foreach ($detalles as $detalle ) { ?>
                    <tr>
                        <td><?php echo $detalle['CODPRODUCTO2'] ?></td>
                        <td><?php echo $detalle['DESITEM4'] ?></td>
                        <td><?php echo $detalle['CTDUNIDADITEM1'] ?></td>
                        <td><?php echo $detalle['MTOVALORUNITARIO5'] ?></td>
                        <td><?php echo $detalle['MTOPRECIOVENTAITEM11'] ?></td>
                        <td><?php echo $detalle['MTODSCTOITEM6'] ?></td>
                        <td><?php echo $detalle['MTOVALORVENTAITEM12'] ?></td>
                    </tr>
                <?php } ?>

                <?php



            }elseif($tip_imp=='R'){

            }
        }elseif($co_cr_an=='AN'){
            $sql_fecha_doc_modifica = "select b.cdg_fec_gen from cab_doc_gen a inner join cab_doc_gen b 
on b.cdg_num_doc=a.CDG_DOC_REF and b.cdg_cla_doc=a.cdg_tip_ref where a.cdg_num_doc=2984 and a.cdg_cod_gen=02 and a.cdg_cod_emp=01 and a.cdg_cla_doc='AS'";
            $fecha_doc_modifica = oci_parse($conn, $sql_fecha_doc_modifica);
            oci_execute($fecha_doc_modifica);
            while ($filas = oci_fetch_array($fecha_doc_modifica, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $fecha = $filas['CDG_FEC_GEN'];
            }
            ?>
            <tr>
                <td class="text-center">--</td>
                <td><?php echo $descriptcion_nota_credito_anticipo ?></td>
                <td class="text-center">--</td>
                <td class="text-center">--</td>
                <td class="text-center">--</td>
                <td class="text-center">--</td>
                <td class="text-center">--</td>
            </tr>
            <?php
        }

        // parte final de la cabezera
        ?>
        <tr>
            <td colspan="4"></td>
            <td>OP. GRATUITAS</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right">0.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. EXONERADA</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right"><?php echo $cabezera_ncc['MTOOPEREXONERADAS12'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. INAFECTA</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right"><?php echo $cabezera_ncc['MTOOPERINAFECTAS11'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>OP. GRAVADA</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right"><?php echo $cabezera_ncc['MTOOPERGRAVADAS10'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>TOTAL. DSCTO</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right"><?php echo $cabezera_ncc['SUMOTROSCARGOS9'] ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>I.S.C.</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right">0.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>I.G.V.</td>
            <td><?php echo $cabezera_ncc['TIPMONEDA8'] ?></td>
            <td class="text-right"><?php echo $cabezera_ncc['MTOIGV13'] ?></td>
        </tr>
        <tr class="thead-default">
            <th colspan="4"></th>
            <th>IMPORTE TOTAL</th>
            <th><?php echo $cabezera_ncc['TIPMONEDA8'] ?></th>
            <th class="text-right"><?php echo $cabezera_ncc['MTOIMPVENTA16'] ?></th>
        </tr>
        <?php
    }
    ?>






</table>