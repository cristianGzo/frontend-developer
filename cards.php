<?php
session_start();
$rolUsuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
include './models/conexion.php';
include './models/product_model.php';
require './models/CarritoModel.php';

$productModel = new ProductModel();
$productos = $productModel->obtenerProductos();
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
    <link rel="stylesheet" href="./styles/styleNav.css">
    <link rel="stylesheet" href="./styles/foot.css">
    <!--para el foot -->

    <hr>
    <!--ETILOS DEL FOOT-->

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

        body {
            margin: 0;
            font-family: 'Quicksand', 'sans-serif';
        }

        .cards-container {
            display: grid;
            /*para el numero de columnas que se van a mostrar en este caso se establece en automatico con tamaño de 240px*/
            grid-template-columns: repeat(auto-fill, 240px);
            /*repeat, repite un patrón especificado un número determinado de veces utiliza 2 argumentos, el primero es el num de veces que se repite y el segundo es el propio patron */
            /*para establecer espacio entre las tarjetas, para que no esten pegadas*/
            gap: 26px;
            place-content: center;
        }

        .product-card {
            width: 240px;
        }

        .nav-profile {
            position: relative;
            display: inline-block;
        }

        .nav-profile img {
            width: 50px;
            height: 50px;
        }

        .product-card img {
            width: 100%;
            height: 240px;
            border-radius: 20px;
            /*para que la imagen original se adapte al tamaño que se le da en css, sin deformarse*/
            object-fit: cover;
        }

        .info-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
        }

        /*en la siguiente linea quiere decir en la clase info-card en su div y en el primer parrfo de ese div aplicar los estilos*/
        .info-card div p:nth-child(1) {
            font-weight: bold;
            font-size: var(--md);
            margin-top: 0;
            margin-bottom: 4px;
        }

        .info-card div p:nth-child(2) {

            font-size: var(--sm);
            margin-top: 0;
            margin-bottom: 0;
            color: var(--very-light-pink);
        }

        .product-card figure {
            margin: 0;
        }

        .product-card figure img {
            width: 35px;
            height: 35px;
        }

        .add-to-cart-button {
            border: none;
            background-color: transparent;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 12px;
            z-index: 1;
        }

        .nav-profile:hover .dropdown-content {
            display: block;
        }


        @media (max-width: 640px) {
            .cards-container {
                grid-template-columns: repeat(auto-fill, 140px);
            }

            .product-card {
                width: 140px;
            }

            .product-card img {
                width: 140px;
                height: 140px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function logout() {
            $.ajax({
                type: "POST",
                data: {},
                url: "./controlador/sesionControlador.php?opc=2",
                success: function(data) {
                    console.log("Sesión Cerrada");
                    window.location.replace('./login.php');
                }
            });
        }
    </script>
</head>

<body>



    <nav>
        <img src="./icons/icon_menu.svg" alt="menu" class="menu">
        <div class="navbar-left">
            <img src="./logos/Log.png" alt="logo" class="logo">

            <ul>
                <li>
                    <a href="/">All</a>
                </li>
                <li>
                    <a href="/">Clothes</a>
                </li>
                <li>
                    <a href="/">Electronics</a>
                </li>
                <li>
                    <a href="/">Furnitures</a>
                </li>
                <li>
                    <?php
                    // Condición para mostrar la opción de "Add Product" solo si el usuario tiene un rol admin
                    if ($rolUsuario == 'Administrador') {
                        echo '<li><a href="./view/adminDashboard.php">Ghrapics</a></li>';
                    }
                    ?>
                    <!-- <a href="./view/adminDashboard.php">Ghrapics</a>-->
                </li>
            </ul>
        </div>

        <?php
        // Verificar si el usuario tiene la sesión
        if (isset($_SESSION['rol'])) {
            $rolUsuario = $_SESSION['rol'];
            //if ($_SESSION['rol'] === 'Administrador') {
        ?>

            <div class="navbar-right" id="menu-container">
                <ul>
                    <li class="nav-profile">
                        <img src="./images/perfil.png" alt="Profile">
                        <div class="dropdown-content">
                            <p><?php echo $_SESSION['nombre']; ?></p>
                            <p><?php echo $_SESSION['email']; ?></p>
                            <div>
                                <p>
                                    <a href="./view/historialView.html" class="estilo">Tus compras</a>
                                </p>
                                <p><a href="#" onclick="logout();" class="estilo">Cerrar sesión</a></p>
                            </div>
                        </div>
                    </li>
                    <li class="nav-email"><?php echo $_SESSION['email']; ?></li>

                    <li class="navbar-cart">
                        <a href="./shopping-cart.php" class="cart-link">
                            <img src="./icons/icon_shopping_cart.svg" alt="">
                        </a>
                    </li>
                    <div>2</div>
                </ul>
            </div>
        <?php
            //}
        } else {
            ?>
            <div class="navbar-right" id="menu-container">
            <ul>
                <li>
                    <a href="./login.php" >Iniciar Sesión</a>
                </li>
                <li>
                    <a href="./createAccount.html">Registrarse</a>
                </li>
                <li class="navbar-cart">
                        <a href="./login.php" class="cart-link">
                            <img src="./icons/icon_shopping_cart.svg" alt="">
                        </a>
                    </li>
            </ul>
        </div>

        <?php
            }
        ?>
    </nav>
    <section class="main-container">
        <div class="cards-container">

            <?php foreach ($productos as $producto) { ?>
                <div class="product-card">
                    <img src="images/<?php echo $producto['imagen']; ?>" alt="" class="product">
                    <div class="info-card">
                        <div>
                            <p>$<?php echo $producto['precio']; ?></p>
                            <p><?php echo $producto['nombre']; ?></p>
                        </div>
                        <button class="add-to-cart-button" data-product-id="<?php echo $producto['idProducto']; ?>">
                            <img src="./icons/bt_add_to_cart.svg" alt="Add to cart" style="width: 20px; height: 20px;">
                        </button>
                    </div>
                </div>
            <?php } ?>

            <!--
            <div class="product-card">
                <img src="https://images.pexels.com/photos/255934/pexels-photo-255934.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="" class="product">
                <div class="info-card">
                    <div>
                        <p>$12000</p>
                        <p>Bike</p>
                    </div>
                    <button class="add-to-cart">
                        <img src="./icons/bt_add_to_cart.svg" alt="Add to cart" style="width: 20px; height: 20px;">
                    </button>
                </div>    
            </div>
            -->
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('.add-to-cart-button').on('click', function() {
                var productId = $(this).data('product-id');
                //var userId = 1;
                var cantidad = 1;
                var fechaActualizacion = fechaActual();


                // Realiza una solicitud al servidor para agregar el producto al carrito
                var requestData = {
                    //idUsuario: userId,
                    idProducto: productId, // Cambiando idProducto a product_id
                    cantidad: cantidad,
                    fechaAgregado: fechaActualizacion
                };
                $.post('./controlador/CarritoControlador.php?opc=1', requestData,
                    function(data) {
                        // Puedes manejar la respuesta del servidor aquí, por ejemplo, mostrar un mensaje de éxito.
                        alert('Product agregado al carrito' + requestData.fechaAgregado);
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


    <!--*****************FOOTER*****************+-->

    <div class="container-fluid">
        <div class="row footer-top">
            <div class="col-sm-4 text-center">
                <h4 class="ft-text-title">Media Name</h4>
                <h6 class="ft-desp">Company Name
                    <br>Country Name
                </h6>
                <h4 class="details">
                    <a class="contact" href="tel:+977-1-4107223">
                        <i class="fa fa-phone" aria-hidden="true"></i> +977-000000</a>
                </h4>
            </div>
            <div class="col-sm-4 text-center border-left">
                <h4 class="ft-text-title">Our Team</h4>
                <div class="address-member">
                    <p class="member">
                        <b>Director: TECNM</b>
                    </p>
                    <p class="member">
                        <b>Editor: Web</b> :
                    </p>
                    <p class="member">
                        <b>Reporter</b> :
                    </p>
                    <p class="member">
                        <b>Reporter</b> :
                    </p>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 text-center border-left">
                <h4 class="ft-text-title">About</h4>
                <div class="pspt-dtls">
                    <a href="./aboutUs.html" class="about">About</a>
                    <a href="#" class="team">Team</a>
                    <a href="#" class="advertise">Advertise</a>
                    <br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
        <div class="row ft-copyright pt-2 pb-2" style="padding-left: 25px;">
            <div class="col-sm-4 text-pp-crt">cpoyright 2018 All Rights Reserved</div>
            <div class="col-sm-4 text-pp-crt-rg">Department of Information Reg No :</div>
            <div class="col-sm-4 developer">
                <a href="https://topline-tech.com" target="_blank" class="text-pp-crt">By : <b>T</b>op<b>L</b>ine</a>
            </div>
        </div>
    </div>

</body>

</html>