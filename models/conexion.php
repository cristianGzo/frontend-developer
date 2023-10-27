<?php
class Conexion {
    private $DBServer = '127.0.0.1';
    private $DBUser = 'master'; 
    private $DBPass = '1234'; 
    private $DBName = 'proyectoweb';

    public function __construct() {}

    public function conectar() { 
        try {
            $conn = new PDO("mysql:host={$this->DBServer};dbname={$this->DBName}", $this->DBUser, $this->DBPass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
            
        }
        
    }
}



   /* $conexion = new Conexion();
    // Intentar establecer la conexión
$conn = $conexion->conectar();

if ($conn) {
    echo "Conexión exitosa a la base de datos.<br>";

    // Realizar una consulta para obtener datos de la tabla 'producto'
   /* $query = "SELECT * FROM producto";
    $stmt = $conn->prepare($query);
    $stmt->execute();  
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar y mostrar los datos de los productos
    foreach ($productos as $producto) {
        echo "ID: " . $producto['idProducto'] . "<br>";
        echo "Nombre: " . $producto['precio'] . "<br>";
        // Agrega más campos según la estructura de tu tabla
        echo "<br>";
    }*//*
} else {
    echo "No se pudo conectar a la base de datos.";
}*/
