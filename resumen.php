<!DOCTYPE html>
<html>
<head>
    <!-- bootstrap 3 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>

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

<?php
    /*Auth
    ***************/
    include "factura/layout/__auth.php";

    /*Nav Bar
    **************/
    include "factura/layout/__nav_bar.php";

    /*conexion
    *************/
    require("app/coneccion.php");

    $gen = $_GET['gen'];
    $emp = $_GET['emp'];
    $dia = date("d-m-Y", strtotime($_GET['fecha']));

    date_default_timezone_set('America/Lima');


    include "factura/__resumen.php";

    $sql_resumen = "select * from resumenes where emp='".$emp."' and to_char(fecha,'yyyy-mm-dd')='".$_GET['fecha']."'";
    $sql_parse = oci_parse($conn,$sql_resumen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $resumenes, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    //$chek:  0: no hay resumen, 1: resumen aceptado y comprobado, 2 : resumen hay pero esta en 0098
    if(isset($resumenes[0]['CODIGO'])){
        if($resumenes[0]['CODIGO'] == '0'){
            $check = 1;
        }elseif($resumenes[0]['CODIGO'] == '0098'){
            $check = 2;
        }
    }else{
        $check = 0;
    }

?>

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <?php
                if ($_GET['emp'] == '01'){
                    echo '<h1><span class="glyphicon glyphicon-th"></span> Tacna Resumen <small>Boletas</small></h1>';
                }else {
                    echo '<h1><span class="glyphicon glyphicon-th"></span> Moquegua Resumen <small>Boletas</small></h1>';
                }
            ?>
        </div>
        <div class="col-lg-6 text-right">           
            <br><br>
            <?php
                echo '<a class="btn btn-primary" href="factura/resumenes.php?mes='.date("Y-m", strtotime($_GET['fecha'])).'&emp='.$_GET['emp'].'" target="_blank"> Resumenes Mes</a> ';
                // no hay nada
                if($check==0){
                    echo '<a class="btn btn-primary" href="factura/resumen_enviar.php?gen='.$_GET["gen"].'&emp='.$_GET["emp"].'&fecha='.$_GET["fecha"].'" target="_blank"> Enviar Resumen</a>';

                // codigo 0
                }elseif($check==1){
                    echo '<a class="btn btn-default" href="./factura/rcomprobacion.php?ticket='.$resumenes[0]['TICKET'].'" target="_blank"><span class="glyphicon glyphicon-refresh"></span> Comprobar</a>';
                // codigo 0098
                }elseif($check==2){
                    echo '<a class="btn btn-warning" href="factura/resumen_enviar.php?gen='.$_GET["gen"].'&emp='.$_GET["emp"].'&fecha='.$_GET["fecha"].'" target="_blank"><span class="glyphicon glyphicon-upload"></span> Enviar Resumen</a> ';
                    echo '<a class="btn btn-default" href="./factura/rcomprobacion.php?ticket='.$resumenes[0]['TICKET'].'&op=terminar&gen='.$_GET["gen"].'&emp='.$_GET["emp"].'&fecha='.$_GET["fecha"].'" target="_blank"><span class="glyphicon glyphicon-refresh"></span> Comprobar</a>';
                }
            ?>
        </div>
        <div class="col-lg-12">
            <blockquote>
                <p>0: Proceso Correctamente <br>
                    98: En Proceso <br>
                    99: Proceso con Errores</p>
            </blockquote>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
                <?php
                    $i = 1;
                    if($check==2 || $check==1){ // enviado pero esta en 98 o 0
                        if($check==2){
                            $class = 'warning';
                        }else{
                            $class = 'success';
                        }
                        echo '<table class="table table-bordered table-stripedd">
                            <tr class="'.$class.'">
                                <th>#Nro</th>
                                <th>Fecha</th>
                                <th>Ticket</th>
                                <th>Serie</th>
                                <th>Rango</th>
                                <th>Sub Total</th>
                                <th>Descuentos</th>
                                <th>Gravadas</th>
                                <th>IGV 18%</th>
                                <th class="text-right">Total</th>
                                <th>Codigo</th>
                            </tr>';
                        foreach($resumenes as $resumen){
                            echo '<tr class="'.$class.'">';
                            echo '<td>'.$i.'</td>';
                            echo '<td>'.$resumen['FECHA'].'</td>';
                            echo '<td>'.$resumen['TICKET'].'</td>';
                            echo '<td class="text-right">'.$resumen['SERIE'].'</td>';
                            echo '<td class="text-right">'.$resumen['INICIO'].' - '.$resumen['FINAL'].'</td>';
                            echo '<td class="text-right">'.$resumen['SUBTOTAL'].'</td>';
                            echo '<td class="text-right">'.$resumen['DESCUENTO'].'</td>';
                            echo '<td class="text-right">'.$resumen['GRAVADA'].'</td>';
                            echo '<td class="text-right">'.$resumen['IGV'].'</td>';
                            echo '<td class="text-right">'.$resumen['TOTAL'].'</td>';
                            echo '<td class="text-right">'.$resumen['CODIGO'].'</td>';
                            echo '</tr>';
                            $i++;
                        }
                        echo '</table>';
                    }elseif($check==0){ // no se nevio nada
                        echo '<table class="table table-bordered table-stripedd">
                            <tr class="well">
                                <th>#Nro</th>
                                <th>Serie</th>
                                <th>Rango</th>
                                <th>Sub Total</th>
                                <th>Total Descuentos</th>
                                <th>Operaciones Gravadas</th>
                                <th>IGV 18%</th>
                                <th class="text-right">Total</th>
                            </tr>';
                        if (isset($boletas))
                        {
                            foreach ($boletas as $bol) {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<td>'.$bol['serie'].'</td>';
                                echo '<td>['.$bol['first'].' - '.$bol['last'].']</td>';
                                echo '<td>'.$bol['sub'].'</td>';
                                echo '<td>'.$bol['descuentos'].'</td>';
                                /*Grabvadas*/echo '<td>'.$bol['gravadas'].'</td>';
                                echo '<td>'.$bol['igv'].'</td>';
                                echo '<td class="text-right">'.$bol['total'].'</td>';
                                echo '</tr>';
                                $i++;
                            }
                        }
                        echo '</table>';
                    }

                ?>
        </div>
    </div>

    <table>

    </table>


</div>
</body>
</html>