<?php

    switch( $_GET['opc'] ){
        case 1: // GET
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/ditto");
            //"http://localhost:8080/prograweb/controladores/restContacto.php");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            echo $res = curl_exec($ch);
            break;
        case 2: // POST
            $fields = array(
                "name" => "trinidad",
                "email" => "trinidad.salinas@nimodillo.com",
                "password" => "452"
            );
            $jsonData = json_encode($fields);
            // http://localhost:8080/prograweb/controladores/ctrRestAPI.php?opc=1

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/frontend-developer/controlador/restUsr.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json'] );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            echo $data = curl_exec($ch);
            break;
        case 3:
            $fields = array(
                "idMensaje" => 7,
                "nombre" => "trinidad",
                "apepat" => "Salinas",
                "apemat" => "Leon",
                "email" => "trinidad@nimodillo.com",
                "telefono" => "4521234433"
            );
        
            $jsonData = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/frontend-developer/controlador/restUsr.php");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Content-Length: ' . strlen($jsonData)]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            echo $data = curl_exec($ch);
            break;
        case 4:
            $fields = array("idMensaje" => 7);
            $jsonData = json_encode($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/frontend-developer/controlador/restUsr.php");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            echo $data = curl_exec($ch);
    }

    curl_close($ch);
?>