<?php 
require_once '../models/CarritoModel.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $carrito = new CarritoModel();

    switch($_GET['opc']){
        case 1:
            //insert
            $carrito->crearCarrito();
            break;
        case 2:
            $eliminado=$carrito->eliminarDeCarrito();
            $carritoActualizado = $carrito->obtenerCarrito();
            $costoActualizado = $carrito->costoCarrito();
            
            foreach ($carritoActualizado as $item) {
                // Imprime cada producto en el carrito en formato HTML
                echo '<div class="shopping-cart">';
                echo '<figure><img src="./images/' . $item['imagen_producto'] . '" alt="bike"></figure>';
                echo '<p>' . $item['nombre_producto'] . '</p>';
                echo '<p>' . $item['suma_precio'] . '</p>';
                echo '<p>' . $item['cantidad'] . '</p>';
                echo '<img src="./icons/icon_close.png" alt="close" onclick="eliminar(' . $item['idCarrito'] . ')">';
                echo '</div>';
            }
            echo '<script>';
            echo 'document.getElementById("totalPrice").textContent = ' . $costoActualizado[0]['total_a_pagar'] . ';';
            echo '</script>';
            break;
        case 3:
            $carrito->eliminarTodoCarrito();
    }
}else{
    header('Location: ../index.html');
}


?>