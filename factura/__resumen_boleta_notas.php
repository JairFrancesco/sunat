<?php

$sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and (cdg_anu_sn, cdg_doc_anu) in (('S','N'),('N','N')) and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_num_doc Asc"); oci_execute($sql_boletas);
while($res_boletas = oci_fetch_array($sql_boletas)){
    //echo $res_boletas['CDG_NUM_DOC'].' '.$res_boletas['CDG_FEC_GEN'].'<br>';
    $boletas[] = $res_boletas;
}

$sql_notas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'dd-mm-yyyy') = '".$dia."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_num_doc ASC"); oci_execute($sql_notas);
while($res_notas = oci_fetch_array($sql_notas)){ $notas[] = $res_notas; }

//echo $res_notas['CDG_NUM_DOC'].' '.$res_notas['CDG_FEC_GEN'].'<br>';

if ($emp == '01')
{
    $serie_boleta = 'B001';
    $serie_nota = 'BN03';
} else
{
    $serie_boleta = 'B004';
    $serie_nota = 'BN04';
}

$ant = 0;
$i = 0;
$h = 0;

if (isset($boletas)){
    foreach ( $boletas as $boleta ){
        if ($i==0){
            $bols[$h][0] = $boleta['CDG_NUM_DOC'];
            $ant = $boleta['CDG_NUM_DOC'];
            $i++;
            if (count($boletas)==1){
                $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                $bols[$h][2] = $serie_boleta;
            }
        }else {
            if (($ant+1) == $boleta['CDG_NUM_DOC']){
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $boleta['CDG_NUM_DOC']){
                        $bols[$h][1] = $ant;
                        $bols[$h][2] = $serie_boleta;
                        $h++;
                        $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }else {
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $boleta['CDG_NUM_DOC'];
                }
            }else {
                $bols[$h][1] = $ant;
                $bols[$h][2] = $serie_boleta;
                $h++;
                $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                $ant = $boleta['CDG_NUM_DOC'];
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                    $bols[$h][2] = $serie_boleta;
                }

            }
        }
    }
}

$ant = 0;
$i = 0;
$h = 0;
if (isset($notas)){
    foreach ( $notas as $nota ){
        if ($i==0){
            $nots[$h][0] = $nota['CDG_NUM_DOC'];
            $ant = $nota['CDG_NUM_DOC'];
            $i++;
            if (count($notas)==1){
                $nots[$h][1] = $nota['CDG_NUM_DOC'];
                $nots[$h][2] = $serie_nota;
            }
        }else {
            if (($ant+1) == $nota['CDG_NUM_DOC']){
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $nota['CDG_NUM_DOC']){
                        $nots[$h][1] = $ant;
                        $nots[$h][2] = $serie_nota;
                        $h++;
                        $nots[$h][0] = $nota['CDG_NUM_DOC'];
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }else {
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $nota['CDG_NUM_DOC'];
                }
            }else {
                $nots[$h][1] = $ant;
                $nots[$h][2] = $serie_nota;
                $h++;
                $nots[$h][0] = $nota['CDG_NUM_DOC'];
                $ant = $nota['CDG_NUM_DOC'];
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    $nots[$h][1] = $nota['CDG_NUM_DOC'];
                    $nots[$h][2] = $serie_nota;
                }

            }
        }
    }
}
?>