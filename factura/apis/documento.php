<?php
    include "../__docs.php";
    $documento['fecha'] = 'Fecha : '.$fecha;
    $documento['cliente'] = 'Cliente  : '.$cab_doc_gen['CDG_NOM_CLI'];
    $documento['doc_cliente'] = $tipo_doc.' : '.trim($cab_doc_gen['CDG_DOC_CLI']);
    $documento['direccion'] = 'Direccion : '.$cab_doc_gen['CDG_DIR_CLI'];
    $documento['pago'] = 'Forma de Pago : '.$forma_pago;
    $documento['ubigeo'] = 'Ubigeo : '.$ubigeo;

    if ($cabezera_tipo == 0){
        $documento['ord_tra'] = '';
        $documento['placa'] = '';
        $documento['modelo'] = '';
        $documento['chasis'] = '';
        $documento['color'] = '';
        $documento['km'] = '';
    }elseif ($cabezera_tipo == 1){
        $documento['ord_tra'] = 'Ord. Trab : '.$ord_trab;
        $documento['placa'] = 'Placa/Serie : '.$placa;
        $documento['modelo'] = 'Modelo/Año : '.$modelo_anho;
        $documento['chasis'] = 'Motor/Chasis : '.$motor_chasis;
        $documento['color'] = 'Color : '.$color;
        $documento['km'] = 'Km : '.$kilometraje;
    }
    $i = 0;
    foreach ($items as $item){
        $documento['items'][$i]['id'] = $i+1;
        $documento['items'][$i]['codigo'] = $item['codigo'];
        $documento['items'][$i]['descripcion'] = $item['descripcion'];
        $documento['items'][$i]['cantidad'] = $item['cantidad'];
        $documento['items'][$i]['unitario'] = $item['unitario'];
        $documento['items'][$i]['importe'] = $item['importe'];
        $documento['items'][$i]['descuento'] = $item['descuento'];
        $documento['items'][$i]['venta'] = $item['venta'];
        $i++;
    }
    $documento['moneda'] = $moneda_nombre;
    $documento['total_sub'] = $subtotal;
    $documento['total_descuentos'] = $descuentos;
    $documento['total_gravadas'] = $gravadas;
    $documento['total_igv'] = $igv;
    $documento['total_total'] = $total;
    $documento['mensajes'] = $mensajes;




    print_r(json_encode($documento,JSON_UNESCAPED_UNICODE));
?>