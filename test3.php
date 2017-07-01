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


    // Tipos de factura
    if ( $cla_doc=='FS' || $cla_doc=='FR' || $cla_doc=='FC' || $cla_doc=='BS' || $cla_doc=='BR') {
        if ($co_cr_an=='CO' || $co_cr_an=='CR') {
            if ($tip_imp == 'D') {
                require("app/facturas/share/variables.php"); // genera las variables
                require("app/facturas/share/xml.php");
                require("app/facturas/share/pdf.php"); // genera el xml
            }elseif($tip_imp=='R'){
                require("app/facturas/share/variables.php");
                require("app/facturas/share/xml.php");
                require("app/facturas/share/pdf.php"); // genera el xml
            }
        }elseif($co_cr_an=='AN'){
            require("app/facturas/share/variables.php");
            require("app/facturas/share/xml.php");
            require("app/facturas/share/pdf.php"); // genera el xml
        }
    }elseif($cla_doc=='AR' || $cla_doc=='AS'){
        if ($co_cr_an=='CO' || $co_cr_an=='CR') {
            if ($tip_imp == 'D') {
                require ("app/facturas/share/variables.php");
                require ("app/facturas/share/xml_nota.php");
                require ("app/facturas/share/pdf_nota.php");
            }elseif($tip_imp=='R'){
                require ("app/facturas/share/variables.php");
                require ("app/facturas/share/xml_nota.php");
                require ("app/facturas/share/pdf_nota.php");
            }
        }elseif($co_cr_an=='AN'){
            require ("app/facturas/share/variables.php");
            require ("app/facturas/share/xml_nota.php");  
            require ("app/facturas/share/pdf_nota.php");          
        }
    }