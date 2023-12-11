<?php
session_start();
require_once '../models/vDetalleModel.php';
require_once '../models/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../email/PHPMailer/src/PHPMailer.php';
require_once '../email/PHPMailer/src/SMTP.php';
require_once '../email/PHPMailer/src/Exception.php';

if (isset($_GET['opc'])) {
    $venta = new vDetalleModel();

    switch ($_GET['opc']) {
        case 1:
            //insert
            $venta->crearVDetalle();
            
            break;
        case 2:
            $resul = $venta->obtenerId();
            //echo $resul;
            $correoUsuario = $_SESSION['email'];
            $mail = new PHPMailer(true);

            try {
                // Configurar detalles del servidor SMTP
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ab9621932@gmail.com';
                $mail->Password = 'utxi qoqw pwdk ptki';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Configurar remitente y destinatario
                $mail->setFrom('ab9621932@gmail.com', 'OnlineStore');
                $mail->addAddress($correoUsuario, '');

                // Configurar cuerpo del correo

                $cuerpo = '<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h2>Detalles de la compra</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Costo unitario</th>
                <th>Fecha</th>
                <th>Tu usuario</th>
            </tr>';
                if (!empty($resul)) {
                    foreach ($resul as $fila) {
                        $producto = $fila['Producto'];
                        $importe = $fila['Importe'];
                        $cantidad = $fila['Cantidad'];
                        $fecha = $fila['Fecha'];
                        $usuario = $fila['Usuario'];

                        // Agregar información de cada producto al cuerpo del correo
                        $cuerpo .= "<tr>
                        <td>$producto</td>
                        <td>$cantidad</td>
                        <td>$importe</td>
                        <td>$fecha</td>
                        <td>$usuario</td>
                    </tr>";
                    }
                } else {
                    $cuerpo .= '<tr><td colspan="5">No hay detalles de compra disponibles.</td></tr>';
                }
                $cuerpo .= '</table></body></html>';

                // Configurar correo como HTML
                $mail->isHTML(true);

                // Configurar asunto y cuerpo del correo
                $mail->Subject = 'Detalle de compra';
                $mail->Body = utf8_decode($cuerpo);
                $mail->AltBody = 'Le enviamos detalles de su compra';

                // Configurar idioma del correo
                $mail->setLanguage('es', './language/phpmailer.lang-es.php');

                // Enviar correo
                $mail->send();

                echo 'Correo enviado con éxito';
            } catch (Exception $e) {
                echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
            }
            break;
    }
} else {
    header('Location: ../index.html');
}
