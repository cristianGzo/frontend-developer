<?php
class SesionModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }

    
    public function obtenerSesion()  
{
    //echo "Método obtenerSesion() se ha invocado.<br>";
    if(isset($_POST['email']) && isset($_POST['password'])){
        $usuario = $_POST['email'];
        $password = $_POST['password'];
        try {
            $stmt = $this->conexion->prepare("SELECT idUsuario, nombre, email, contrasena, rol FROM usuario where email=:usuario");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();
            $usuarioRegistrado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioRegistrado) {
                // Imprimir los datos recuperados de la base de datos
                //var_dump($usuarioRegistrado);

                // Verificar si la contraseña almacenada coincide con la contraseña ingresada
                if ($password === $usuarioRegistrado['contrasena']) {
                    // Contraseña válida, se autentica el usuario
                
                    return $usuarioRegistrado;
                } else {
                    // Contraseña incorrecta
                    echo "Contraseña incorrecta.";
                    return false;
                }
            } else {
                // Usuario no encontrado
                echo "Usuario no encontrado.";
                return false;
            }
        } catch (PDOException $e) {
            die("Error al validar usuario: " . $e->getMessage());
        }
    } else {
        echo "Datos de inicio de sesión no recibidos.";
        return false;
    }
}

}
?>
