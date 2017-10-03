<?php
    session_start();
    if ($_SESSION['valido'] != true){
        echo '<script type="text/javascript">window.location="factura/login.php";</script>';
    }
?>