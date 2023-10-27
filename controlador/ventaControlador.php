<?php 
require_once '../models/VentaModel.';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $venta = new VentaModel();

    switch($_GET['opc']){
        case 1:
            //insert
            $venta->crearVenta();
            echo 'insertado xxd';
            
        }
    }else{
        header('Location: ../index.html');
    }
    
    ?>