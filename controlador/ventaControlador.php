<?php 
require_once '../models/VentaModel.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $venta = new VentaModel();

    switch($_GET['opc']){
        case 1:
            //insert
            $venta->crearVenta();
            echo 'insertado xxd';
            break;
        case 2:
                //update
                $venta->actualizarPaypalVenta();
                echo 'Actualizado xxd';
                break;
            
        }
    }else{
        header('Location: ../index.html');
    }
    
    ?>