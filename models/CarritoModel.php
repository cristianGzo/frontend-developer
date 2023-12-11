<?php
class CarritoModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }



    public function crearCarrito()
    {

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
                $this->conexion->beginTransaction(); // Comienza una transacción

                // Verifica si hay suficiente stock
                $stmtStock = $this->conexion->prepare("SELECT stock FROM producto WHERE idProducto = :idProducto");
                $stmtStock->bindParam(':idProducto', $idProducto);
                $stmtStock->execute();
                $stockActual = $stmtStock->fetchColumn();

                if ($stockActual >= $cantidad) {
                    // Hay suficiente stock, procede con la inserción en la tabla carrito
                    $stmtCarrito = $this->conexion->prepare("INSERT INTO carrito (idUsuario, idProducto, cantidad, fechaAgregado) VALUES (:idUsuario, :idProducto, :cantidad, :fechaAgregado)");
                    $stmtCarrito->bindParam(':idUsuario', $idUsuario);
                    $stmtCarrito->bindParam(':idProducto', $idProducto);
                    $stmtCarrito->bindParam(':cantidad', $cantidad);
                    $stmtCarrito->bindParam(':fechaAgregado', $fechaActualizacion);
                    $stmtCarrito->execute();

                    // Actualiza la tabla producto
                    $stmtUpdateProducto = $this->conexion->prepare("UPDATE producto SET stock = stock - :cantidad, comprometidos = comprometidos + :cantidad WHERE idProducto = :idProducto");
                    $stmtUpdateProducto->bindParam(':cantidad', $cantidad);
                    $stmtUpdateProducto->bindParam(':idProducto', $idProducto);
                    $stmtUpdateProducto->execute();

                    $this->conexion->commit(); // Confirma la transacción
                    return $this->conexion->lastInsertId(); // Retorna el ID del nuevo producto
                } else {
                    $this->conexion->rollBack(); // Revierte la transacción si no hay suficiente stock
                    echo -1;
                    return -1; // Otra indicación de error
                }
            } catch (PDOException $e) {
                $this->conexion->rollBack(); // Revierte la transacción en caso de error
                die("Error al crear producto: " . $e->getMessage());
            }
        }
    }
    public function obtenerCantidadProductosEnCarrito()
    {
        // Carrito de usuario logeado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['idUsuario'])) {
            $idUsuario = $_SESSION['idUsuario'];

            try {
                $stmt = $this->conexion->prepare("SELECT SUM(cantidad) AS total FROM carrito WHERE idUsuario = :idUsuario");
                $stmt->bindParam(':idUsuario', $idUsuario);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                return isset($result['total']) ? $result['total'] : 0;
            } catch (PDOException $e) {
                die("Error al obtener la cantidad de productos en el carrito: " . $e->getMessage());
            }
        } else {
            return 0;
        }
    }

    public function obtenerCarrito()
    {
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
            p.idProducto as idProducto,
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
    public function costoCarrito()
    {
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
    /*public function eliminarDeCarrito()
    {
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
    }*/
    public function eliminarDeCarrito()
    {
        $idCarrito = $_POST['idCarrito'];
        $idProducto = $_POST['idProducto'];

        try {
            $this->conexion->beginTransaction(); // Comienza una transacción

            // Obtén la cantidad comprometida antes de eliminar el producto del carrito
            $stmtCantidadComprometida = $this->conexion->prepare("SELECT cantidad FROM carrito WHERE idCarrito = :idCarrito");
            $stmtCantidadComprometida->bindParam(':idCarrito', $idCarrito);
            $stmtCantidadComprometida->execute();
            $cantidadComprometida = $stmtCantidadComprometida->fetchColumn();

            // Elimina el producto del carrito
            $stmtEliminar = $this->conexion->prepare("DELETE FROM carrito WHERE idCarrito = :idCarrito;");
            $stmtEliminar->bindParam(':idCarrito', $idCarrito);

            if ($stmtEliminar === false) {
                throw new Exception("Error en la preparación de la consulta de eliminación.");
            }

            if ($stmtEliminar->execute()) {
                // Actualiza la tabla producto para devolver la cantidad comprometida al stock
                $stmtUpdateProducto = $this->conexion->prepare("UPDATE producto SET stock = stock + :cantidadComprometida WHERE idProducto = :idProducto");
                $stmtUpdateProducto->bindParam(':cantidadComprometida', $cantidadComprometida);
                $stmtUpdateProducto->bindParam(':idProducto', $idProducto); // Asegúrate de obtener el idProducto
                $stmtUpdateProducto->execute();

                $stmtRestarCantidadComprometida = $this->conexion->prepare("UPDATE producto SET comprometidos = comprometidos - :cantidadComprometida WHERE idProducto = :idProducto");
                $stmtRestarCantidadComprometida->bindParam(':cantidadComprometida', $cantidadComprometida);
                $stmtRestarCantidadComprometida->bindParam(':idProducto', $idProducto);
                $stmtRestarCantidadComprometida->execute();


                $this->conexion->commit(); // Confirma la transacción
                return "Operación exitosa";
            } else {
                throw new Exception("Error al ejecutar la consulta de eliminación.");
            }
        } catch (Exception $e) {
            $this->conexion->rollBack(); // Revierte la transacción en caso de error
            return "Error al eliminar producto: " . $e->getMessage();
        }
    }

    public function eliminarTodoCarrito()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['idUsuario'])) {
            $idUsuario = $_SESSION['idUsuario'];
            try {
                $stmtSelect = $this->conexion->prepare("SELECT idProducto, cantidad FROM carrito WHERE idUsuario = :idUsuario");
            $stmtSelect->bindParam(':idUsuario', $idUsuario);
            $stmtSelect->execute();
            $productosCarrito = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

            // Paso 2: Actualizar los campos "comprometidos" en la tabla de productos
            foreach ($productosCarrito as $producto) {
                $idProducto = $producto['idProducto'];
                $cantidad = $producto['cantidad'];
                $this->actualizarComprometidos($idProducto, $cantidad);
            }

            // Paso 3: Eliminar los productos del carrito
            $stmtDelete = $this->conexion->prepare("DELETE FROM carrito WHERE idUsuario = :idUsuario");
            $stmtDelete->bindParam(':idUsuario', $idUsuario);

            if ($stmtDelete->execute()) {
                return "Operación exitosa";
            } else {
                return "Error al ejecutar la consulta de eliminación.";
            }
        } catch (PDOException $e) {
            return "Error al eliminar producto: " . $e->getMessage();
        }





                /*$stmt = $this->conexion->prepare("DELETE FROM carrito WHERE idUsuario = :idUsuario;");
                $stmt->bindParam(':idUsuario', $idUsuario);
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
            }*/
        } else {
            return array();
        }
    }
    private function actualizarComprometidos($idProducto, $cantidad)
{
    try {
        $stmtUpdate = $this->conexion->prepare("UPDATE producto SET comprometidos = comprometidos - :cantidad WHERE idProducto = :idProducto");
        $stmtUpdate->bindParam(':idProducto', $idProducto);
        $stmtUpdate->bindParam(':cantidad', $cantidad);

        if ($stmtUpdate->execute()) {
            return "Actualización exitosa";
        } else {
            return "Error al ejecutar la actualización.";
        }
    } catch (PDOException $e) {
        return "Error al actualizar comprometidos: " . $e->getMessage();
    }
}

    public function actualizarCantidadCarrito()
    {
        $idCarrito = $_POST['idCarrito'];
        $nuevaCantidad = $_POST['nuevaCantidad'];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['idUsuario'])) {
            $idUsuario = $_SESSION['idUsuario'];

            try {
                $this->conexion->beginTransaction();

                // Obtén información de carrito
                $stmtCarrito = $this->conexion->prepare("SELECT * FROM carrito WHERE idCarrito = :idCarrito AND idUsuario = :idUsuario");
                $stmtCarrito->bindParam(':idCarrito', $idCarrito);
                $stmtCarrito->bindParam(':idUsuario', $idUsuario);
                $stmtCarrito->execute();
                $carritoAntiguo = $stmtCarrito->fetch(PDO::FETCH_ASSOC);

                // Calcula la diferencia en la cantidad
                $diferenciaCantidad = $nuevaCantidad - $carritoAntiguo['cantidad'];

                // Actualiza la cantidad en el carrito
                $stmtUpdateCarrito = $this->conexion->prepare("UPDATE carrito SET cantidad = :nuevaCantidad WHERE idCarrito = :idCarrito AND idUsuario = :idUsuario");
                $stmtUpdateCarrito->bindParam(':nuevaCantidad', $nuevaCantidad);
                $stmtUpdateCarrito->bindParam(':idCarrito', $idCarrito);
                $stmtUpdateCarrito->bindParam(':idUsuario', $idUsuario);
                $stmtUpdateCarrito->execute();

                // Actualiza el stock y los comprometidos en la tabla de productos
                $stmtUpdateProducto = $this->conexion->prepare("UPDATE producto SET stock = stock - :diferenciaCantidad, comprometidos = comprometidos + :diferenciaCantidad WHERE idProducto = :idProducto");
                $stmtUpdateProducto->bindParam(':diferenciaCantidad', $diferenciaCantidad);
                $stmtUpdateProducto->bindParam(':idProducto', $carritoAntiguo['idProducto']);
                $stmtUpdateProducto->execute();

                $this->conexion->commit();

                return true; // La actualización fue exitosa
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                die("Error al actualizar cantidad en carrito: " . $e->getMessage());
            }
        }

        return false; // No hay sesión de usuario
    }
}
