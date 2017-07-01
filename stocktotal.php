<?php 

ini_set('memory_limit', '-1');
//$conn = oci_connect("dti", "dti", "54.202.12.82/xe");
//$csv = array_map('str_getcsv', file('C:\Users\sistemas\Desktop\StockTotal.csv'));
$csv  = array_map(function($v){return str_getcsv($v, "\t");}, file('C:\Users\sistemas\Desktop\StockTotal.txt'));

$i=1;
foreach ($csv as $key) {

	
	$sql_factura_detalle_resumen = "select * from lis_pre_rep where lpr_cod_gen=02 and lpr_cod_pro='".$key[0]."'";
    $factura_detalle_resumen = oci_parse($conn, $sql_factura_detalle_resumen);
    oci_execute($factura_detalle_resumen);
    while ($fila_factura_detalle_resumen = oci_fetch_array($factura_detalle_resumen, OCI_ASSOC + OCI_RETURN_NULLS)) {        
        $cod_pro = $fila_factura_detalle_resumen['LPR_COD_PRO'];
        $vvd_sol = $fila_factura_detalle_resumen['LPR_VVD_SOL'];
        $vvp_sol = $fila_factura_detalle_resumen['LPR_VVP_SOL'];
    }	


	if(isset($cod_pro) || isset($vvd_sol) || isset($vvp_sol)){
		$pos = strpos($key[4], '.');

		if ($pos === false) {
			// si no se encontro un punto
			$pos2 = strpos($key[5], '.');
			if ($pos2 === false) {
				$update = "update lis_pre_rep set LPR_VVD_SOL=".$key[6].", LPR_VVP_SOL=".$key[7].", LPR_POR_DES=".$key[8].", LPR_MAR_PRO='".$key[9]."' where LPR_COD_PRO='".$key[0]."' and LPR_COD_GEN=02";
			} else {
				$update = "update lis_pre_rep set LPR_VVD_SOL=".$key[5].", LPR_VVP_SOL=".$key[6].", LPR_POR_DES=".$key[7].", LPR_MAR_PRO='".$key[8]."' where LPR_COD_PRO='".$key[0]."' and LPR_COD_GEN=02";
			}
		} else {			
			$update = "update lis_pre_rep set LPR_VVD_SOL=".$key[4].", LPR_VVP_SOL=".$key[5].", LPR_POR_DES=".$key[6].", LPR_MAR_PRO='".$key[7]."' where LPR_COD_PRO='".$key[0]."' and LPR_COD_GEN=02";
		}

		
		$stmt = oci_parse($conn, $update);
		oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
		oci_free_statement($stmt);
    	
    	echo $i.' | '.$key[0].' | '.$key[4].' | '.$key[5].' | '.$key[6].' | '.$key[7].' | '.$key[1].'<br>';
		$i++;
    }	
}

?>