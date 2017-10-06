<style>
    body {
        padding-top: 60px;
        padding-bottom: 30px;
    }
</style>
<?php
    date_default_timezone_set('America/Lima');

?>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/sunat/index.php?emp=<?php echo $_SESSION['emp'] ?>">Surmotriz S.R.L</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/sunat/factura/index.php" target="_blank">Nuevo</a></li>
                <li><a href="/sunat/resumen.php?gen=<?php echo $_SESSION['gen'] ?>&emp=<?php echo $_SESSION['emp'] ?>&fecha=<?php echo date("Y-m-d")?>">Resumen Dia (<?php echo date("d-m-Y")?>)</a></li>
                <li><a href="/sunat/factura/resumenes.php?mes=<?php echo date("Y-m") ?>&emp=<?php echo $_SESSION['emp'] ?>">Resumen Mensual (<?php echo date("m-Y") ?>)</a></li>
                <li><a href="/sunat/factura/tareas.php">Tareas</a></li>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
                -->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a><span class="glyphicon glyphicon-lock"></span> <?php echo $_SESSION['user'];?></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>