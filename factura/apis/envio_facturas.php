<?php

    /*condigurando try cash
    *****************************/
    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    /*Config Fecha Peru
    **********************/
    date_default_timezone_set('America/Lima');

    /*Parametros
    **********************/
    $gen = '02';
    $emp = '02';
    $fecha = '21-10-2017';


    /*Conexion
    **********************/
    include "../conexion.php";

    /*Firmado Electronico*/
    require '../../robrichards/src/xmlseclibs.php';

    /*Letras funcion
    ***********************/
    include ("../convertir_a_letras.php");

    /*funcion crear factura xml
    ******************************/
    function crear_xml_factura($gen,$emp,$tip,$num){
        include "__doc.php";
        include "__xml_factura.php";
    }

    /*funcion crear nota xml
    ******************************/
    function crear_xml_nota($gen,$emp,$tip,$num){
        include "__doc.php";
        include "__xml_nota.php";
    }

    /*Funcion Comprobar
    ******************************/
    function comprobar_facturas($tip,$ser,$num,$cod,$cla,$emp,$gen,$anu_sn,$doc_anu){
        include "__comprobar_facturas.php";
    }

    /*Funcion Baja
    ************************/
    function crear_baja_factura($gen,$emp,$num,$cla){
        include "__baja_xml_factura.php";
    }

    /*Funcion enviar resumen
    **************************/
    function enviar_resumen($gen,$emp,$fecha){
      include "__enviar_resumen.php";
    }

    /*Soap client
    *******************/
    include "../__soap.php";

    /*funcion enviar xml
    ******************************/

    /*Consulta Facturas
    **********************/
    $sql_facturas_dia = "select * from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy')='".$fecha."' and cdg_cod_emp='".$emp."'
      and cdg_tip_doc in ('A') and  cdg_tip_ref in ('FS','FR','FC')
      union
      select * from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy')='".$fecha."' and cdg_cod_emp='01' and cdg_tip_doc in ('F')";
    $sql_parse = oci_parse($conn,$sql_facturas_dia);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $documentos, null, null, OCI_FETCHSTATEMENT_BY_ROW); //sin array numeros

    /*Creacion del xml y envio a sunat cdg_sun_env=S */
    foreach ($documentos as $documento){
        $crear_gen = $documento['CDG_COD_GEN'];
        $crear_emp = $documento['CDG_COD_EMP'];
        $crear_tip = $documento['CDG_TIP_DOC'];
        $crear_num = $documento['CDG_NUM_DOC'];
        if ($documento['CDG_TIP_DOC']=='F'){
            crear_xml_factura($crear_gen,$crear_emp,$crear_tip,$crear_num);
        }elseif ($documento['CDG_TIP_DOC']=='A'){
            crear_xml_nota($crear_gen,$crear_emp,$crear_tip,$crear_num);
        }
    }

    /*Comprobacion sunat cdg_cod_env=0001*/
    foreach ($documentos as $documento){
        $crear_tip = $documento['CDG_TIP_DOC'];
        $crear_ser = $documento['CDG_SER_DOC'];
        $crear_num = $documento['CDG_NUM_DOC'];
        $crear_cod = $documento['CDG_COD_SNT'];
        $crear_cla = $documento['CDG_CLA_DOC'];
        $crear_emp = $documento['CDG_COD_EMP'];
        $crear_gen = $documento['CDG_COD_GEN'];
        $crear_anu_sn = $documento['CDG_ANU_SN'];
        $crear_doc_anu = $documento['CDG_DOC_ANU'];
        comprobar_facturas($crear_tip,$crear_ser,$crear_num,$crear_cod,$crear_cla,$crear_emp,$crear_gen,$crear_anu_sn,$crear_doc_anu);
    }

    /*Baja de facturas cdg_cod_env=0003
    *************************************/
    foreach ($documentos as $documento){
        if ($documento['CDG_ANU_SN']=='S' && $documento['CDG_DOC_ANU']=='S' && $documento['CDG_TIP_DOC']=='F' && $documento['CDG_COD_SNT']!='0003'){ //solo si no fue enviado
            $crear_gen = $documento['CDG_COD_GEN'];
            $crear_emp = $documento['CDG_COD_EMP'];
            $crear_num = $documento['CDG_NUM_DOC'];
            $crear_cla = $documento['CDG_CLA_DOC'];
            //crear_baja_factura($crear_gen,$crear_emp,$crear_num,$crear_cla);
        }
    }

    /*Comprobacion sunat cdg_cod_env=0003*/
    foreach ($documentos as $documento){
        if ($documento['CDG_ANU_SN']=='S' && $documento['CDG_DOC_ANU']=='S' && $documento['CDG_TIP_DOC']=='F' && $documento['CDG_COD_SNT']!='0003') { //solo si no fue enviado
            $crear_tip = $documento['CDG_TIP_DOC'];
            $crear_ser = $documento['CDG_SER_DOC'];
            $crear_num = $documento['CDG_NUM_DOC'];
            $crear_cod = $documento['CDG_COD_SNT'];
            $crear_cla = $documento['CDG_CLA_DOC'];
            $crear_emp = $documento['CDG_COD_EMP'];
            $crear_gen = $documento['CDG_COD_GEN'];
            $crear_anu_sn = $documento['CDG_ANU_SN'];
            $crear_doc_anu = $documento['CDG_DOC_ANU'];
            //comprobar_facturas($crear_tip, $crear_ser, $crear_num, $crear_cod, $crear_cla, $crear_emp, $crear_gen, $crear_anu_sn, $crear_doc_anu);
        }
    }

    // Envio de resumenes
    //enviar_resumen($gen,$emp,$fecha);


?>
