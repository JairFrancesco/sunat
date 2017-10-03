<?php
    include "../conexion.php";
    $sql_tareas = "SELECT * FROM (SELECT * FROM tareas ORDER BY fecha DESC ) WHERE ROWNUM <= 10";
    $sql_parse = oci_parse($conn,$sql_tareas);
    oci_execute($sql_parse);
    oci_fetch_all($sql_parse, $tareas, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    print_r(json_encode($tareas,JSON_UNESCAPED_UNICODE))
    //print_r($tareas);

?>