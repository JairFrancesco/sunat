<?php
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    include "../conexion.php";

    $sql_insert = "insert into tareas (NOMBRE,DESCRIPCION,FECHA) values (to_date('".$_GET['fecha']."','yyyy-mm-dd'),'".$ticket."','".$not[2]."','".$not[0]."','".$not[1]."','".$not['subtotal']."','".$not['descuento']."','".$not['gravada']."','".$not['igv']."','".$not['total']."','".$codigo."','".$emp."')";
    $stmt_insert = oci_parse($conn, $sql_insert);
    oci_execute($stmt_insert);
?>