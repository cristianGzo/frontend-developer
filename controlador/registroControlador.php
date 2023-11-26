<?php
require_once '../models/registroModel.php';
require_once '../models/conexion.php';


if (isset($_GET['opc'])) {
    $reg = new RegistroModel();

    switch ($_GET['opc']) {
        case 1:
            if( $reg->crearUsr()){
                echo 1;
                exit();
            }else{
                echo 2;
                exit();
            }
            //echo $usuario;
            //header('Location: pruea.html');
    }
} else {
    echo "No registrado";
    header('Location: ../createAccount.html');
}
?>