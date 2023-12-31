<?php
session_start();

// Verificar si el usuario tiene la sesión activa
if (!isset($_SESSION['idUsuario'])) {
  // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
  header("Location: ./login.php");
  exit();
}
include './models/conexion.php';
include './models/CarritoModel.php';

$conexion = new Conexion();
$conn = $conexion->conectar();
$carritoModel = new CarritoModel();

$carrito = $carritoModel->obtenerCarrito();
$precioCarrito = $carritoModel->costoCarrito();
$userId = $_SESSION['idUsuario'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
  <title>Document</title>
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

    nav {
      display: flex;
      justify-content: space-between;
      padding: 0 24px;
      border-bottom: 1px solid;
      margin-bottom: 0px;
      margin-top: 20px;
      background-color: #D6FFB7;
      z-index: 1;
    }

    .menu {
      display: flex;
      align-items: center;

      /* Centra los elementos verticalmente */
    }

    .nav-title {
      flex-grow: 1;
      text-align: center;
      margin: 0;
      display: flex;
      align-items: center;
      /* Centra los elementos verticalmente */
      justify-content: center;
    }

    .logo {
      width: 100px;
      padding-left: 60px;
      height: auto;
    }

    body {
      /*display: flex;
      justify-content: center;
      /*align-items: center;*/
      height: 100vh;
      margin: 0;
      font-family: 'Quicksand', sans-serif;
    }

    .product-detail {
      /*width: 360px;*/

      width: 80%;
      padding: 24px;
      max-width: 600px;
      box-sizing: border-box;
      margin: 0 auto;
      /*position: absolute;
      margin-top: 130px;*/
      /*right: 0;*/
    }

    .title-container {
      display: flex;
    }

    .title-container img {
      transform: rotate(180deg);
      margin-right: 14px;
    }

    .title {
      font-size: var(--lg);
      font-weight: bold;
    }

    .order {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 16px;
      align-items: center;
      background-color: var(--text-input-field);
      margin-bottom: 24px;
      border-radius: 8px;
      padding: 0 24px;
    }

    .order p:nth-child(1) {
      display: flex;
      flex-direction: column;
    }

    .order p span:nth-child(1) {
      font-size: var(--md);
      font-weight: bold;
    }

    .order p:nth-child(2) {
      text-align: end;
      font-weight: bold;
    }

    .shopping-cart {
      display: grid;
      grid-template-columns: auto auto auto auto auto;
      gap: 16px;
      margin-bottom: 24px;
      align-items: center;
    }

    .shopping-cart figure {
      margin: 0;
    }

    .shopping-cart figure img {
      width: 70px;
      height: 70px;
      border-radius: 20px;
      object-fit: cover;
    }

    .shopping-cart p:nth-child(2) {
      color: var(--very-light-pink);
    }

    .shopping-cart p:nth-child(3) {
      font-size: var(--md);
      font-weight: bold;
    }

    .primary-button {
      background-color: var(--hospital-green);
      border-radius: 8px;
      border: none;
      color: var(--white);
      width: 100%;
      cursor: pointer;
      font-size: var(--md);
      font-weight: bold;
      height: 50px;
    }

    @media (max-width: 640px) {
      .product-detail {
        width: 100%;
      }
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function eliminar(idCarrito, idProducto) {
      $.ajax({
        type: "POST",
        url: "./controlador/CarritoControlador.php?opc=2",
        data: {
          idCarrito: idCarrito,
          idProducto: idProducto
        },
        success: function(data) {

          window.location.reload();
          //aqui se puede decir que ya se elimino
          $('#my-order-content').html(data);
          if ($('#my-order-content').find('.shopping-cart').length === 0) {
            $('#totalPrice').text('0.00'); // o cualquier valor que desees
            $('#order').hide(); // Oculta la sección de pedido
          }
        },
        error: function(xhr, status, error) {
          alert("Error al eliminar el producto: " + error);
        },
      })
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Agrega un evento click al botón
      document.getElementById('checkout-button').addEventListener('click', function() {
        // Redirige al usuario a la página HTML de destino
        window.location.href = './view/venta.php';
      });
    });
  </script>
  <script>
    function actualizarCantidad(idCarrito, nuevaCantidad) {
      $.ajax({
        type: "POST",
        url: "./controlador/CarritoControlador.php?opc=5", // Supongamos que el código 3 es para actualizar la cantidad
        data: {
          idCarrito: idCarrito,
          nuevaCantidad: nuevaCantidad
        },
        success: function(data) {
          // Manejar la respuesta del servidor si es necesario
          window.location.reload(); // Actualizar la página después de la actualización
        },
        error: function(xhr, status, error) {
          alert("Error al actualizar la cantidad: " + error);
        },
      });
    }
  </script>
</head>

<body>
  <nav class="navbar-left">
    <h1 class="nav-title">Carrito de compras</h1>
    <div class="menu">
      <a href="./cards.php">
        <img src="./logos/Log.png" alt="logo" class="logo">
      </a>
    </div>

  </nav>

  <aside class="product-detail">
    <div class="title-container">
      <a href="./cards.php">
        <img src="./icons/flechita.svg" alt="arrow">
      </a>
      <p class="title">My order</p>
    </div>

    <div class="my-order-content" id="my-order-content">
      <?php foreach ($carrito as $item) { ?>
        <div class="shopping-cart">
          <figure>
            <img src="./images/<?php echo $item['imagen_producto']; ?>" alt="bike">
          </figure>
          <p><?php echo $item['nombre_producto']; ?></p>
          <p><?php echo $item['suma_precio']; ?></p>
          <div class="shopping-cart-details">
            <p>Calidad:<?php echo $item['cantidad']; ?></p>
            <input type="number" id="cantidad-<?php echo $item['idCarrito']; ?>" value="<?php echo $item['cantidad']; ?>" onchange="actualizarCantidad(<?php echo $item['idCarrito']; ?>, this.value)">
          
          </div>
          <img src="./icons/icon_close.png" alt="close" onclick="eliminar(<?php echo $item['idCarrito']; ?>, '<?php echo $item['idProducto']; ?>')">
        </div>
      <?php } ?>
      <?php if (empty($carrito)) { ?>
        <p>El carrito está vacío.</p>
      <?php } ?>

    </div>
    <!--
      <div class="shopping-cart">
        <figure>
          <img src="https://images.pexels.com/photos/276517/pexels-photo-276517.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" alt="bike">
        </figure>
        <p>Bike</p>
        <p>$30,00</p>
        <img src="./icons/icon_close.png" alt="close">
      </div>
      -->
    <div class="order" id="order" <?php echo empty($carrito) ? 'style="display: none;"' : ''; ?>>
      <p>
        <span>Total</span>
      </p>
      <p id="totalPrice"><?php echo $precioCarrito[0]['total_a_pagar']; ?></p>
    </div>

    <button class="primary-button" id="checkout-button">
      Checkout
    </button>

    </div>
    </div>
  </aside>

  <script>
    $(document).ready(function() {
      $('.primary-button').on('click', function() {
        var userId = <?php echo $userId; ?>;
        var fecha = fechaActual();
        var total = <?php echo $precioCarrito[0]['total_a_pagar']; ?>;

        // Realiza una solicitud al servidor para agregar a pedido
        var requestData = {
          idUsuario: userId,
          fecha: fecha,
          total: total
        };
        $.post('./controlador/ventaControlador.php?opc=1', requestData,
          function(data) {
            //  manejar la respuesta del servidor ;
          });
      });
    });

    function fechaActual() {
      var fecha = new Date();
      var dia = fecha.getDate();
      var mes = fecha.getMonth() + 1; // Se suma 1 ya que los meses comienzan desde 0 (enero).
      var anio = fecha.getFullYear();

      // Formatea la fecha como 'YYYY-MM-DD'
      if (mes < 10) mes = '0' + mes;
      if (dia < 10) dia = '0' + dia;

      return anio + '-' + mes + '-' + dia;
    }
  </script>
</body>

</html>