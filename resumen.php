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
//echo $dia;
/*
if ($hace == 0){
    $dia = date('d-m-Y');
    //$dia = '27-06-2017';     
}elseif ($hace == 1){
    //$fecha = date('d-m-Y');
    $dia = date("d-m-Y", strtotime("$fecha -1 day"));
    //$dia = '12-04-2017';
}elseif ($hace == 2){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -2 day"));
    $dia = '11-04-2017';
}elseif ($hace == 3){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -3 day"));
    $dia = '10-04-2017';
}elseif ($hace == 4){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -4 day"));
    $dia = '09-04-2017';
}elseif ($hace == 5){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -5 day"));
    $dia = '08-04-2017';
}elseif ($hace == 6){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -6 day"));
    $dia = '07-04-2017';
}elseif ($hace == 7){
    $fecha = date('d-m-Y');
    //$dia = date("d-m-Y", strtotime("$fecha -7 day"));
    $dia = '06-04-2017';
}
*/
require("app/coneccion.php");

$sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_anu_sn !='S' and cdg_doc_anu !='S' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_num_doc Asc"); oci_execute($sql_boletas);
while($res_boletas = oci_fetch_array($sql_boletas)){
    //echo $res_boletas['CDG_NUM_DOC'].' '.$res_boletas['CDG_FEC_GEN'].'<br>';
    $boletas[] = $res_boletas;
}



$sql_notas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_num_doc ASC"); oci_execute($sql_notas);
while($res_notas = oci_fetch_array($sql_notas)){ $notas[] = $res_notas; }

//echo $res_notas['CDG_NUM_DOC'].' '.$res_notas['CDG_FEC_GEN'].'<br>';

if ($emp == '01')
{
    $serie_boleta = 'B001';
    $serie_nota = 'BN03';
} else
{
    $serie_boleta = 'B004';
    $serie_nota = 'BN04';
}

$ant = 0;
$i = 0;
$h = 0;

if (isset($boletas)){
    foreach ( $boletas as $boleta ){
        if ($i==0){
            $bols[$h][0] = $boleta['CDG_NUM_DOC'];
            $ant = $boleta['CDG_NUM_DOC'];
            $i++;
            if (count($boletas)==1){
                $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                $bols[$h][2] = $serie_boleta;
            }
        }else {
            if (($ant+1) == $boleta['CDG_NUM_DOC']){
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $boleta['CDG_NUM_DOC']){
                        $bols[$h][1] = $ant;
                        $bols[$h][2] = $serie_boleta;
                        $h++;
                        $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }else {
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $boleta['CDG_NUM_DOC'];
                }
            }else {
                $bols[$h][1] = $ant;
                $bols[$h][2] = $serie_boleta;
                $h++;
                $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                $ant = $boleta['CDG_NUM_DOC'];
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                    $bols[$h][2] = $serie_boleta;
                }

            }
        }
    }
}

$ant = 0;
$i = 0;
$h = 0;
if (isset($notas)){
    foreach ( $notas as $nota ){
        if ($i==0){
            $nots[$h][0] = $nota['CDG_NUM_DOC'];
            $ant = $nota['CDG_NUM_DOC'];
            $i++;
            if (count($notas)==1){
                $nots[$h][1] = $nota['CDG_NUM_DOC'];
                $nots[$h][2] = $serie_nota;
            }
        }else {
            if (($ant+1) == $nota['CDG_NUM_DOC']){
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $nota['CDG_NUM_DOC']){
                        $nots[$h][1] = $ant;
                        $nots[$h][2] = $serie_nota;
                        $h++;
                        $nots[$h][0] = $nota['CDG_NUM_DOC'];
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }else {
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $nota['CDG_NUM_DOC'];
                }
            }else {
                $nots[$h][1] = $ant;
                $nots[$h][2] = $serie_nota;
                $h++;
                $nots[$h][0] = $nota['CDG_NUM_DOC'];
                $ant = $nota['CDG_NUM_DOC'];
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    $nots[$h][1] = $nota['CDG_NUM_DOC'];
                    $nots[$h][2] = $serie_nota;
                }

            }
        }
    }
}

//print_r($nots);


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
            <a class="btn btn-success" href="resumen_envio.php?gen=<?php echo $_GET['gen']; ?>&emp=<?php echo $_GET['emp']; ?>&fecha=<?php echo $_GET['fecha']; ?>"> Enviar Resumen</a>
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
