<?php
class CarritoModel{
    private $conexion;

    public function __construct() {
        $conexion= new Conexion();
        $this->conexion = $conexion->conectar();
    }
   
    

    public function crearCarrito() {
        $idUsuario=$_POST['idUsuario']; 
        $idProducto=$_POST['idProducto'];
        $cantidad=$_POST['cantidad']; 
        $fechaActualizacion= $_POST['fechaAgregado'];
        try {
            $stmt = $this->conexion->prepare("INSERT INTO carrito (idUsuario, idProducto, cantidad, fechaAgregado) VALUES (:idUsuario, :idProducto, :cantidad, :fechaAgregado)");
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':idProducto', $idProducto);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':fechaAgregado', $fechaActualizacion);
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
    public function obtenerCarrito() {
        try {
            $stmt = $this->conexion->prepare("SELECT
            p.nombre AS nombre_producto,
            p.precio AS precio_producto,
            c.idCarrito as idCarrito,
            SUM(c.cantidad) as cantidad,
            SUM(p.precio * c.cantidad) AS suma_precio,
            p.imagen AS imagen_producto
        FROM
            carrito c
        JOIN
            producto p ON c.idProducto = p.idProducto
        GROUP BY
            p.idProducto;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el carrito: " . $e->getMessage());
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
                $carritoActualizado = $this->obtenerCarrito();
                //echo json_encode($carritoActualizado);
            } else {
                echo "Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo());
                return false; // Otra indicación de error
            }
        } catch (PDOException $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }
}
?>