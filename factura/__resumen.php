<?php

    if (isset($_GET['fecha']) && isset($_GET['gen']) && isset($_GET['emp']) ){
        $fecha  =   date("d-m-Y", strtotime($_GET['fecha']));
        $gen    =   $_GET['gen'];
        $emp    =   $_GET['emp'];
    }

    //echo $fecha;

    /*conexion
    ****************/
    include "conexion.php";


    /*Consulta Boletas
    **********************/
    $sql_boletas_dia = "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$fecha."' and (cdg_anu_sn, cdg_doc_anu) in (('S','N'),('N','N')) and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_num_doc Asc";
    $sql_parse = oci_parse($conn,$sql_boletas_dia);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $boletas, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros

    //print_r($boletas);

    /*Consulta Boletas Notas
    ***************************/
    $sql_notas_dia = "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$fecha."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_num_doc ASC";
    $sql_parse_notas = oci_parse($conn,$sql_notas_dia);
    oci_execute($sql_parse_notas);
    oci_fetch_all($sql_parse_notas, $notas, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros

    //print_r($notas);



    /*Envio de boletas
    ********************/
    if (count($boletas) != 0){
        $salida1 = rangos($boletas);
    }

    /*Envio de Notas boletas
    *************************/
    if (count($notas) != 0){
        $salida2 = rangos($notas);
    }


    function rangos($boletas){

        $i = 0;
        $first = 0;
        $resumens = [];
        foreach ($boletas as $index => $boleta){

            if ($first == 0){ //es primero
                $resumens[$i]['first'] = $boleta['CDG_NUM_DOC'];
                $first = 1; // ya no es primero

                /*serie
                ***********/
                if ($boleta['CDG_TIP_DOC'] == 'A'){
                    $resumens[$i]['serie'] = $boleta['CDG_TIP_REF'][0].'N0'.$boleta['CDG_SER_DOC'];
                    $resumens[$i]['serie_tipo'] = '07';
                }else {
                    $resumens[$i]['serie'] = $boleta['CDG_TIP_DOC'].'00'.$boleta['CDG_SER_DOC'];
                    $resumens[$i]['serie_tipo'] = '03';
                }


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
                $resumens[$i]['sub'] +=  $boleta['CDG_VVP_TOT'] - round(($boleta['CDG_TOT_FRA']/1.18),2);
                $resumens[$i]['gravadas'] += (($boleta['CDG_VVP_TOT'] - round(($boleta['CDG_TOT_FRA']/1.18),2)) - $boleta['CDG_DES_TOT']);
                $resumens[$i]['igv'] += $boleta['CDG_IGV_TOT'] - round(($boleta['CDG_TOT_FRA']/1.18)*0.18,2);
            }else {
                $resumens[$i]['sub'] += $boleta['CDG_VVP_TOT'];
                $resumens[$i]['gravadas'] += ($boleta['CDG_VVP_TOT'] - $boleta['CDG_DES_TOT']);
                $resumens[$i]['igv'] += $boleta['CDG_IGV_TOT'];
            }
            $resumens[$i]['descuentos'] += $boleta['CDG_DES_TOT'];
            $resumens[$i]['total'] += $boleta['CDG_IMP_NETO'];

            if (isset($boletas[$index+1])){ //siguiente index existe
                if (($boleta['CDG_NUM_DOC']+1) != $boletas[$index+1]['CDG_NUM_DOC']){//siguiente en valores es igual al anterior
                    $first = 0;
                    $i++;
                }
            }

        }
        return $resumens;


    }

    //boletas y notas
    if (count($boletas) != 0 && count($notas) != 0){
        $boletas = array_merge($salida1, $salida2);
    // boletas
    }elseif (count($boletas) != 0 && count($notas) == 0){
        $boletas = array_merge($salida1);
    //notas
    }elseif (count($boletas) == 0 && count($notas) != 0){
        $boletas = array_merge($salida2);
    }

    //print_r($boletas);

?>