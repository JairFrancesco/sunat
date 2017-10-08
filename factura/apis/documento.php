<?php
    include "../__docs.php";
    $documento['serie'] = $serie;
    $documento['numero'] = $cab_doc_gen['CDG_NUM_DOC'];
    $documento['documento_noombre'] = $doc_nombre;

    /*Para el modal
    ******************/
    $documento['fecha'] = 'Fecha : '.$fecha;
    $documento['cliente'] = 'Cliente  : '.$cab_doc_gen['CDG_NOM_CLI'];
    $documento['doc_cliente'] = $tipo_doc.' : '.trim($cab_doc_gen['CDG_DOC_CLI']);
    $documento['direccion'] = 'Direccion : '.$cab_doc_gen['CDG_DIR_CLI'];
    $documento['pago'] = 'Forma de Pago : '.$forma_pago;
    $documento['ubigeo'] = 'Ubigeo : '.$ubigeo;


    /*Para el print
    ******************/
    $documento['fecha_print'] = $fecha;
    $documento['cliente_print'] = $cab_doc_gen['CDG_NOM_CLI'];
    $documento['tipo_doc_print'] = $tipo_doc;
    $documento['doc_cliente_print'] = trim($cab_doc_gen['CDG_DOC_CLI']);
    $documento['direccion_print'] = $cab_doc_gen['CDG_DIR_CLI'];
    $documento['pago_print'] = $forma_pago;
    $documento['ubigeo_print'] = $ubigeo;


    if ($cabezera_tipo == 0){

        $documento['cabezera_tipo'] = 0;

        /*Para el modal
        ******************/
        $documento['ord_tra'] = '';
        $documento['placa'] = '';
        $documento['modelo'] = '';
        $documento['chasis'] = '';
        $documento['color'] = '';
        $documento['km'] = '';

        /*Para el print
        ******************/
        $documento['ord_tra_print'] = '';
        $documento['placa_print'] = '';
        $documento['modelo_print'] = '';
        $documento['chasis_print'] = '';
        $documento['color_print'] = '';
        $documento['km_print'] = '';

    }elseif ($cabezera_tipo == 1){

        $documento['cabezera_tipo'] = 1;

        /*Para el modal
        ******************/
        $documento['ord_tra'] = 'Ord. Trab : '.$ord_trab;
        $documento['placa'] = 'Placa/Serie : '.$placa;
        $documento['modelo'] = 'Modelo/Año : '.$modelo_anho;
        $documento['chasis'] = 'Chasis : '.$motor_chasis;
        $documento['color'] = 'Color : '.$color;
        $documento['km'] = 'Km : '.$kilometraje;

        /*Para el print
        ******************/
        $documento['ord_tra_print'] = $ord_trab;
        $documento['placa_print'] = $placa;
        $documento['modelo_print'] = $modelo_anho;
        $documento['chasis_print'] = $motor_chasis;
        $documento['color_print'] = $color;
        $documento['km_print'] = $kilometraje;

    }
    $i = 0;
    $suma_import = 0.00;
    $suma_descuento = 0.00;
    $suma_venta = 0.00;
    foreach ($items as $item){
        $documento['items'][$i]['id'] = $i+1;
        $documento['items'][$i]['codigo'] = $item['codigo'];
        $documento['items'][$i]['descripcion'] = $item['descripcion'];
        $documento['items'][$i]['cantidad'] = $item['cantidad'];
        $documento['items'][$i]['unitario'] = $item['unitario'];
        $documento['items'][$i]['importe'] = $item['importe'];
        $documento['items'][$i]['descuento'] = $item['descuento'];
        $documento['items'][$i]['venta'] = $item['venta'];
        //echo $item['venta'].'<br>';
        $suma_import = $suma_import + $item['importe'];
        $suma_descuento = $suma_descuento + $item['descuento'];
        $suma_venta = $suma_venta + $item['venta'];
        $i++;
    }
    $documento['moneda'] = $moneda_nombre;
    $documento['total_sub'] = $subtotal;
    $documento['total_descuentos'] = $descuentos;
    $documento['total_gravadas'] = $gravadas;
    $documento['total_igv'] = $igv;
    $documento['total_total'] = $total;


    /*sumatorias
    ***************/
    if (count($items)>1) {
        $documento['suma_active'] = true;
        $documento['suma_import'] = number_format($suma_import,2,'.',',');
        $documento['suma_descuento'] = number_format($suma_descuento,2,'.',',');
        $documento['suma_venta'] = number_format($suma_venta,2,'.',',');
    }else{
        $documento['suma_active']=false;
        $documento['suma_import']=0;
        $documento['suma_descuento']=0;
        $documento['suma_venta']=0;
    }

    /*mensajes
    ***********/
    $documento['mensajes'] = $mensajes;
    if(strlen(trim($mensajes))>0) {
        $documento['mensaje_active'] = true;
    }else{
        $documento['mensaje_active'] = false;
    }

    /*Anulado
    **************/
    if ($cab_doc_gen['CDG_DOC_ANU']=='S' && $cab_doc_gen['CDG_ANU_SN']=='S'){
        $documento['anulado'] = 'ANU';
    }else{
        $documento['anulado'] = '';
    }

    /*Anticipo
    ******************/
    if(trim($cab_doc_gen['CDG_EXI_ANT'])=='AN'){
        $documento['anticipo']='ANTP';
        $documento['franquicia'] = '';
    }

    /*Franquicia
    *****************/
    if($cab_doc_gen['CDG_EXI_FRA']=='S' && $cab_doc_gen['CDG_EXI_ANT']!='AN'){
        $documento['anticipo']='';
        $documento['franquicia'] = 'FRA';
    }

    /*leyenda
    **************/
    $documento['leyenda'] = $letras;

    print_r(json_encode($documento,JSON_UNESCAPED_UNICODE));
?>