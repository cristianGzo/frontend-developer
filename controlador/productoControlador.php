<?php 
require_once '../models/product_model.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $producto = new ProductModel();

    switch($_GET['opc']){
        case 1:
            //insert
            $idProducto=$producto->crearProducto();
            echo $idProducto;
            break;
        case 2:
            $productos=$producto->obtenerImagenProducto();
            echo 'coorecto';
            foreach ($productos as $item) {
                // Imprime cada producto en el carrito en formato HTML
                echo '<select class="imagenes">';
                echo '<option>' . $item['imagen'] . '</option>';
                echo '</select>';
            }
            break;
            case 3:
                $productos=$producto->obtenerProductos();
                echo json_encode($productos);  
            break;
            case 4:
                    $producto->desactivarProducto();
                    
        }
    }else{
        header('Location: ../index.html');
    }
    
    ?>