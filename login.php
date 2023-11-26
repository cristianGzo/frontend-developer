<?php
require "./models/conexion.php";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "Login incorrecto. Por favor, intenta de nuevo.";
}
session_start();

if (isset($_SESSION['idUsuario'])) {
    $idUsuario = $_SESSION['idUsuario'];
    $nombre = $_SESSION['nombre'];
    $email = $_SESSION['email'];

    // Puedes mostrar los datos o utilizarlos según tu lógica
    echo "ID de Usuario: $idUsuario<br>";
    echo "Nombre: $nombre<br>";
    echo "Email: $email<br>";
} else {
    echo "No se ha iniciado sesión o no hay información de usuario en la sesión.";
    // Maneja este escenario según tu lógica de la interfaz de usuario.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--links para utilizar tipo de letra-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sesion() {
            var formData = $('#login').serialize();
            $.ajax({
                type: "POST",
                data: formData,
                url: "./controlador/sesionControlador.php?opc=1",
                cache:false,
                //dataType: "json",
                success: function(data) {
                    /*console.log("Respuesta AJAX exitosa");
                        console.log(data);
                    if (data && data.success) {
                        console.log("Tipo de datos: " + typeof data);
                        console.log("Redireccionando a cards.php...");
                        window.location.href = './cards.php'; // Redirecciona si la verificación es exitosa
                    } else {
                        //console.log("Error: " + data.error);
                        console.log("Respuesta AJAX exitosa, pero error en la verificación");
                        alert(data.error); // Muestra un mensaje de error si la verificación falla
                    }*/
                    console.log(data);
                    if(data==1){
                        console.log(data);
                        window.location.href = './cards.php';
                    }else{
                        alert("no funciiono"); 
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error en la solicitud AJAX");
                    console.log("Error en la solicitud AJAX");
                    console.log(xhr.responseText);
                     // Opcional, puedes manejar mensajes de error aquí
                    alert("Ha ocurrido un error. Por favor, revisa las credenciales e intenta nuevamente.");
                }
            });
        }
        $(document).ready(function() {
            $('#login').submit(function(event) {
                event.preventDefault(); // Evita el envío del formulario por defecto
                sesion(); // Llama a la función insertar() para enviar los datos del formulario
            });
        });
    </script>
</head>

<style>
    /*class que contiene todo*/
    .login {
        width: 100%;
        height: 100vh;
        display: flex;
        /*utilizado por flex para alinear vertical*/
        align-items: center;
        /*utilizado por flex para alinear horizontalmente
        o eje principal*/
        justify-content: center;
    }

    .container {
        /*container, contiene al form y al boton
        alinea los elementos en el centro de el div que la contiene(div login en este caso)*/
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 350px;
        width: 300px;
    }

    .form {
        display: flex;
        flex-direction: column;

        height: 250px;
        width: 300px;
        /*da separacion entre los elementos*/
        /*gap: 10px;*/
    }

    .email {
        border: none;
        background-color: ghostwhite;
        height: 30px;
        font-size: 16px;
        padding: 6px;
        margin-bottom: 5%;
    }

    .password {
        border: none;
        background-color: ghostwhite;
        height: 30px;
        font-size: 16px;
        padding: 6px;
    }

    .label {
        font-size: 14px;
        font-weight: bold;
    }

    .btn-login {
        width: 100%;
        margin-top: 14px;
        background-color: #ACD9B2;
        color: white;
        border: none;
        border-radius: 8px;
        /*para que se ponga la manita sobre el boton*/
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        height: 50px;

    }

    .forgot {
        text-decoration: none;
        color: #ACD9B2;
        display: flex;
        justify-content: center;
    }

    .btn-sign {
        border-radius: 8px;
        background-color: white;
        color: #ACD9B2;
        border-color: #ACD9B2;
        width: 230px;
        height: 50px;
        margin-top: 20px;

    }

    .logo {
        display: none;
        margin-bottom: 40px;
        width: 150px;
        align-self: center;
    }

    @media (max-width: 640px) {
        .logo {
            display: block;
            /*para mostrr el logo en taamañp movil*/

        }

        .btn-sign {
            position: absolute;
            width: inherit;
            bottom: 0%;
        }

    }
</style>

<body>
    <div class="login">
        <div class="container">
            <form class="form" id="login" method="post">
                <img src="./logos/Log.png" alt="logo" class="logo">
                <label for="email" class="label">Email address</label>
                <input type="text" class="email" id="email" placeholder="example@gmail.com" autocomplete="email" name="email">
                <label for="password" class="label">Password</label>
                <input type="password" class="password" id="password" placeholder="input your password" autocomplete="current-password" name="password">

                <button class="btn-login">Log in</button>

                <a href="./newPassword.html" class="forgot">Forgot your password</a>
            </form>
            <button class="btn-sign" onclick="window.location.href = './createAccount.html';">Sign up</button>
        </div>
    </div>

</body>

</html>