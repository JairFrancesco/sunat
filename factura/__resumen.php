<?php
    if (isset($_GET['fecha']) && isset($_GET['gen']) && isset($_GET['emp']) ){
        $fecha  =   $_GET['fecha'];
        $gen    =   $_GET['gen'];
        $emp    =   $_GET['emp'];
    }else {
        $fecha  =   '24-08-2017';
        $gen    =   '02';
        $emp    =   '02';
    }

    /*conexion
    ****************/
    include "conexion.php";


    /*consulta
    **************/
    $sql_boletas_dia = "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$fecha."' and (cdg_anu_sn, cdg_doc_anu) in (('S','N'),('N','N')) and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_num_doc Asc";
    $sql_parse = oci_parse($conn,$sql_boletas_dia);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $boletas, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros

    $i = 0;
    $first = 0;
    $resumens = [];
    foreach ($boletas as $index => $boleta){

        if ($first == 0){ //es primero
            $resumens[$i]['first'] = $boleta['CDG_NUM_DOC'];
            $first = 1; // ya no es primero

            /*definiciones totales
            ***********************/
            $resumens[$i]['sub'] = 0;
            $resumens[$i]['descuentos'] = 0;
            $resumens[$i]['gravadas'] = 0;
            $resumens[$i]['igv'] = 0;
            $resumens[$i]['total'] = 0;
        }

        $resumens[$i]['last'] = $boleta['CDG_NUM_DOC']; // mientras i no cambie

        /*REFERENCIA 0:sin  1:nota  2:franquisia 3:anticipo
        *****************************************************/
        if ($boleta['CDG_TIP_DOC'] == 'A') {
            $reference = 1; //nota
        }elseif ($boleta['CDG_EXI_FRA'] == 'S'  && $boleta['CDG_EXI_ANT']!='AN' && $boleta['CDG_TIP_DOC'] != 'A') {
            $reference = 2; // franquisia
        }elseif ($boleta['CDG_EXI_ANT'] == 'AN' && $boleta['CDG_TIP_DOC'] != 'A') {
            $reference = 3; // anticipo
        }else {
            $reference = 0;
        }


        /*Totales
        ****************/
        if ($reference == 2 || $reference == 3 ) { //franquisia o anticipo

        }else {
            $resumens[$i]['sub'] = $resumens[$i]['sub'] +  $boleta['CDG_VVP_TOT'];
            $resumens[$i]['descuentos'] = $resumens[$i]['descuentos'] + $boleta['CDG_DES_TOT'];
            $resumens[$i]['gravadas'] = $resumens[$i]['gravadas'] + ($boleta['CDG_VVP_TOT'] - $boleta['CDG_DES_TOT']);
            $resumens[$i]['igv'] = $resumens[$i]['igv'] + $boleta['CDG_IGV_TOT'];
        }
        $resumens[$i]['total'] = $resumens[$i]['total'] + $boleta['CDG_IMP_NETO'];

        if (isset($boletas[$index+1])){ //siguiente index existe
            if (($boleta['CDG_NUM_DOC']+1) != $boletas[$index+1]['CDG_NUM_DOC']){//siguiente en valores es igual al anterior
                $first = 0;
                $i++;
            }
        }

    }

    print_r($resumens);



?>