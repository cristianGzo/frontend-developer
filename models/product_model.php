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

    public function eliminarProducto($id) {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM producto WHERE idProducto = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }

}
?>