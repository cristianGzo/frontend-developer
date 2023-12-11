<?php
class VentaModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }



    public function crearVenta()
    {
        $idUsuario = $_POST['idUsuario'];
        $fecha = $_POST['fecha'];
        $total = $_POST['total'];
        try {
            $stmt = $this->conexion->prepare("INSERT INTO venta (idUsuario, fecha, total) VALUES (:idUsuario, :fecha, :total);");
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':total', $total);
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
    public function obtenerVenta()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT V.idVenta AS ID,P.idProducto as IDP, U.nombre AS usuario, P.nombre AS producto, P.precio AS Precio_Producto, SUM(C.cantidad) AS Cantidad, SUM(C.cantidad * P.precio) AS costo FROM venta V JOIN usuario U ON V.idUsuario = U.idUsuario JOIN carrito C ON V.idUsuario = C.idUsuario JOIN producto P ON C.idProducto = P.idProducto WHERE V.idVenta = (SELECT MAX(idVenta) FROM venta) GROUP BY V.idVenta, U.nombre, P.nombre, P.precio;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el carrito: " . $e->getMessage());
        }
    }
    //metodo que trae el total a pagar de los productos que hay en el carrito
    public function costoCarrito()
    {
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
    public function eliminarDeCarrito()
    {
        $idCarrito = $_POST['idCarrito'];
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

    public function actualizarPaypalVenta()
    {
        $idPaypal= $_POST['idPaypal'];
        $idVenta = $_POST['idVenta'];
        try {
            $stmt = $this->conexion->prepare("UPDATE venta SET idPaypal = :idPaypal WHERE idVenta = :idVenta");
            $stmt->bindParam(':idPaypal', $idPaypal);
            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error al actualizar venta: " . $e->getMessage());
        }
    }
}
