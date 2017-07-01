<?php
$file = $_GET['file'];
$nombre = $_GET['nombre'];
header("Content-disposition: attachment; filename=".$nombre);
header("Content-type: MIME");
readfile($file);
?>