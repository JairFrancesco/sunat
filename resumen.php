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


    $sql_resumen = "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_tip_doc='".$tip."' and cdg_num_doc='".$num."'";
    $sql_parse = oci_parse($conn,$sql_resumen);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $cab_doc_gen, null, null, OCI_FETCHSTATEMENT_BY_ROW); $cab_doc_gen = $cab_doc_gen[0];


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
            <br>
            <a class="btn btn-primary" href="index.php?emp=<?php echo $_GET['emp']; ?>"><span class="glyphicon glyphicon-arrow-left"></span> Regresar</a>
            <a class="btn btn-success" href="factura/resumen_enviar.php?gen=<?php echo $_GET['gen']; ?>&emp=<?php echo $_GET['emp']; ?>&fecha=<?php echo $_GET['fecha']; ?>" target="_blank"> Enviar Resumen</a>
            <a class="btn btn-default" href="./factura/rcomprobacion.php?ticket=201701284687038" target="_blank">Comprobar</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-10">

            <table class="table table-bordered table-stripedd">
                <tr class="well">
                    <th>#Nro</th>
                    <th>Serie</th>
                    <th>Rango</th>
                    <th>Sub Total</th>
                    <th>Total Descuentos</th>
                    <th>Operaciones Gravadas</th>
                    <th>IGV 18%</th>
                    <th class="text-right">Total</th>
                </tr>
                <?php
                    $i = 1;
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
                ?>
            </table>

        </div>
    </div>

    <table>

    </table>


</div>
</body>
</html>