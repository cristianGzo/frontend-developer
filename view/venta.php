<?php
include '../models/conexion.php';
include '../models/VentaModel.php';

$conexion = new Conexion();
$conn = $conexion->conectar();
$ventaModel = new VentaModel();

$ventas = $ventaModel->obtenerVenta();

$precioVenta = $ventaModel->costoCarrito();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Resumen de Venta</title>
    <style>
        :root {
            --white: #FFFFFF;
            --black: #000000;
            --very-light-pink: #C7C7C7;
            --text-input-field: #F7F7F7;
            --hospital-green: #ACD9B2;
            --sm: 14px;
            --md: 16px;
            --lg: 18px;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--white);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .resumen-container {
            background-color: var(--white);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 24px;
            border-radius: 8px;
            text-align: center;
        }

        .resumen-container h1 {
            font-size: var(--lg);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .resumen-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .resumen-item p {
            font-size: var(--md);
        }

        .total {
            font-size: var(--lg);
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout-button {
            background-color: var(--hospital-green);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: var(--md);
            margin-top: 20px;
        }
    </style>
    <script>
        function VentaDetalle_correo() {
            $.ajax({
                type: "POST",
                data: {},
                url: "../controlador/VDetalleControlador.php?opc=1",
                success: function(data) {
                    console.log("Correo enviado");
                }
            });
        }
    </script>
    <script>
        function crearVD(idV, idP, cantidad, precio) {
            $.ajax({
                type: "POST",
                data: {
                    idVenta: idV,
                    idProducto: idP,
                    cantidad: cantidad,
                    total: precio
                },
                url: "../controlador/VDetalleControlador.php?opc=1",
                success: function(data) {
                    console.log(data);
                    console.log("Creadas");
                }
            });
        }
        $(document).ready(function() {});
    </script>
    <script>
        function correo() {
            $.ajax({
                type: "POST",
                data: {},
                url: "../controlador/VDetalleControlador.php?opc=2",
                success: function(data) {
                    console.log(data);
                    console.log("Correo enviado");
                }
            });
        }
        $(document).ready(function() {});
    </script>
    <?php
    foreach ($ventas as $venta) {
        echo '<script>';
        echo 'crearVD(' . $venta['ID'] . ', ' . $venta['IDP'] . ', ' . $venta['Cantidad'] . ', ' . $venta['Precio_Producto'] . ');';
        echo '</script>';
    }
    ?>
</head>

<body>
    <!--<form id="formProcederPago" method="post">-->
        <div class="resumen-container">
            <h1>Resumen de Venta</h1>
            <?php
            $numeroDeOrdenImpreso = false;
            $usuarioImpreso = false;
            foreach ($ventas as $venta) {

                if (!$numeroDeOrdenImpreso) {
                    echo '<div class="resumen-item">';
                    echo '<p>Numero de orden:</p>';
                    echo '<p>' . $venta['ID'] . '</p>';
                    echo '</div>';
                    $numeroDeOrdenImpreso = true; // Marca que se ha impreso
                }

                // Verifica si el usuario aún no se ha impreso
                if (!$usuarioImpreso) {
                    echo '<div class="resumen-item">';
                    echo '<p>Usuario:</p>';
                    echo '<p>' . $venta['usuario'] . '</p>';
                    echo '</div>';
                    echo '<p>Productos:</p>';
                    $usuarioImpreso = true; // Marca que se ha impreso
                }

                echo '<div class="resumen-item">';
                echo '<p>' . $venta['Cantidad'] . ' ' . $venta['producto'] . ' Precio unitario: $' . $venta['Precio_Producto'] . '</p>';
                echo '<p>Importe: $' . $venta['costo'] . '</p>';
                echo '</div>';
            } ?>
            <p class="total">Total a pagar: $ <?php echo $precioVenta[0]['total_a_pagar']; ?></p>
            <button class="checkout-button" onclick="correo()">Proceder al Pago</button>
        </div>
    <!--</form>-->
</body>

</html>