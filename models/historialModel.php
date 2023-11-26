<?php
class HistorialModel{
    private $conexion;

    public function __construct() {
        $conexion= new Conexion();
        $this->conexion = $conexion->conectar();
    }
   
    

    public function agregarAhistorial() {
        $idUsuario = $_POST['idUsuario']; 
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad']; 
        $fechaActualizacion = $_POST['fechaAgregado'];
        
        try {
            $stmt = $this->conexion->prepare("INSERT INTO carrito (idUsuario, idProducto, cantidad, fechaAgregado) VALUES (:idUsuario, :idProducto, :cantidad, :fechaAgregado)");
            if ($stmt === false) {
                echo "Error en la preparación de la consulta.";
                return -1; // Otra indicación de error
            }
            
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':idProducto', $idProducto);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':fechaAgregado', $fechaActualizacion);
            echo "Consulta SQL: " . $stmt->queryString;
            
            if ($stmt->execute()) {
                return $this->conexion->lastInsertId(); // Retorna el ID del nuevo producto
            } else {
                return -1; // Otra indicación de error
            }
        } catch (PDOException $e) {
            die("Error al crear producto: " . $e->getMessage());
        }
    }
    
    public function obtenerHistorial() {
        try {
            $stmt = $this->conexion->prepare("SELECT 
            p.nombre AS Producto, 
            p.imagen AS ImagenProducto, 
            pd.total AS Importe, 
            pd.cantidad AS Cantidad,
            v.fecha AS Fecha, 
            u.nombre AS Usuario 
        FROM 
            detalleventa pd 
        JOIN 
            producto p ON pd.idProducto = p.idProducto 
        JOIN 
            venta v ON pd.idVenta = v.idVenta 
        JOIN 
            usuario u ON v.idUsuario = u.idUsuario 
        WHERE 
            v.idUsuario = 1;
        
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el historial: " . $e->getMessage());
        }
    }
    
    
    public function eliminarDeHistorial(){
        $idCarrito = $_POST['idCarrito'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM carrito WHERE idCarrito = :idCarrito;");
            $stmt->bindParam(':idCarrito', $idCarrito);
            
            if ($stmt === false) {
                return "Error en la preparación de la consulta de eliminación.";
            }
    
            if ($stmt->execute()) {
               
                return "Operación exitosa";
            } else {
                return "Error al ejecutar la consulta de eliminación.";
            }
        } catch (PDOException $e) {
            return "Error al eliminar producto: " . $e->getMessage();
        }
    }
     
}
?>