<?php
class CategoriaModel{
    private $conexion;

    public function __construct() {
        $conexion= new Conexion();
        $this->conexion = $conexion->conectar();
    }
   
    

    public function crearCategoria() {
        $nombre=$_POST['nombre']; 
        try {
            $stmt = $this->conexion->prepare("INSERT INTO categoria (nombre) VALUES (:nombre);");
            $stmt->bindParam(':nombre', $nombre);
            echo "Consulta SQL: " . $stmt->queryString;
            //return $this->conexion->lastInsertId(); // Retorna el ID del nuevo producto
            if ($stmt->execute()) {
                return $this->conexion->lastInsertId(); // Retorna el ID del nuevo producto
            } else {
                echo "Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo());
                return -1; // Otra indicación de error
            }
        } catch (PDOException $e) {
            die("Error al crear producto: " . $e->getMessage());
        }
    }
    public function obtenerCategoria() {
        try {
            $stmt = $this->conexion->prepare("SELECT idCategoria, nombre FROM categoria WHERE `activo` = TRUE");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el categoria: " . $e->getMessage());
        }
    }
    public function idCategoria() {
        $idCategoria=$_POST['idCategoria']; 
        try {
            $stmt = $this->conexion->prepare("SELECT idCategoria from categoria where nombre= :idCategoria;");
            $stmt->bindParam(':idCategoria', $idCategoria);
            //echo "Consulta SQL: " . $stmt->queryString;
            $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado['idCategoria']; // Retorna el ID de la categoría
        } else {
            return null; // No se encontró la categoría
        }
            
        } catch (PDOException $e) {
            die("Error al obtener id: " . $e->getMessage());
        }
    }
    //metodo que trae el total a pagar de los productos que hay en el carrito
    public function costoCarrito() {
        try {
            $stmt = $this->conexion->prepare("SELECT
            SUM(p.precio * c.cantidad) AS total_a_pagar
        FROM
            carrito AS c
        JOIN
            producto AS p ON c.idProducto = p.idProducto; ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener costoCarrito: " . $e->getMessage());
        }
    }
    public function eliminarDeCarrito(){
        $idCarrito=$_POST['idCarrito'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM carrito
            WHERE idCarrito = :idCarrito;");
            $stmt->bindParam(':idCarrito', $idCarrito);
            //echo "Consulta SQL: " . $stmt->queryString;
            //return $this->conexion->lastInsertId(); // Retorna el ID del nuevo producto
            if ($stmt->execute()) {
                //$carritoActualizado = $this->obtenerCarrito();
                //echo json_encode($carritoActualizado);
            } else {
                echo "Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo());
                return false; // Otra indicación de error
            }
        } catch (PDOException $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }
    public function desactivarCategoria() {
        $idCategoria=$_POST['idCategoria'];
        try {
            $stmt = $this->conexion->prepare("UPDATE categoria SET `activo` = FALSE WHERE idCategoria = :idCategoria");
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar categoria: " . $e->getMessage());
        }
    }
}
?>