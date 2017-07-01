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
            padding-top: 70px;
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
<?php
if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_final']) && isset($_GET['pagina'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_final = $_GET['fecha_final'];
    $pagina = $_GET['pagina'];

}else{
    $fecha_inicio = 'N';
    $fecha_final = 'N';
    $pagina = 1;
}
?>
<body>
<div class="container">
    <h1>Resumen diaria de Boletas y Notas</h1>
    <a class="btn btn-default" href="resumen.php?h=0&gen=02&emp=01" target="_blank">Hoy</a>
    <a class="btn btn-default" href="resumen.php?h=1&gen=02&emp=01" target="_blank">Hace 1 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=2&gen=02&emp=01" target="_blank">Hace 2 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=3&gen=02&emp=01" target="_blank">Hace 3 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=4&gen=02&emp=01" target="_blank">Hace 4 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=5&gen=02&emp=01" target="_blank">Hace 5 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=6&gen=02&emp=01" target="_blank">Hace 6 Dia</a>
    <a class="btn btn-default" href="resumen.php?h=7&gen=02&emp=01" target="_blank">Hace 7 Dia</a>
</div>

</body>
</html>
<?php //print_r($row2) ?>

