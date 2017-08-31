<!DOCTYPE html>
<html>
<head>
    <!-- bootstrap 3 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <!-- datepicker -->
    <link rel="stylesheet" href="../datepicker/css/bootstrap-datepicker.css">    
    <style>
        body {
            padding-top: 10px;
            padding-bottom: 30px;
        }

        .theme-dropdown .dropdown-menu {
            position: static;
            display: block;
            margin-bottom: 20px;
        }

        .theme-showcase > p > .btn {
            margin: 5px 0;
        }

        .theme-showcase .navbar .container {
            width: auto;
        }
        .pager {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

<?php
    require("conexion.php");
    date_default_timezone_set('America/Lima');
    echo '<h1>Resumen Mes '.date('F Y', strtotime('01-08-2017')).'</h1>';
    $inicio_mes = '01';
    $mes = substr($_GET['mes'],5,2);
    $anho = substr($_GET['mes'],0,4);
    $fin_mes = date("d",(mktime(0,0,0,substr($_GET['mes'],5,2)+1,1,substr($_GET['mes'],0,4))-1));
    echo '<table class="table table-striped table-bordered table-condensed">';
    echo '<tr>';
    echo '<td>Dia</td>';
    echo '<td>&nbsp;&nbsp;F | &nbsp;B &nbsp;| &nbsp;A | &nbsp;E</td>';
    echo '<td>Resumen</td>';
    echo '<td>Total Saes</td>';
    echo '<td>Total FE</td>';
    echo '<td>Faltan Enviar</td>';
    echo '<td>Acciones</td>';
    echo '</tr>';
    for($i=$inicio_mes;$i<=$fin_mes;$i++){
        $fecha_documentos = str_pad($i,2,'0',STR_PAD_LEFT).'-'.$mes.'-'.$anho;
        $total_sf = 0; //total saes facturas
        $total_ef = 0;
        $total_sb = 0;
        $total_eb = 0;
        $total_sa = 0;
        $total_ea = 0;
        $total_s = 0;
        $total_e = 0;
        $can_F = 0;
        $can_B = 0;
        $can_A = 0;
        $can_E = 0;
        $can_T = 0;
        $can_SNT = 0;
        $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='02' and cdg_cod_emp='".$_GET['emp']."' and to_char(cdg_fec_gen,'dd-mm-yyyy')='".$fecha_documentos."'";
        $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
        oci_execute($sql_parse);
        oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($documentos as $documento) {
            //docuementos totals
            $can_T = $can_T + 1;
            if ($documento['CDG_COD_SNT'] == '0001') {
                //enviados
                $can_SNT = $can_SNT + 1;
            }
            if($documento['CDG_TIP_DOC'] == 'F'){
                $can_F = $can_F +1;
                if($documento['CDG_ANU_SN']=='S' && $documento['CDG_DOC_ANU']=='S'){
                    $can_E = $can_E +1;
                }
            }elseif($documento['CDG_TIP_DOC'] == 'B'){
                $can_B = $can_B +1;
            }elseif($documento['CDG_TIP_DOC'] == 'A'){
                $can_A = $can_A +1;
            }
            // sacamos los eliminados
            if($documento['CDG_ANU_SN']!='S' && $documento['CDG_DOC_ANU']!='S'){
                //facturas
                if($documento['CDG_TIP_DOC'] == 'F'){
                    $total_sf = $total_sf + $documento['CDG_IMP_NETO'];
                    if($documento['CDG_COD_SNT'] == '0001'){
                        $total_ef = $total_ef + $documento['CDG_IMP_NETO'];
                    }
                }
                //boletas
                if($documento['CDG_TIP_DOC'] == 'B'){
                    $total_sb = $total_sb + $documento['CDG_IMP_NETO'];
                    if($documento['CDG_COD_SNT'] == '0001'){
                        $total_eb = $total_eb + $documento['CDG_IMP_NETO'];
                    }
                }
                //notas                            
                if($documento['CDG_TIP_DOC'] == 'A'){
                    $total_sa = $total_sa + $documento['CDG_IMP_NETO'];
                    if($documento['CDG_COD_SNT'] == '0001'){
                        $total_ea = $total_ea + $documento['CDG_IMP_NETO'];
                    }
                }
                //totales
                $total_s = $total_s + $documento['CDG_IMP_NETO'];
                if($documento['CDG_COD_SNT'] == '0001'){
                    $total_e = $total_e + $documento['CDG_IMP_NETO'];
                }
            }
        }
        echo '<tr>';
        echo '<td style="padding:0px;">'.str_pad($i,2,'0',STR_PAD_LEFT).'</td>';
        echo '<td>'.str_pad($can_F,2,'0',STR_PAD_LEFT).' | '.str_pad($can_B,2,'0',STR_PAD_LEFT).' | '.str_pad($can_A,2,'0',STR_PAD_LEFT).' | '.str_pad($can_E,2,'0',STR_PAD_LEFT).'</td>';
        echo '<td>'.$fecha_documentos.'</td>';
        echo '<td>'.$total_s.'</td>';
        echo '<td>'.number_format($total_e,2,'.','').'</td>';
        echo '<td>'.str_pad(($can_T-$can_SNT),2,'0',STR_PAD_LEFT).'</td>';
        echo '<td></td>';
        echo '</tr>';
    }
    echo '<tr>';
    echo '<tb></tb>';
    echo '</tr>';
    echo '</table>';
    
    //$fecha = $_GET['mes'];
    //$month=substr($_GET['mes'],5,2);
    
    //echo date("d",(mktime(0,0,0,$month+1,1,$year)-1));

    require ('conexion.php');
    $sql_resumenes = "select * from resumenes WHERE to_char(fecha,'yyyy-mm')='".$_GET['mes']."' and emp='".$_GET['emp']."' order by fecha Desc";
    $sql_parse = oci_parse($conn,$sql_resumenes);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $resumenes, null, null, OCI_FETCHSTATEMENT_BY_ROW); 
    $i=1;
    if($_GET['emp']=='01'){
        $total_B001 = 0;
        $total_BN03 = 0;
        $local = 'Tacna';
    }else{
        $total_B004 = 0;
        $total_BN04 = 0;
        $local = 'Moquegua';
    }
