<?php 
require_once '../models/historialModel.php';
require_once '../models/conexion.php';

if(isset($_GET['opc'])){
    $historial = new HistorialModel();

    switch($_GET['opc']){
        case 1:
            //get para llenar la tabla
            echo getHistorial($historial);
            break;
        case 2:
            /*$productos=$producto->obtenerImagenProducto();
            echo 'coorecto';
            foreach ($productos as $item) {
                // Imprime cada producto en el carrito en formato HTML
                echo '<select class="imagenes">';
                echo '<option>' . $item['imagen'] . '</option>';
                echo '</select>';
            }*/
        }
    }else{
        header('Location: ../index.html');
    }

    function getHistorial($data) {
        $response = '';
        $info = $data->obtenerHistorial();
        
        foreach($info as $row){
            $ruta="../images/";
            $rutaImagen=$ruta . $row['ImagenProducto'];
            $response .= '<tr>
                <th scope="row">1</th>
                <td>'.$row['Producto'].'</td>
                <td><img src="'.$rutaImagen.'" alt="'.$row['Producto'].'" style="width: 70px; height: 70px; border-radius: 20px; object-fit: cover;"></td>
                <td>'.$row['Importe'].'</td>
                <td>'.$row['Cantidad'].'</td>
                <td>'.$row['Fecha'].'</td>
                <td>'.$row['Usuario'].'</td>
            </tr>';
        }
        return $response;
    }
?>