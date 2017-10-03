<?php
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    include "../conexion.php";
    $fecha = date('Y-m-d');

    $sql_insert = "insert into tareas (NOMBRE,DESCRIPCION,FECHA) values ('".$nombre."','".$descripcion."',to_date('".$fecha."','yyyy-mm-dd'))";
    $stmt_insert = oci_parse($conn, $sql_insert);
    oci_execute($stmt_insert);
?>