<?php 
require_once '../models/categoriaModel.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $cat = new CategoriaModel();

    switch($_GET['opc']){
        
        case 1:
            $categorias = $cat->obtenerCategoria();
            echo json_encode($categorias);
            break;
        case 2:
            $idCat = $cat->idCategoria();
            echo json_encode($idCat);
            break;
        case 3:
            $cat->crearCategoria();
            break;
        case 4:
            $cat->desactivarCategoria();
    }
}else{
    header('Location: ../index.html');
}


?>