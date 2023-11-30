<?php
class vDetalleModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }



    public function crearVDetalle(){
        $idVenta = $_POST['idVenta'];
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $total = $_POST['total'];
        $idPaypal= $_POST['idPaypal'];
        
        try {
            $stmt = $this->conexion->prepare("INSERT INTO detalleventa (idVenta, idProducto, cantidad, total, idPaypal) VALUES (:idVenta, :idProducto, :cantidad, :total, :idPaypal);");
            $stmt->bindParam(':idVenta', $idVenta);
            $stmt->bindParam(':idProducto', $idProducto);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':idPaypal', $idPaypal);
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

    public function obtenerId() {
        try {
            $stmt = $this->conexion->prepare("SELECT p.nombre AS Producto, pd.total as Importe, pd.cantidad as Cantidad,v.fecha AS Fecha, u.nombre AS Usuario FROM detalleventa pd JOIN producto p ON pd.idProducto = p.idProducto JOIN venta v ON pd.idVenta = v.idVenta JOIN usuario u ON v.idUsuario = u.idUsuario WHERE v.idVenta = (SELECT MAX(idVenta) FROM venta);");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el carrito: " . $e->getMessage());
        }
    }
}
?>
