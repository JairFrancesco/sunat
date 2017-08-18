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
            padding-top: 120px;
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
    require "app/coneccion.php";
    $serie = strtoupper($_POST['serie']);
    $numero = $_POST['numero'];
    $fecha = $_POST['fecha'];

    $ser = $serie[3];
    $num = ltrim($numero, "0");
    if ($serie[0] == 'F' || $serie[0] == 'f'){
        if ($serie[1] == 'N' || $serie[1] == 'n'){
            $tip = 'A';
            $tipd = '07';
        }elseif ($serie[1] == '0') {
            $tip = 'F';
            $tipd = '01';
        }
    } elseif ($serie[0] == 'B' || $serie[0] == 'b'){
        if ($serie[1] == 'N' || $serie[1] == 'n'){
            $tip = 'A';
            $tipd = '07';
        }elseif ($serie[1] == '0') {
            $tip = 'B';
            $tipd = '03';
        }
    }
    $fecha_partida = explode("-", $fecha);
    $ruta = './app/repo/'.$fecha_partida[2].'/'.$fecha_partida[1].'/'.$fecha_partida[0].'/';
    $nombre = '20532710066-'.$tipd.'-'.$serie.'-'.$numero;

    // comprobacion del pdf
    if (file_exists($ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.pdf')) {
        $pdf= $ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.pdf';
        $pdf_nombre = '20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.pdf';
    }else {
        $pdf = '';
        $pdf_nombre = '';
    }

    // comprobacion del xml
    if (file_exists($ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml')) {
        $xml= $ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml';
        $xml_nombre = '20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml';
    }elseif(file_exists($ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip')){
        $xml= $ruta.'20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip';
        $xml_nombre = '20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip';
    }else{
        $xml = '';
        $xml_nombre = '';
    }

    // comprobacion del cdr
    if (file_exists($ruta.'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml')) {
        $cdr = $ruta.'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml';
        $cdr_nombre = 'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.xml';
    }elseif(file_exists($ruta.'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip')){
        $cdr = $ruta.'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip';
        $cdr_nombre = 'R-20532710066-'.$tipd.'-'.$serie.'-'.$numero.'.zip';
    }else{
        $cdr = '';
        $cdr_nombre = '';
    }
    //$sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_anu_sn !='S' and cdg_doc_anu !='S' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_fec_gen Asc"); oci_execute($sql_boletas);
    //while($res_boletas = oci_fetch_array($sql_boletas)){ $boletas[] = $res_boletas; }
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <h2>Consultar Factura <small>Surmotriz</small></h2>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <?php
                if($pdf != '' && $xml != '' && $cdr != ''){
                    echo '<table class="table table-bordered">';
                    echo '<tr class="well">';
                    echo '<th>#</th>';
                    echo '<th>Serie</th>';
                    echo '<th>Numero</th>';
                    echo '<th>Fecha</th>';
                    echo '<th>Nombre Completo</th>';
                    echo '<th>PDF</th>';
                    echo '<th>XML</th>';
                    echo '<th>CDR</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>1</td>';
                    echo '<td>'.$serie.'</td>';
                    echo '<td>'.$numero.'</td>';
                    echo '<td>'.$fecha.'</td>';
                    echo '<td>'.$nombre.'</td>';
                    echo '<td><a href="consulta_descarga.php?file='.$pdf.'&nombre='.$pdf_nombre.'" class="btn btn-default"><span class="glyphicon glyphicon-file"></span> PDF</a></td>';
                    echo '<td><a href="consulta_descarga.php?file='.$xml.'&nombre='.$xml_nombre.'" class="btn btn-default"><span class="glyphicon glyphicon-barcode"></span> XML</a></td>';
                    echo '<td><a href="consulta_descarga.php?file='.$cdr.'&nombre='.$cdr_nombre.'" class="btn btn-default"><span class="glyphicon glyphicon-barcode"></span> CDR</a></td>';
                    echo '</tr>';
                    echo '</table>';
                }else{
                    echo 'Sucedio un error, uno de los documentos no esta disponible por favor informar a sistemas@surmotriz.com';
                }
            ?>
        </div>
    </div>
    <br>
    <br>
    <a href="consulta.php" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Regresar</a>
</div>
</body>
</html>
