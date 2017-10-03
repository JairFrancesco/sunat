<?php
    ini_set("session.cookie_lifetime","31536000");
    ini_set("session.gc_maxlifetime","31536000");
    if (isset($_POST['user']) && isset($_POST['password'])){
        include "conexion.php";
        $user = $_POST['user'];
        $password = $_POST['password'];

        $sql_usuario = "select * from usuarios where user_email='".$user."' and user_pass='".$password."'";
        $sql_parse = oci_parse($conn,$sql_usuario);
        oci_execute($sql_parse);
        oci_fetch_all($sql_parse, $usuario, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        if (count($usuario) > 0){
            session_start();
            $_SESSION['user']=$user;
            $_SESSION['password']=$password;
            $_SESSION['valido']=true;
            $_SESSION['gen'] = $usuario[0]['USER_COD_GEN'];
            $_SESSION['emp'] = $usuario[0]['USER_COD_EMP'];
            echo '<script type="text/javascript">window.location="../index.php?emp='.$_SESSION['emp'].'";</script>';
        }else{
            echo '<script type="text/javascript">window.location="login.php";</script>';
        }

    }
?>