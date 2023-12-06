<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require '../email/PHPMailer/src/PHPMailer.php';
require '../email/PHPMailer/src/SMTP.php';
require '../email/PHPMailer/src/Exception.php';




$mail = new PHPMailer(true);
try {   
    
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ab9621932@gmail.com';
    $mail->Password = 'utxi qoqw pwdk ptki'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 465;

    $mail->setFrom('ab9621932@gmail.com','OnlineStore');
    $mail->addAddress('cg9554212@gmail.com','');//correo que va recibir el mensaje
    $cuerpo = '';
    if (!empty($resul)) {
    foreach ($resul as $fila) {
        $producto = $fila['Producto'];
        $importe = $fila['Importe'];
        $cantidad = $fila['Cantidad'];
        $fecha = $fila['Fecha'];
        $usuario = $fila['Usuario'];
    
        // Agregar información de cada producto al cuerpo del correo
        $cuerpo .= "<p>Detalles del producto: $producto, Cantidad: $cantidad, Importe: $importe, Fecha: $fecha, Usuario: $usuario</p>";
    }
} else {
    $cuerpo = '<p>No hay detalles de compra disponibles.</p>';
    }
    $mail->isHTML(true);
    $mail->Subject = 'Detalle de compra';
    //$cuerpo= '<h4>Gracias por su compra</h4>';
    /*$cuerpo .= '<p>El id de su compra es <b>'. $idVenta .'</b></p>';*/
    $mail->Body = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos detalles de su compra';
    $mail->setLanguage('es','./language/phpmailer.lang-es.php');
    $mail->send();
}catch(Exception $e) {
    echo "cayo aqui";
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>