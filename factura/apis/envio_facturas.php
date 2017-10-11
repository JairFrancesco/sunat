<?php

    /*Config Fecha Peru
    **********************/
    date_default_timezone_set('America/Lima');

    /*Parametros
    **********************/
    $fecha = '09-10-2017';
    $emp = '01';

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
      select * from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy')='09-10-2017' and cdg_cod_emp='01' and cdg_tip_doc in ('F')";
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

    /*Comprobacion sunat cdg_cod_env=001*/
    foreach ($documentos as $documento){
        
    }

?>