?>
                <h1>Resumen <?php echo $local; ?> <small>mes <?php echo $_GET['mes']; ?></small></h1>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Ticket</th>
                        <th>Serie</th>
                        <th>Inicio</th>
                        <th>Final</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Gravada</th>
                        <th>IGV</th>
                        <th>TOTAL</th>
                        <th>Codigo</th>                        
                    </tr>
                    <?php                        
                        foreach ($resumenes as $resumen) {
                            echo '<tr>';
                            echo '<td>'.$i.'</td>';                            
                            echo '<td>'.$resumen['FECHA'].'</td>';
                            echo '<td>'.$resumen['TICKET'].'</td>';
                            echo '<td>'.$resumen['SERIE'].'</td>';
                            echo '<td>'.$resumen['INICIO'].'</td>';                            
                            echo '<td>'.$resumen['FINAL'].'</td>';                            
                            echo '<td>'.$resumen['SUBTOTAL'].'</td>';                            
                            echo '<td>'.$resumen['DESCUENTO'].'</td>';                           
                            echo '<td>'.$resumen['GRAVADA'].'</td>';                            
                            echo '<td>'.$resumen['IGV'].'</td>';                            
                            echo '<td>'.$resumen['TOTAL'].'</td>';                            
                            echo '<td>'.$resumen['CODIGO'].'</td>';
                            echo '</tr>';
                            $i++;

                            if($_GET['emp']=='01'){
                                if($resumen['SERIE']=='B001'){
                                    $total_B001 = $total_B001 + $resumen['TOTAL'];
                                }elseif($resumen['SERIE']=='BN03') {
                                    $total_BN03 = $total_BN03 + $resumen['TOTAL'];    
                                }                                
                            }else{
                                if($resumen['SERIE']=='B004'){
                                    $total_B004 = $total_B004 + $resumen['TOTAL'];
                                }elseif($resumen['SERIE']=='BN04') {
                                    $total_BN04 = $total_BN04 + $resumen['TOTAL'];
                                }                                
                            }
                        }                        
                    ?>
                </table>
            </div>
            <div class="col-lg-6">
                <h2>Totales por Serie <?php echo $local; ?></h2>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Serie</th>
                        <th>Total Sumatoria</th>
                    </tr>
                    <?php
                        echo '<tr>';
                        echo '<td>1</td>';
                        echo '<td>Boletas</td>';
                        if($_GET['emp']=='01'){
                            echo '<td>B001</td>';
                            echo '<td>'.$total_B001.'</td>';
                        }else{
                            echo '<td>B004</td>';
                            echo '<td>'.$total_B004.'</td>';
                        }
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>2</td>';
                        echo '<td>Notas de Credito</td>';
                        if($_GET['emp']=='01'){
                            echo '<td>BN01</td>';
                            echo '<td>'.number_format($total_BN03,2,'.','').'</td>';
                        }else{
                            echo '<td>BN04</td>';
                            echo '<td>'.$total_BN04.'</td>';
                        }
                        echo '</tr>';
                    ?>
                </table>    
            </div>            
        </div>
    </div>

</body>
</html>