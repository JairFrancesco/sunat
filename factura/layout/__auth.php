<?php
    session_start();
    if (isset($_SESSION['valido'])){
        if ($_SESSION['valido'] != true){
            echo '<script type="text/javascript">window.location="factura/login.php";</script>';
        }
    }else {
        echo '<script type="text/javascript">window.location="factura/login.php";</script>';
    }

?>