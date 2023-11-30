<?php
class ProductModel{
    private $conexion;

    public function __construct() {
        $conexion= new Conexion();
        $this->conexion = $conexion->conectar();
    }

    public function crearProducto() {
        $nombre=$_POST['txtNombre']; 
        $descripcion=$_POST['txtAp'];
        $precio=$_POST['txtPm']; 
        $idCategoria= $_POST['idCategoria'];
        $imagen=$_POST['imagen'];
        
        try {
            $stmt = $this->conexion->prepare("INSERT INTO producto (nombre, descripcion, precio, idCategoria, imagen) VALUES (:nombre, :descripcion, :precio, :idCategoria, :imagen);");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->execute();
            $idProducto= $this->conexion->lastInsertId(); 
            return $idProducto;// Retorna el ID del nuevo producto
        } catch (PDOException $e) {
            die("Error al crear producto: " . $e->getMessage());
        }
    }

    public function obtenerProductos() {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM producto");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener productos: " . $e->getMessage());
        }
    }
    public function obtenerImagenProducto() {
        try {
            $stmt = $this->conexion->prepare("SELECT imagen FROM producto");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener imagen: " . $e->getMessage());
        }
    }

    public function actualizarProducto($id, $nombre, $precio) {
        try {
            $stmt = $this->conexion->prepare("UPDATE producto SET nombre = :nombre, precio = :precio WHERE idProducto = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function eliminarProducto() {
        $idProducto=$_POST['idProducto'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM producto WHERE idProducto = :idProducto");
            $stmt->bindParam(':idProducto', $idProducto);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }

    public function productosMasVendidos() {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        try {
            $stmt = $this->conexion->prepare("SELECT p.nombre AS nombreProducto, SUM(dv.cantidad) AS totalVentas FROM producto p JOIN detalleventa dv ON p.idProducto = dv.idProducto JOIN venta v ON dv.idVenta = v.idVenta WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY p.nombre;");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            die("Error al obtener productos: " . $e->getMessage());
        }
    }

}
?>