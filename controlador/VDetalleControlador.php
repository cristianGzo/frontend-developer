<?php 
require_once '../models/vDetalleModel.php';
require_once '../models/conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if(isset($_GET['opc'])){
    $venta = new vDetalleModel();

    switch($_GET['opc']){
        case 1:
            //insert
            $venta->crearVDetalle();
            echo 'insertado xxd';
         break;
        case 2:
            $resul=$venta->obtenerId();
           /* if (!empty($resul)) {
                // Recorre todas las filas del resultado
                foreach ($resul as $fila) {
                    $producto = $fila['Producto'];
                    $importe = $fila['Importe'];
                    $cantidad = $fila['Cantidad'];
                    $fecha = $fila['Fecha'];
                    $usuario = $fila['Usuario'];
        
                    // Puedes hacer algo con cada conjunto de datos aquí
                    // Por ejemplo, enviar un correo para cada fila
                    include '../view/email.php';
                }
            } else {
                // Manejar el caso en que no hay datos
                echo "No se encontraron datos";
            }*/
            include '../view/email.php';
        }
    }else{
        header('Location: ../index.html');
    }
    
    ?>