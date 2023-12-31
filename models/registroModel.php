<?php
require_once 'conexion.php';

class RegistroModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }


    public function crearUsr()
    {
        $Usuario = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = 'cliente';

        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = "6LdVHg0pAAAAAH7XVC_0KKPqJErMwvVm-w_JqIIH";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip");
        $atributos = json_decode($response, TRUE);

        
        if (empty($Usuario) || empty($email) || empty($password)) {
            echo 'Todos los campos son obligatorios.';
            return;
        }
        // Validación de correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Formato de correo electrónico no válido.';
            return;
        }
        // Validación de longitud de contraseña
        if (strlen($password) < 6) {
            echo 'La contraseña debe tener al menos 6 caracteres.';
            return;
        }if (!$atributos['success']) {
            echo 'Captcha no valido';
            return;
        }else {
            try {
                $stmt = $this->conexion->prepare("INSERT INTO usuario (nombre, email, contrasena, rol) VALUES (:nombre, :email, :passwor, :rol);");
                $stmt->bindParam(':nombre', $Usuario);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passwor', $password);
                $stmt->bindParam(':rol', $rol);
                if ($stmt->execute()) {
                    $cve_usuario = $this->conexion->lastInsertId();
                    //echo "Inserción exitosa. ID de usuario: " . $cve_usuario;
                    return $cve_usuario; // Retorna el ID del nuevo usuario
                } else {
                    echo "Error al ejecutar la consulta.";
                } // Retorna el ID del nuevo producto
            } catch (PDOException $e) {
                die("Error al crear usuario: " . $e->getMessage());
            }
        }
    }
    public function crearUsrRest($arg)
    {
        $Usuario = $arg['name'];
        $email = $arg['email'];
        $password = $arg['password'];
        $rol = 'cliente';

        try {
            $stmt = $this->conexion->prepare("INSERT INTO usuario (nombre, email, contrasena, rol) VALUES (:nombre, :email, :passwor, :rol);");
            $stmt->bindParam(':nombre', $Usuario);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':passwor', $password);
            $stmt->bindParam(':rol', $rol);
            //$stmt->bindParam(':correo', $correo);
            //$stmt->bindParam(':contrasena', $contrasena);
            if ($stmt->execute()) {
                $cve_usuario = $this->conexion->lastInsertId();
                //echo "Inserción exitosa. ID de usuario: " . $cve_usuario;
                return $cve_usuario; // Retorna el ID del nuevo usuario
            } else {
                echo "Error al ejecutar la consulta.";
            } // Retorna el ID del nuevo producto
        } catch (PDOException $e) {
            die("Error al crear producto: " . $e->getMessage());
        }
    }
    public function actualizarUsr($id, $nombre, $precio)
    {
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


    public function obtenerUsr()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM usuario");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el categoria: " . $e->getMessage());
        }
    }

    public function eliminarUsr()
    {
        $idUsuario = $_POST['idUsuario'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM usuario WHERE idUsuario = :idUsuario;");
            $stmt->bindParam(':idUsuario', $idUsuario);

            if ($stmt === false) {
                return "Error en la preparación de la consulta de eliminación.";
            }

            if ($stmt->execute()) {
                //$carritoActualizado = $this->obtenerCarrito();
                //$precioActual = $this->costoCarrito();
                return "Operación exitosa";
            } else {
                return "Error al ejecutar la consulta de eliminación.";
            }
        } catch (PDOException $e) {
            return "Error al eliminar producto: " . $e->getMessage();
        }
    }
    public function eliminarUsrRest($data)
    {
        $idUsuario = $data['idUsuario'];
        try {
            $stmt = $this->conexion->prepare("DELETE FROM usuario WHERE idUsuario = :idUsuario;");
            $stmt->bindParam(':idUsuario', $idUsuario);

            if ($stmt === false) {
                return "Error en la preparación de la consulta de eliminación.";
            }

            if ($stmt->execute()) {
                //$carritoActualizado = $this->obtenerCarrito();
                //$precioActual = $this->costoCarrito();
                return "Operación exitosa";
            } else {
                return "Error al ejecutar la consulta de eliminación.";
            }
        } catch (PDOException $e) {
            return "Error al eliminar producto: " . $e->getMessage();
        }
    }
}
