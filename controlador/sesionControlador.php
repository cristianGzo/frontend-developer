<?php
ob_start();
require_once '../models/sesionModel.php';
require_once '../models/conexion.php';
// Habilitar el reporte de todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
//echo "Se ha llegado a este punto del script.<br>";

if (isset($_GET['opc'])) {
    $sesion = new SesionModel();

    //echo "Se inicializó la clase SesionModel.<br>";

    switch ($_GET['opc']) {
        case 1:
            
            //var_dump($usuario); // Verificar lo que devuelve el método obtenerSesion
            //echo "Se intentó obtener la sesión.<br>";
            //intentar sesion desde el modelo y en el controlador simplemente recibir un true.

            if ($usuario = $sesion->obtenerSesion()) {
                //echo "antes del json";
                session_start();
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                /* var_dump($_SESSION);
                header('Location: ../cards.php');*/
                //echo "entro al if";
                //echo json_encode(array("success" => true));
                echo 1;
                exit();
            } else {
                //echo json_encode(["success" => false, "error" => "Ha ocurrido un error en la verificación"]);
                echo 2;
                exit();
            }
            break;

        case 2:
            session_start();
            session_destroy();
            echo "Sesión cerrada.<br>";
            //header('Location: ../login.php');
            //exit();
            break;
    }
} else {
    echo "No se encontró 'opc'. Se redirige al login.<br>";
    ob_end_clean();

    header('Location: ../login.php');
    exit();
}

//echo "Fin del script.<br>";
?>
