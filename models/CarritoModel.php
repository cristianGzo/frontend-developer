<?php
class CarritoModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }



    public function crearCarrito(){
        //se agrega al carrito del usr logeado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    if (isset($_SESSION['idUsuario'])) {
        $idUsuario = $_SESSION['idUsuario'];
        //$idUsuario = $_POST['idUsuario'];
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
    } else {
        return array();
    }
    }

    public function obtenerCarrito(){
        //carrito de usuario logeado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['idUsuario'])) {
            $idUsuario = $_SESSION['idUsuario'];
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
            where  c.idUsuario = :idUsuario
        GROUP BY
            p.idProducto;");
                $stmt->bindParam(':idUsuario', $idUsuario);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al obtener el carrito: " . $e->getMessage());
            }
        } else {
            return array();
        }
    }
    //metodo que trae el total a pagar de los productos que hay en el carrito
    public function costoCarrito(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['idUsuario'])) {
            $idUsuario = $_SESSION['idUsuario'];
        try {
            $stmt = $this->conexion->prepare("SELECT
            SUM(p.precio * c.cantidad) AS total_a_pagar
        FROM
            carrito AS c
        JOIN
            producto AS p ON c.idProducto = p.idProducto
            WHERE
            c.idUsuario = :idUsuario; ");
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener costoCarrito: " . $e->getMessage());
        }
    } else {
        return array();
    }
    }
    public function eliminarDeCarrito(){
        $idCarrito = $_POST['idCarrito'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM carrito WHERE idCarrito = :idCarrito;");
            $stmt->bindParam(':idCarrito', $idCarrito);

            if ($stmt === false) {
                return "Error en la preparación de la consulta de eliminación.";
            }

            if ($stmt->execute()) {
                $carritoActualizado = $this->obtenerCarrito();
                $precioActual = $this->costoCarrito();
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