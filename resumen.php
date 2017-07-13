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

require("app/coneccion.php");

$sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_anu_sn !='S' and cdg_doc_anu !='S' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_fec_gen Asc"); oci_execute($sql_boletas);
while($res_boletas = oci_fetch_array($sql_boletas)){ $boletas[] = $res_boletas; }

$sql_notas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_tip_doc, cdg_fec_gen ASC"); oci_execute($sql_notas);
while($res_notas = oci_fetch_array($sql_notas)){ $notas[] = $res_notas; }

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

//print_r($dia);
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
            <a class="btn btn-success" href="resumen_envio.php?h=<?php echo $_GET['h']; ?>&gen=<?php echo $_GET['gen']; ?>&emp=<?php echo $_GET['emp']; ?>"> Enviar Resumen</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-6">

            <table class="table table-bordered table-stripedd">
                <tr>
                    <th>#Nro</th>
                    <th>Serie</th>
                    <th>Rango</th>
                </tr>
                <?php
                    $i = 1;
                    if (isset($bols))
                    {
                        foreach ($bols as $bol) {
                            echo '<tr>';
                            echo '<td>'.$i.'</td>';
                            if ($_GET['emp'] == '01')
                            {
                                echo '<td>'.$bol[2].'</td>';
                            } else
                            {
                                echo '<td>'.$bol[2].'</td>';
                            }

                            echo '<td>['.$bol[0].' - '.$bol[1].']</td>';
                            echo '</tr>';
                            $i++;
                        }
                    }
                    if (isset($nots))
                    {
                        foreach ($nots as $bol) {
                            echo '<tr>';
                            echo '<td>'.$i.'</td>';
                            if ($_GET['emp'] == '01')
                            {
                                echo '<td>'.$bol[2].'</td>';
                            } else
                            {
                                echo '<td>'.$bol[2].'</td>';
                            }

                            echo '<td>['.$bol[0].' - '.$bol[1].']</td>';
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
