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

    /*Auth
    ***************/
    include "factura/layout/__auth.php";

    date_default_timezone_set('America/Lima');
	if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_final']) && isset($_GET['pagina'])) {
		$fecha_inicio = $_GET['fecha_inicio'];
		$fecha_final = $_GET['fecha_final'];    	
		$pagina = $_GET['pagina'];
	}else{
		$fecha_inicio = 'N';
		$fecha_final = 'N';
		$pagina = 1;
	}
	if (isset($_GET['emp'])) {
	    $emp = $_GET['emp'];
	}

	/*Nav Bar
	**************/
	include "factura/layout/__nav_bar.php";

	/*Conexion BD
	*****************/
    require("app/coneccion.php");

?>


	<div class="container">
        <?php

        if (isset($_GET['emp'])) {
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            if ($emp == '01') {
                echo '<h1><span class="glyphicon glyphicon-th-list"></span> Tacna <small>Surmotriz</small></h1><br>';
            } elseif ($emp == '02') {
                echo '<h1><span class="glyphicon glyphicon-th-list"></span> Moquegua <small>Surmotriz</small></h1><br>';
            }
            echo '</div>';
            echo '<div class="col-lg-6 text-right">';
            echo '<br><br>';


            /*Boton Resumen Facturas
            *************************/
            include "factura/__crfacturas.php";
            if ($rfacturas == 1){
                echo '<a href="factura/rfacturas.php" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-refresh"></i> Resumen Factura</a> ';
            }else {
                echo '<a target="_blank" class="btn btn-success" disabled="disabled"><i class="glyphicon glyphicon-refresh"></i> Resumen Factura</a> ';
            }


            if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_final'])) {
                if ($_GET['fecha_inicio'] == $_GET['fecha_final']) {
                    $date1 = new DateTime($_GET['fecha_inicio']);
                    $date2 = new DateTime(date('d-m-Y'));
                    $diff = $date1->diff($date2);
                    if (($diff->days) < 32) {
                        $fecha = date("Y-m-d", strtotime($_GET['fecha_inicio']));
                    } else {
                        $fecha = 'N';
                    }

                } else {
                    $fecha = 'N';
                }
            } else {
                $fecha = date("Y-m-d");
            }


            if ($fecha != 'N'){
                echo '<a class="btn btn-primary" href="resumen.php?h=0&gen=02&emp=' . $emp . '&fecha=' . $fecha . '" target="_blank"><span class="glyphicon glyphicon-refresh"></span> Resumen Boletas</a>';
            }

            echo '</div>';
            echo '</div>';

        ?>
            <div class="row">
                <form action="" class="form-inline">
                    <div class="col-lg-6">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control" name="fecha_inicio" value="<?php if($fecha_inicio !='N') { echo $fecha_inicio; } else { echo date('d-m-Y'); } ?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" name="fecha_final" value="<?php if($fecha_final !='N') { echo $fecha_final; } else { echo date('d-m-Y');  } ?>" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Buscar</button>
                            <input type="hidden" name="pagina" value="1">
                            <input type="hidden" name="emp" value="<?php echo $emp; ?>">
                        </div>
                    </div>
                </form>

                <div class="col-lg-6">
                    <nav>
                        <ul class="pager pull-right">
                            <li><a href="index.php?fecha_inicio=<?php echo $fecha_inicio ?>&fecha_final=<?php echo $fecha_final ?>&pagina=<?php echo $pagina - 1 ?>&&emp=<?php echo $emp; ?>"><span class="glyphicon glyphicon-arrow-left"></span> Anterior</a></li>
                            <li><a href="index.php?fecha_inicio=<?php echo $fecha_inicio ?>&fecha_final=<?php echo $fecha_final ?>&pagina=<?php echo $pagina + 1 ?>&&emp=<?php echo $emp; ?>">Siguiente <span class="glyphicon glyphicon-arrow-right"></span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>

		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr class="well">
					<th>Fecha</th>
					<th>Nro Doc</th>					
					<th>Cliente</th>
					<th>Cla CO/CR</th>
					<th>Anula</th>
					<th>Moneda</th>
					<th>Total</th>
					<th>Sunat</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php

                    if(isset($_GET['fecha_inicio']) && isset($_GET['fecha_final'])){
                        if ($_GET['fecha_inicio'] == $_GET['fecha_final']) {
                            $fecha_documentos = $_GET['fecha_inicio'];
                            $check_documentos = 1;
                        }else{
                            $check_documentos = 0;    
                        }
                        
                    }else{
                        $fecha_documentos = date("d-m-Y");
                        $check_documentos = 1;
                    }
                    //echo $check_documentos;
                    if($check_documentos == 1){
                        $sql_cab_doc_gen = "select * from cab_doc_gen where cdg_cod_gen='02' and cdg_cod_emp='".$_GET['emp']."' and to_char(cdg_fec_gen,'dd-mm-yyyy')='".$fecha_documentos."'";
                        $sql_parse = oci_parse($conn,$sql_cab_doc_gen);
                        oci_execute($sql_parse);
                        oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    }
                        //print_r($documentos);

                    $curs = oci_new_cursor($conn);
					$sql = "begin PKG_ELECTRONICA.docs('02','".$emp."',".$pagina.",'".$fecha_inicio."','".$fecha_final."',:docs); end;";
					$stid = oci_parse($conn,$sql);
					oci_bind_by_name($stid, ":docs", $curs, -1, OCI_B_CURSOR);
					oci_execute($stid);
					oci_execute($curs);
					while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
						//if ($row['CDG_TIP_REF'] !='BR' &&  $row['CDG_TIP_REF'] !='BS' ) {
                        if ($row['CDG_SUN_ENV']=='S'){
                            if ($row['ANU_SN11']=='S' && $row['DOC_ANU12']=='S'){
                                $sunat = 'danger';
                                $icon = 'glyphicon glyphicon-remove';
                            } else {
                                $sunat = 'success';
                                $icon = 'glyphicon glyphicon-ok';
                            }
                        } elseif ($row['CDG_SUN_ENV']=='C'){
                            $sunat = 'danger';
                            $icon = 'glyphicon glyphicon-ok';
                        } else {
                            $sunat = '';
                            $icon = 'glyphicon glyphicon-remove';
                        }
						    echo '
						        <tr class="'.$sunat.'">						            
						            <td>'.strtolower($row['FEC_GEN1']).'</td>
						            <td>'.substr($row['NOMBRE10'],3,20).'</td>
						            <td>'.strtolower(substr($row['NOM_CLI2'],0,25)).'</td>
						            <td>'.$row['CLA_DOC3'].' '.$row['CO_CR_AN4'].' '.$row['TIP_IMP6'].' '.$row['FQ5'].' '.$row['CDG_EXI_FRA'].' '.$row['CDG_EXI_ANT'].'</td>
						            <td>'.$row['ANU_SN11'].' '.$row['DOC_ANU12'].'</td>
						            <td>'.$row['SOLES8'].'</td>
						            <td class="text-right">'.number_format($row['VVP_TOT7'], 2, ".", ",").'</td>';

                            //cantidad caracteres sunat
                            if(strlen($row['CDG_COD_SNT'])==4){
                                $codigo_sunat = $row['CDG_COD_SNT'];
                            }else{
                                $codigo_sunat = substr($row['CDG_COD_SNT'],11,4);
                            }
                            echo '<td class="text-center">'.$codigo_sunat.'</td>';
                            /*
                            if ($row['CDG_SUN_ENV']=='S'){

                            } elseif ($row['CDG_SUN_ENV']=='C'){
                                echo '<td class="text-center"><span class="'.$icon.'" aria-hidden="true"></span></td>';
                            } else{
                                echo '<td class="text-center"><span class="'.$icon.'" aria-hidden="true"></span></td>';
                            }
                            */

                            // arranca el 1 la facturacion electronica
                            $fisico_electro = 1;
                            echo '<td>';
                                if($row['CDG_TIP_DOC'] == 'F'){
                                    echo '<a href="factura/pdf.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">PDF</a> ';
                                    echo '<a href="factura/xml_factura.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" class="btn btn-default btn-xs" target="_blank">XML</a> ';
                                    echo '<a href="factura/comprobar.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">COM</a> ';
                                }elseif($row['CDG_TIP_DOC'] == 'B'){
                                    echo '<a href="factura/pdf.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">PDF</a> ';
                                    echo '<a class="btn btn-default btn-xs" disabled="">XML</a> ';
                                    echo '<a href="factura/rbcomprobacion.php?serie='.substr($row['NOMBRE10'],3,4).'&num='.$row['CDG_NUM_DOC'].'&fecha='.date("d-m-Y", strtotime($row['FEC_GEN1'])).'" class="btn btn-default btn-xs" target="_blank">COM</a> ';
                                }elseif($row['CDG_TIP_DOC'] == 'A'){
                                    $sql_ref = "select * from cab_doc_gen where cdg_cod_gen='".$row['CDG_COD_GEN']."' and cdg_cod_emp='".$row['CDG_COD_EMP']."' and cdg_cla_doc='".$row['CDG_TIP_REF']."' and cdg_num_doc='".$row['CDG_DOC_REF']."'";
                                    $sql_ref_parse = oci_parse($conn, $sql_ref);
                                    oci_execute($sql_ref_parse);
                                    oci_fetch_all($sql_ref_parse, $ref, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                                    $fecha_actual = strtotime(date("d-m-Y", strtotime($ref[0]['CDG_FEC_GEN'])));
                                    $fecha_fija = strtotime('13-07-2017');
                                    if($fecha_actual >= $fecha_fija){
                                        // factura elctronica
                                        $fisico_electro = 1;
                                    }else{
                                        if($ref[0]['CDG_TIP_DOC'] =='B'){
                                            // factura electronica
                                            $fisico_electro = 1;
                                        }else{
                                            // factura fisica
                                            $fisico_electro = 0;
                                        }

                                    }

                                   // if($fisico_electro == 1){
                                        if ($row['CDG_TIP_REF'] !='BR' &&  $row['CDG_TIP_REF'] !='BS' ) {
                                            echo '<a href="factura/pdf.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">PDF</a> ';
                                            echo '<a href="factura/xml_nota.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">XML</a> ';
                                            echo '<a href="factura/comprobar.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">COM</a> ';
                                        }else{
                                            echo '<a href="factura/pdf.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" class="btn btn-default btn-xs" target="_blank">PDF</a> ';
                                            echo '<a href="" class="btn btn-default btn-xs" disabled="">XML</a> ';
                                            echo '<a href="" class="btn btn-default btn-xs" disabled="">COM</a> ';
                                        }
                                    /*
                                    }else{
                                        echo '<a href="factura/pdf_fisico.php?gen='.$row['CDG_COD_GEN'].'&emp='.$row['CDG_COD_EMP'].'&tip='.$row['CDG_TIP_DOC'].'&num='.$row['CDG_NUM_DOC'].'" target="_blank" class="btn btn-default btn-xs">FSC</a> ';
                                        echo '<a target="_blank" class="btn btn-default btn-xs" disabled="">XML</a> ';
                                        echo '<a target="_blank" class="btn btn-default btn-xs" disabled="">COM</a> ';
                                    }
                                    */
                                }

                                if($fisico_electro == 1){
                                    if ($row['CDG_SUN_ENV']=='S'){
                                        if ($row['ANU_SN11']=='S' && $row['DOC_ANU12']=='S') {
                                            echo '<a class="btn btn-'.$sunat.' btn-xs" href="test2.php?gen=02&emp='.$row['CDG_COD_EMP'].'&num_doc='.$row['NUM_DOC0'].'&cla_doc='.$row['CLA_DOC3'].'&moneda='.$row['SOLES8'].'&co_cr_an='.$row['CO_CR_AN4'].'&exi_fra='.$row['FQ5'].'&tip_imp='.$row['TIP_IMP6'].'&anu_sn='.$row['ANU_SN11'].'&doc_anu='.$row['DOC_ANU12'].'&sun_env='.$row['CDG_SUN_ENV'].'" target="_blank"><span class="'.$icon.'"></span> Dar Baja</a>';
                                        } else {
                                            echo '<a class="btn btn-'.$sunat.' btn-xs" href="./app/repo/' . $row['NOMBRE_DOC'] . '.pdf" target="_blank"><span class="'.$icon.'"></span> Imprimir</a>';
                                        }
                                    } elseif ($row['CDG_SUN_ENV']=='C'){
                                        echo '<a class="btn btn-'.$sunat.' btn-xs" href="./app/repo/' . $row['NOMBRE_DOC'] . '.pdf" target="_blank"><span class="'.$icon.'"></span> Imprimir</a>';
                                    }
                                    else{
                                        echo '<a class="btn btn-primary btn-xs" href="test2.php?gen=02&emp='.$row['CDG_COD_EMP'].'&num_doc='.$row['NUM_DOC0'].'&cla_doc='.$row['CLA_DOC3'].'&moneda='.$row['SOLES8'].'&co_cr_an='.$row['CO_CR_AN4'].'&exi_fra='.$row['FQ5'].'&tip_imp='.$row['TIP_IMP6'].'&anu_sn='.$row['ANU_SN11'].'&doc_anu='.$row['DOC_ANU12'].'&sun_env='.$row['CDG_SUN_ENV'].'" target="_blank"><span class="'.$icon.'"></span> Facturar</a>';
                                    }
                                }else{
                                    echo '<a target="_blank" class="btn btn-default btn-xs" disabled=""><span class="'.$icon.'"></span>Imprimir</a>';
                                }
					        echo '</td></tr>';

                        //}
                    }
                    if($check_documentos == 1){
                        $total_sf = 0;
                        $total_ef = 0;
                        $total_sb = 0;
                        $total_eb = 0;
                        $total_sa = 0;
                        $total_ea = 0;
                        //echo count($documentos);
                        foreach ($documentos as $documento) {
                            // sacamos los eliminados
                            if($documento['CDG_DOC_ANU']=='N'){

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

                                //notas                            
                                if($documento['CDG_TIP_DOC'] == 'A'){
                                    $total_sa = $total_sa + $documento['CDG_IMP_NETO'];
                                    if($documento['CDG_COD_SNT'] == '0001'){
                                        $total_ea = $total_ea + $documento['CDG_IMP_NETO'];
                                    }
                                }                                
                            }                            
                        }                        
                        echo '<tr>';
                        echo '<td colspan="2"><strong>Facturas '.number_format($total_sf,2,'.','').' | '.number_format($total_ef,2,'.','').'</strong></td>';
                        echo '<td><strong>Boletas '.number_format($total_sb,2,'.','').' | '.number_format($total_eb,2,'.','').'</strong></td>';
                        echo '<td colspan="2"><strong>Notas '.number_format($total_sa,2,'.','').' | '.number_format($total_ea,2,'.','').'</strong></td>';
                        echo '<td colspan="3"></td>';
                        echo '<td colspan="2"></td>';
                        echo '</tr>';
                    }

				?>                
                    
                    
                </tr>
			</tbody>				
		</table>
        <?php } else { ?>
            <div style="padding-top: 250px; padding-left: 430px;">
                <a class="btn btn-default btn-lg" href="index.php?emp=01">
                    <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> Tacna
                </a>
                <a class="btn btn-default btn-lg" href="index.php?emp=02">
                    <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> Moquegua
                </a>
            </div>
        <?php } ?>
	</div>

</body>
</html>