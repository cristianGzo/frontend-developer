<?php 
require_once '../models/product_model.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $product = new ProductModel();

    switch($_GET['opc']){
        
        case 1:
            $pdts = $product->productosMasVendidos();
            echo($pdts);
    }
}else{
    header('Location: ../index.html');
}
?>