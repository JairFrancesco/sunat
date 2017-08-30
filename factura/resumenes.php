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