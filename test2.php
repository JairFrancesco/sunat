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
    <script src="https://unpkg.com/vue"></script>

    <style>
        body { padding-top: 30px; padding-bottom: 30px; }
        .theme-dropdown .dropdown-menu { position: static; display: block; margin-bottom: 20px; }
        .theme-showcase > p > .btn { margin: 5px 0; }
        .theme-showcase .navbar .container { width: auto; }
        .pager { margin-top: 0; }
    </style>
</head>
<body>
    <?php
        // conexion
        require("app/coneccion.php");
        require ("app/facturas/share/convertir_a_letras.php");

        // recoge datos de la url
        $gem = $_GET['gen'];
        $emp = $_GET['emp'];
        $num_doc = $_GET['num_doc'];
        $cla_doc = $_GET['cla_doc'];
        $moneda = $_GET['moneda'];
        $co_cr_an = $_GET['co_cr_an'];
        $exi_fra = $_GET['exi_fra'];
        $tip_imp = $_GET['tip_imp'];
        $anu_sn = $_GET['anu_sn'];
        $doc_anu = $_GET['doc_anu'];
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <?php
                if ($emp == '01') {
                    echo '<h1><span class="glyphicon glyphicon-th-list"></span> Tacna <small>Surmotriz</small></h1><br>';
                } elseif ($emp == '02') {
                    echo '<h1><span class="glyphicon glyphicon-th-list"></span> Moquegua <small>Surmotriz</small></h1><br>';
                }
                ?>
            </div>
            <div class="col-lg-6 text-right">
                <a href="index.php?emp=<?php echo $emp; ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Regresar</a>
                <a href="test3.php?gen=02&emp=<?php echo $emp; ?>&num_doc=<?php echo $num_doc ?>&cla_doc=<?php echo $cla_doc ?>&moneda=<?php echo $moneda ?>&co_cr_an=<?php echo $co_cr_an ?>&exi_fra=<?php echo $exi_fra ?>&tip_imp=<?php echo $tip_imp ?>&anu_sn=<?php echo $anu_sn ?>&doc_anu=<?php echo $doc_anu ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-up"></span> Enviar Sunat</a>
                <?php if ($anu_sn == 'S' and $doc_anu == 'S' and ($cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BR' || $cla_doc=='BS') ) { ?>
                    <a href="baja.php?gen=02&emp=01&num_doc=<?php echo $num_doc ?>&cla_doc=<?php echo $cla_doc ?>&moneda=<?php echo $moneda ?>&co_cr_an=<?php echo $co_cr_an ?>&exi_fra=<?php echo $exi_fra ?>&tip_imp=<?php echo $tip_imp ?>&anu_sn=<?php echo $anu_sn ?>&doc_anu=<?php echo $doc_anu ?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Baja Documento</a>
                <?php } ?>
            </div>
            <br>
            <br>
        </div>
    </div>
	<div class="container">
        <?php

            // Tipos de factura
            if ( $cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BS' || $cla_doc=='BR') {
                if ($co_cr_an=='CO' || $co_cr_an=='CR') {
                    if ($tip_imp == 'D') {
                        require("app/facturas/share/variables.php");
                    }elseif($tip_imp=='R'){
                        require("app/facturas/share/variables.php");
                    }
                }elseif($co_cr_an=='AN'){
                    require("app/facturas/share/variables.php");
                    //require ("app/facturas/facturaboletaserviciorepuestocontable_anticipo.php");
                }
            }elseif($cla_doc=='AR' || $cla_doc=='AS'){

                if ($co_cr_an=='CO' || $co_cr_an=='CR') {
                    if ($tip_imp == 'D') {
                        require("app/facturas/share/variables.php");
                        // require ("app/facturas/notacreditorepuestoservicio_cocr_detalle.php");
                    }elseif($tip_imp=='R'){
                        require ("app/facturas/notacreditorepuestoservicio_cocr_resumen.php");
                    }
                }elseif($co_cr_an=='AN'){
                    require("app/facturas/share/variables.php");
                    //echo $c18;
                    //require ("app/facturas/notacreditorepuestoservicio_anticipo.php");
                }
            }
            //print_r($cab);
            require("app/facturas/share/factura.php");
        ?>
	</div>	
</body>
</html>