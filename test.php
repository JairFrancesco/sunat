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

        <form action="" class="form-inline">
            <div class="col-lg-6">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="form-control" name="fecha_inicio" value="<?php if($fecha_inicio !='N') { echo $fecha_inicio; } else { echo "13-03-2017"; } ?>"/>
                    <span class="input-group-addon">to</span>
                    <input type="text" class="form-control" name="fecha_final" value="<?php if($fecha_final !='N') { echo $fecha_final; } else { echo "13-03-2017"; } ?>" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <input type="hidden" name="pagina" value="1">
                </div>
            </div>
        </form>

		<div class="col-lg-6">
            <nav>
                <ul class="pager pull-right">
                    <li><a href="test.php?fecha_inicio=<?php echo $fecha_inicio ?>&fecha_final=<?php echo $fecha_final ?>&pagina=<?php echo $pagina - 1 ?>">Anterior</a></li>
                    <li><a href="test.php?fecha_inicio=<?php echo $fecha_inicio ?>&fecha_final=<?php echo $fecha_final ?>&pagina=<?php echo $pagina + 1 ?>">Siguiente</a></li>
                </ul>
            </nav>
		</div>
	</div>
	
	<div class="container">
		<table class="table table-hover">
			<thead>
				<tr>
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
                    require("app/coneccion.php");
                    $curs = oci_new_cursor($conn);
					$sql = "begin PKG_ELECTRONICA.docs('02','01',".$pagina.",'".$fecha_inicio."','".$fecha_final."',:docs); end;";
					$stid = oci_parse($conn,$sql);
					oci_bind_by_name($stid, ":docs", $curs, -1, OCI_B_CURSOR);
					oci_execute($stid);
					oci_execute($curs);				
 
					while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
						$row2 = $row;
				?>
				<tr>
					<td><?php echo strtolower($row['FEC_GEN1']); ?></td>
					<td><?php echo substr($row['NOMBRE10'],3,20); ?></td>					
					<td><?php echo strtolower(substr($row['NOM_CLI2'],0,25)); ?></td>
					<td>
						<?php echo $row['CLA_DOC3']; ?> 
						<?php echo $row['CO_CR_AN4']; ?>
						<?php echo $row['TIP_IMP6']; ?> 
						<?php echo $row['FQ5']; ?>
							
					</td>
					<td><?php echo $row['ANU_SN11'] . ' / ' .$row['DOC_ANU12']; ?></td>
					<td><?php echo $row['SOLES8']; ?></td>
					<td><?php echo $row['VVP_TOT7']; ?></td>
					<td><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
					<td>
						<a class="dropdown-item" href="test2.php?gen=02&emp=01&num_doc=<?php echo $row['NUM_DOC0'] ?>&cla_doc=<?php echo $row['CLA_DOC3'] ?>&moneda=<?php echo $row['SOLES8']; ?>&co_cr_an=<?php echo $row['CO_CR_AN4'] ?>&exi_fra=<?php echo $row['FQ5'] ?>&tip_imp=<?php echo $row['TIP_IMP6'] ?>&anu_sn=<?php echo $row['ANU_SN11'] ?>&doc_anu=<?php echo $row['DOC_ANU12'] ?>">Facturar</a>			
					</td>
				</tr>
				    	
				<?php

				}
					//$HTTP_GET_VARS["saludo"]
				?>				
			</tbody>				
		</table>
	</div>

			
</body>
</html>
<?php //print_r($row2) ?>

