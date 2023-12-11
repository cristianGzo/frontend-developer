<?php
include '../models/conexion.php';
include '../models/VentaModel.php';
include '../models/CarritoModel.php';

$conexion = new Conexion();
$conn = $conexion->conectar();
$ventaModel = new VentaModel();
$carritoModel = new CarritoModel();
$idPaypalGlobal = '';

$ventas = $ventaModel->obtenerVenta();
//$precioVenta = $ventaModel->costoCarrito();
$precioVenta = $carritoModel->costoCarrito();

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
    <script src="https://www.paypal.com/sdk/js?client-id=AUgI780GYylmQo6kn27m_iGjtRmtlKpq7F19NkwFQsKzuGLSL2or8bdgRgoBBWL7gzwYym4q1gbDXWbL&currency=MXN">
    </script>
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
        function crearVD(idV, idP, cantidad, precio, idPaypal) {
            $.ajax({
                type: "POST",
                data: {
                    idVenta: idV,
                    idProducto: idP,
                    cantidad: cantidad,
                    total: precio,
                    idPaypal: idPaypal
                },
                url: "../controlador/VDetalleControlador.php?opc=1",
                success: function(data) {
                    console.log(data);
                    console.log("Creadas");
                },
                error: function(xhr, status, error) {
                    console.log("Error en la solicitud AJAX");
                    console.log(xhr.responseText);
                    // Manejar el error de acuerdo a tus necesidades
                }
            });   
        }
        function actualizarVenta(idV, idPaypal) {
                $.ajax({
                    type: "POST",
                    data: {
                        idVenta: idV,
                        idPaypal: idPaypal
                    },
                    url: "../controlador/ventaControlador.php?opc=2",
                    success: function(data) {
                        console.log(data);
                        console.log("ActualizadoV");
                    },
                    error: function(xhr, status, error) {
                        console.log("Error en la solicitud AJAX");
                        console.log(xhr.responseText);
                        // Manejar el error de acuerdo a tus necesidades
                    }
                });
            }
        //$(document).ready(function() {});
    </script>

    <?php /*
    foreach ($ventas as $venta) {
        echo '<script>';
        echo 'crearVD(' . $venta['ID'] . ', ' . $venta['IDP'] . ', ' . $venta['Cantidad'] . ', ' . $venta['Precio_Producto'] . ');';
        echo '</script>';
    }*/
    ?>
    <script>
        function procederAlPago(idPaypal) {
            // Lógica adicional, como llamar a la función 'correo()'
            //correo();
            <?php $ventasAc = $ventaModel->obtenerVenta(); ?>
            var idVenta;
            <?php foreach ($ventasAc as $ventaA) : ?>
                idVenta = <?php echo $ventaA['ID']; ?>;
                var idProducto = <?php echo $ventaA['IDP']; ?>;
                var cantidad = <?php echo $ventaA['Cantidad']; ?>;
                var precio = <?php echo $ventaA['Precio_Producto']; ?>;

                // Llamar a la función crearVD con los parámetros correctos
                crearVD(idVenta, idProducto, cantidad, precio, idPaypal);

                console.log(idProducto);
                console.log("Producto");
            <?php endforeach; ?>

            actualizarVenta(idVenta, idPaypal);
        }
    </script>

    <script>
        function borrarContenidoCarrito() {
            $.ajax({
                type: "POST",
                data: {},
                url: "../controlador/CarritoControlador.php?opc=3",
                success: function(data) {
                    console.log(data);

                }
            });
        }
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
        <!--<button class="checkout-button" onclick="procederAlPago()">Proceder al Pago</button>-->
        <div id="paypal-button-container"></div>

    </div>
    <!--</form>-->
</body>
<script>
    var idPaypalGlobal;
    paypal.Buttons({
        style: {
            color: 'blue',
            shape: 'pill',
            label: 'pay'
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: <?php echo $precioVenta[0]['total_a_pagar']; ?> // Make sure to use a string for the value
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            actions.order.capture().then(function(details) {
                // Extract values from the details object
                var purchaseUnit = details.purchase_units[0];
                var amount = purchaseUnit.amount.value;
                var currencyCode = purchaseUnit.amount.currency_code;
                var estado = details.status;
                // Log or use the extracted values
                var id = details.id;
                procederAlPago(details.id);
                console.log(details);
                console.log("Amount:", amount);
                console.log("Currency Code:", currencyCode);
                console.log("estado:", estado);
                console.log('id', id);

                idPaypalGlobal = id;
                correo();

                window.location.href = '../cards.php';

                borrarContenidoCarrito();
            });
        },
        // Payment canceled
        onCancel: function(data) {
            alert('Payment canceled');
            console.log(data);
        }
    }).render('#paypal-button-container');
</script>

</html>