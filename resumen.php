<!DOCTYPE html>
<html>
<head>
    <!-- bootstrap 3 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <!-- datepicker -->
    <link rel="stylesheet" href="datepicker/css/bootstrap-datepicker.css">
    <script src="datepicker/js/bootstrap-datepicker.js"></script>
    <script src="datepicker/locales/bootstrap-datepicker.es.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#datepicker').datepicker({
                format: "dd-mm-yyyy",
                language: "es"
            });
        });
    </script>
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

$hace = $_GET['h'];
$gen = $_GET['gen'];
$emp = $_GET['emp'];
date_default_timezone_set('America/Lima');
$dia = date("d-m-Y", strtotime($_GET['fecha']));
require("app/coneccion.php");
include "factura/__resumen_boleta_notas.php";



    $sql_resumen = "select * from resumenes where to_char(fecha,'yyyy-mm-dd')='".$_GET['fecha']."'";
    $sql_parse = oci_parse($conn,$sql_resumen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $resumenes, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    //print_r($resumenes);
    if(isset($resumenes[0]['CODIGO'])){
        if($resumenes[0]['CODIGO'] == '0'){
            // esta aceptado y comprobado
            $check = 1;
        }elseif($resumenes[0]['CODIGO'] == '0098'){
            //hay resumen pero esta en 0098
            $check = 2;
        }
    }else{
        // no hay resumenes
        $check = 0;
    }

    echo $check;


    if (isset($bols))
    {
        $i = 0;
        foreach ($bols as $bol)
        {
            $sub = 0;
            $desc = 0;
            $grabadas = 0;
            $igv = 0;
            $total = 0;
        $sql_rboletas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_num_doc between ".$bol[0]." and ".$bol[1]." and cdg_tip_doc='B' order by cdg_fec_gen ASC"); oci_execute($sql_rboletas);
            while($res_rboletas = oci_fetch_array($sql_rboletas))
            {
                $sub = $sub +  $res_rboletas['CDG_VVP_TOT'];
                $desc = $desc + $res_rboletas['CDG_DES_TOT'];
                $grabadas = $grabadas + ($res_rboletas['CDG_VVP_TOT'] - $res_rboletas['CDG_DES_TOT']);
                $igv = $igv + $res_rboletas['CDG_IGV_TOT'];
                $total = $total + $res_rboletas['CDG_IMP_NETO'];
            }

            $matriz_boletas[$i][0]= $bol[0];
            $matriz_boletas[$i][1]= $bol[1];
            $matriz_boletas[$i][2]= $bol[2];
            $matriz_boletas[$i][3]= $grabadas;
            $matriz_boletas[$i][4]= $igv;
            $matriz_boletas[$i][5]= $total;
            $matriz_boletas[$i][6]= $desc;
            $matriz_boletas[$i][7]= $sub;
            $i++;
        }
    }




// Notas
if (isset($nots)) {
    foreach ($nots as $not){
        $sub = 0;
        $grabadas = 0;
        $igv = 0;
        $total = 0;
        $desc = 0;
        $sql_rnotas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='" . $gen . "' and cdg_cod_emp='" . $emp . "' and cdg_num_doc between " . $not[0] . " and " . $not[1] . " and cdg_tip_doc='A' order by cdg_fec_gen ASC");
        oci_execute($sql_rnotas);
        while ($res_rnotas = oci_fetch_array($sql_rnotas)) {
            $sub = $sub +  $res_rboletas['CDG_VVP_TOT'];
            $desc = $desc + $res_rboletas['CDG_DES_TOT'];
            $grabadas = $grabadas + ($res_rnotas['CDG_VVP_TOT'] - $res_rnotas['CDG_DES_TOT']);
            $igv = $igv + $res_rnotas['CDG_IGV_TOT'];
            $total = $total + $res_rnotas['CDG_IMP_NETO'];
        }
        $matriz_notas[$i][0]= $not[0];
        $matriz_notas[$i][1]= $not[1];
        $matriz_notas[$i][2]= $not[2];
        $matriz_notas[$i][3]= $grabadas;
        $matriz_notas[$i][4]= $igv;
        $matriz_notas[$i][5]= $total;
        $matriz_notas[$i][6]= $desc;
        $matriz_notas[$i][7]= $sub;
        $i++;

    }
}
//print_r($matriz_boletas);
//print_r($dia);
//echo count($boletas);
?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <?php
                if ($_GET['emp'] == '01')
                {
                    echo '<h1><span class="glyphicon glyphicon-th"></span> Tacna Resumen <small>Boletas</small></h1>';
                } else
                {
                    echo '<h1><span class="glyphicon glyphicon-th"></span> Moquegua Resumen <small>Boletas</small></h1>';
                }
            ?>
        </div>
        <div class="col-lg-6 text-right">
            <br><br>
            <?php
                // no hay nada
                if($check==0){
                    echo '<a class="btn btn-primary" href="factura/resumen_enviar.php?gen='.$_GET["gen"].'&emp='.$_GET["emp"].'&fecha='.$_GET["fecha"].'" target="_blank"> Enviar Resumen</a>';

                // codigo 0
                }elseif($check==1){
                    echo '<a class="btn btn-default" href="./factura/rcomprobacion.php?ticket='.$resumenes[0]['TICKET'].'" target="_blank"><span class="glyphicon glyphicon-refresh"></span> Comprobar</a>';
                // codigo 0098
                }elseif($check==2){
                    echo '<a class="btn btn-success" href="factura/rcomprobacion.php?ticket='.$resumenes[0]['TICKET'].'&op=terminar" target="_blank"> Terminar</a> ';
                    echo '<a class="btn btn-default" href="factura/resumen_enviar.php?gen='.$_GET["gen"].'&emp='.$_GET["emp"].'&fecha='.$_GET["fecha"].'" target="_blank"> Enviar Denuevo</a>';

                }


            ?>
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
                        if (isset($bols))
                        {
                            foreach ($matriz_boletas as $bol) {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<td>'.$bol[2].'</td>';
                                echo '<td>['.$bol[0].' - '.$bol[1].']</td>';
                                echo '<td>'.$bol[7].'</td>';
                                echo '<td>'.$bol[6].'</td>';
                                /*Grabvadas*/echo '<td>'.$bol[3].'</td>';
                                echo '<td>'.$bol[4].'</td>';
                                echo '<td class="text-right">'.$bol[5].'</td>';
                                echo '</tr>';
                                $i++;
                            }
                        }
                        if (isset($nots))
                        {
                            foreach ($matriz_notas as $not) {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<td>'.$not[2].'</td>';
                                echo '<td>['.$not[0].' - '.$not[1].']</td>';
                                echo '<td>'.number_format($not[7], 2, '.', ',').'</td>';
                                echo '<td>'.number_format($not[6], 2, '.', ',').'</td>';
                                /*Grabvadas*/echo '<td>'.number_format($not[3], 2, '.', ',').'</td>';
                                echo '<td>'.$not[4].'</td>';
                                echo '<td class="text-right">'.$not[5].'</td>';
                                echo '</tr>';
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