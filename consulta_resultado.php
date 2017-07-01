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
    $serie = $_POST['serie'];
    $numero = $_POST['numero'];
    $fecha = $_POST['fecha'];

    $ser = $serie[3];
    $num = ltrim($numero, "0");
    if ($serie[0] == 'F'){
        if ($serie[1] == 'N'){
            $tip = 'A';
            $tipd = '07';
        }elseif ($serie[1] == '0') {
            $tip = 'F';
            $tipd = '01';
        }
    } elseif ($serie[0] == 'B'){
        if ($serie[1] == 'N'){
            $tip = 'A';
            $tipd = '07';
        }elseif ($serie[1] == '0') {
            $tip = 'B';
            $tipd = '03';
        }
    }
    $fecha_partida = explode("-", $fecha);
    $ruta = './app/repo/'.$fecha_partida[2].'/'.$fecha_partida[1].'/'.$fecha_partida[0].'/20532710066-'.$tipd.'-'.$serie.'-'.$numero;
    $nombre = '20532710066-'.$tipd.'-'.$serie.'-'.$numero;

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
            <table class="table table-bordered">
                <tr class="well">
                    <th>#</th>
                    <th>Serie</th>
                    <th>Numero</th>
                    <th>Fecha</th>
                    <th>Nombre Completo</th>
                    <th>PDF</th>
                    <th>XML</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td><?php echo $serie; ?></td>
                    <td><?php echo $numero; ?></td>
                    <td><?php echo $fecha ?></td>
                    <td><?php echo $nombre; ?></td>
                    <td><a href="consulta_descarga.php?file=<?php echo $ruta ?>.pdf&nombre=<?php echo $nombre.'.pdf'; ?>" class="btn btn-default"><span class="glyphicon glyphicon-file"></span> pdf</a></td>
                    <td><a href="consulta_descarga.php?file=<?php echo $ruta ?>.xml&nombre=<?php echo $nombre.'.xml'; ?>" class="btn btn-default"><span class="glyphicon glyphicon-barcode"></span> xml</a></td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <br>
    <a href="consulta.php" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Regresar</a>


</div>
</body>
</html>
