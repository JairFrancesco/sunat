<!DOCTYPE html>
<html>
<head>
    <!-- bootstrap 3 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <!-- datepicker -->
    <link rel="stylesheet" href="../datepicker/css/bootstrap-datepicker.css">    
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
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

<?php
    require("conexion.php");
    date_default_timezone_set('America/Lima');
    echo '<h1>Resumen Mes '.date('F Y', strtotime('01-08-2017')).'</h1>';
    $emp = $_GET['emp'];
    $inicio_mes = '01';
    $mes = substr($_GET['mes'],5,2);
    $anho = substr($_GET['mes'],0,4);
    $fin_mes = date("d",(mktime(0,0,0,substr($_GET['mes'],5,2)+1,1,substr($_GET['mes'],0,4))-1));
    $sql_resumen_item = "select * from resumenes where emp='".$emp."' and to_char(fecha,'mm-yyyy')='08-2017'";
    $sql_parse_item = oci_parse($conn,$sql_resumen_item);
    oci_execute($sql_parse_item);
    oci_fetch_all($sql_parse_item, $resumen_items, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    //print_r($resumen_items);
    //echo date('d-m-Y', strtotime($resumen_items[0]['FECHA']));

    echo '<table class="table table-striped table-bordered table-condensed">';
    echo '<tr>';
    echo '<td class="text-center well"><strong>Dia</strong></td>';
    echo '<td class="well"><strong>&nbsp;&nbsp;F | &nbsp;B &nbsp;| &nbsp;A | &nbsp;E &nbsp;| C</strong></td>';
    echo '<td class="text-center well"><strong>Facturas</strong></td>';
    echo '<td class="text-center well"><strong>Boletas</strong></td>';
    echo '<td class="text-center well"><strong>Notas</strong></td>';
    echo '<td><strong>Total Saes</strong></td>';
    echo '<td><strong>Total FE</strong></td>';
    echo '<td><strong>Resumen</strong></td>';
    echo '<td><strong>Acciones</strong></td>';
    echo '</tr>';
    for($i=$inicio_mes;$i<=$fin_mes;$i++){
        $fecha_documentos = str_pad($i,2,'0',STR_PAD_LEFT).'-'.$mes.'-'.$anho;
        $total_sf = 0; //total saes facturas
        $total_ef = 0;
        $total_sb = 0;
        $total_eb = 0;
        $total_sa = 0;
        $total_ea = 0;
        $total_s = 0;
        $total_e = 0;
        $can_F = 0;
        $can_B = 0;
        $can_A = 0;
        $can_E = 0;
        $can_T = 0;
        $can_SNT = 0;
        $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='02' and cdg_cod_emp='".$_GET['emp']."' and to_char(cdg_fec_gen,'dd-mm-yyyy')='".$fecha_documentos."'";
        $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
        oci_execute($sql_parse);
        oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($documentos as $documento) {
            //docuementos totals
            $can_T = $can_T + 1;
            if ($documento['CDG_COD_SNT'] == '0001' || $documento['CDG_COD_SNT'] == '0003') {
                //enviados
                $can_SNT = $can_SNT + 1;
            }
            if($documento['CDG_TIP_DOC'] == 'F'){
                $can_F = $can_F +1;
                if($documento['CDG_ANU_SN']=='S' && $documento['CDG_DOC_ANU']=='S'){
                    $can_E = $can_E +1;
                }
            }elseif($documento['CDG_TIP_DOC'] == 'B'){
                $can_B = $can_B +1;
            }elseif($documento['CDG_TIP_DOC'] == 'A'){
                $can_A = $can_A +1;
            }
            // sacamos los eliminados
            if($documento['CDG_ANU_SN']!='S' && $documento['CDG_DOC_ANU']!='S'){
                //facturas
                if($documento['CDG_TIP_DOC'] == 'F'){
                    $total_sf = $total_sf + $documento['CDG_IMP_NETO'];
                    if($documento['CDG_COD_SNT'] == '0001'){
                        $total_ef = $total_ef + $documento['CDG_IMP_NETO'];
                    }
                }
                //boletas
                if($documento['CDG_TIP_DOC'] == 'B'){
                    $total_sb = $total_sb + $documento['CDG_IMP_NETO'];
                    if($documento['CDG_COD_SNT'] == '0001'){
                        $total_eb = $total_eb + $documento['CDG_IMP_NETO'];
                    }
                }
                
                //totales
                $total_s = $total_s + $documento['CDG_IMP_NETO'];
                if($documento['CDG_COD_SNT'] == '0001'){
                    $total_e = $total_e + $documento['CDG_IMP_NETO'];
                }
            }
            //notas                            
            if($documento['CDG_TIP_DOC'] == 'A'){
                $total_sa = $total_sa + $documento['CDG_IMP_NETO'];
                if($documento['CDG_COD_SNT'] == '0001'){
                    $total_ea = $total_ea + $documento['CDG_IMP_NETO'];
                }
            }

            //resumen
            $cierre = 0;
            $fecha_resumen_item = str_pad($i,2,'0',STR_PAD_LEFT).'-'.$mes.'-'.$anho;
            foreach ($resumen_items as $resumen_item) {
                //$fecha_temp = date('d-m-Y', strtotime($resumen_item['FECHA']));                
                if($cierre == 0){
                    if ($fecha_resumen_item == date('d-m-Y', strtotime($resumen_item['FECHA'])) && $resumen_item['CODIGO'] == '0'){
                        $resumen_s = 'SI';
                        $class_resumen = 'success';
                        $cierre++;
                    }else{
                        $resumen_s = 'NO';
                        $class_resumen = 'danger';
                    }
                }
                
            } 
            
        }
        echo '<tr>';
        echo '<td class="text-center well"><strong>'.str_pad($i,2,'0',STR_PAD_LEFT).'</strong></td>';
        echo '<td>'.str_pad($can_F,2,'0',STR_PAD_LEFT).' | '.str_pad($can_B,2,'0',STR_PAD_LEFT).' | '.str_pad($can_A,2,'0',STR_PAD_LEFT).' | '.str_pad($can_E,2,'0',STR_PAD_LEFT).' | '.str_pad(($can_T-$can_SNT),2,'0',STR_PAD_LEFT).'</td>';
        echo '<td class="text-center">'.number_format($total_sf,2,'.','').' | '.number_format($total_ef,2,'.','').'</td>';
        echo '<td class="text-center">'.number_format($total_sb,2,'.','').' | '.number_format($total_eb,2,'.','').'</td>';
        echo '<td class="text-center">'.number_format($total_sa,2,'.','').' | '.number_format($total_ea,2,'.','').'</td>';
        echo '<td>'.number_format($total_s,2,'.','').'</td>';
        echo '<td>'.number_format($total_e,2,'.','').'</td>';
        echo '<td class="text-center '.$class_resumen.'">'.$resumen_s.'</td>';
        echo '<td>';
        echo '<a href="../index.php?fecha_inicio='.$fecha_resumen_item.'&fecha_final='.$fecha_resumen_item.'&pagina=1&emp='.$emp.'" target="_blank" class="btn btn-default btn-xs">Dia</a> ';
        echo '<a href="../resumen.php?h=0&gen=02&emp='.$emp.'&fecha='.date('Y-m-d', strtotime($fecha_resumen_item)).'" target="_blank" class="btn btn-default btn-xs">Resumen</a> ';        
        echo '</td>';
        echo '</tr>';
    }
    echo '<tr>';
    echo '<tb></tb>';
    echo '</tr>';
    echo '</table>';
    
    
?>
                
               
        </div>            
    </div>
</div>

</body>
</html>