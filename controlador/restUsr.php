<?php
    require_once '../models/registroModel.php';
    require_once '../models/conexion.php';
    

    $regModel = new RegistroModel();
    $request = $_SERVER['REQUEST_METHOD'];
    switch ($request) {
        case 'GET': $response = '';
                    $usuarios = $regModel->obtenerUsr();
                    if ($usuarios !== false) {
                        echo json_encode($usuarios);
                    } else {
                        echo json_encode(['error' => 'Error al obtener usuarios']);
                    }
                    break;
        case 'POST':   
                $arData = json_decode(file_get_contents('php://input'),true);
                $regModel->crearUsrRest($arData);
            break;
        case 'PUT':     
                $arData = json_decode(file_get_contents('php://input'),true);
                $regModel->actualizarUsr(2,'hk',5);
            break;
        case 'DELETE':  
                $arData = json_decode(file_get_contents('php://input'),true);
                $regModel->eliminarUsrRest($arData['idMensaje']);
            break;
        default: header('HTTP 400 Bad Request');
    }


    http://localhost:8080/prograweb/controladores/restContacto.php
?>