<?php


//Descargamos el Archivo Response
$archivo_nombre = $carpeta."/C".$f1;
$archivo = fopen($archivo_nombre,'w+');
fputs($archivo,$result);
fclose($archivo);

/*LEEMOS EL ARCHIVO XML*/
$xml = simplexml_load_file($archivo_nombre);
foreach ($xml->xpath('//applicationResponse') as $response){ }

/*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
$cdr=base64_decode($response);
$archivo_resultado = $carpeta."/R-".$f5;
$archivo = fopen($archivo_resultado,'w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod($archivo_resultado, 0777);

$archive = new PclZip($archivo_resultado);
if ($archive->extract()==0) {
    die("Error : ".$archive->errorInfo(true));
}else{
    chmod($nombre_archivo, 0777);
}

/*Eliminamos el Archivo Response*/
unlink($archivo_nombre);