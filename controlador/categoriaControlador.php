<?php 
require_once '../models/categoriaModel.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $cat = new CategoriaModel();

    switch($_GET['opc']){
        
        case 1:
            $categorias = $cat->obtenerCategoria();
            
            foreach ($categorias as $item) {
                // Imprime cada producto en el carrito en formato HTML
                echo '<select class="categorias">';
                echo '<option>' . $item['nombre'] . '</option>';
                echo '</select>';
            }
            break;
        case 2:
            $idCat = $cat->idCategoria();
            echo $idCat;
    }
}else{
    header('Location: ../index.html');
}


?>