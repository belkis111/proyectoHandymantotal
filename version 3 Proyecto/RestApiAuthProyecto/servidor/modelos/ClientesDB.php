<?php
require_once __DIR__ . '/../config/database.php';

//require_once __DIR__ . "/config/database.php";
// se define a clase
class ClientesDB {
    private $conn;

// se define las funciones publicas
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function clienteExiste($correo_electronico) {
        $query = "SELECT Id_Cliente FROM cliente WHERE Correo_electronico= :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function obtenerPorCorreo($correo_electronico) {
        $query = "SELECT * FROM cliente WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function registrarUsuario($nombre, $direccion, $telefono, $correo_electronico, $contrasena) {
        try {
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $query = "INSERT INTO cliente (Nombre, Direccion, Telefono, Correo_electronico, Contrasena) VALUES (:Nombre, :Direccion, :Telefono, :Correo_electronico, :Contrasena)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Nombre", $nombre);
            $stmt->bindParam(":Direccion", $direccion);
            $stmt->bindParam(":Telefono", $telefono);
            $stmt->bindParam(":Correo_electronico", $correo_electronico);
            $stmt->bindParam(":Contrasena", $hashed_password);
    
            //DEPURACIÓN: Verificar si la consulta SQL se ejecuta correctamente
            if (!$stmt->execute()) {
                throw new Exception("Error en la consulta SQL: " . implode(" ", $stmt->errorInfo()));
                file_put_contents("debug.log", "Hash generado: $hashed_password\n", FILE_APPEND);

            }
            return true;
    
        } catch (Exception $e) {
            file_put_contents("debug.log", "Error en registrarUsuario: " . $e->getMessage() . "\n", FILE_APPEND);
           
            return false;
        }
    }
    public function iniciarSesion($correo_electronico, $contrasena) {
        $query = "SELECT Id_Cliente, Nombre, Contrasena FROM cliente WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$cliente) {
            return false;
        }

        if (password_verify($contrasena, $cliente["Contrasena"])) {
            return $cliente["Id_Cliente"];
        }
        return false;
    }
    
    public function guardarSesion($id_usuario, $token, $tipoUsuario) {
        $query = "INSERT INTO sesiones (Id_Usuario, token, TipoUsuario, creado_en) 
                  VALUES (:id_usuario, :token, :tipo_usuario, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":tipo_usuario", $tipoUsuario);

        return $stmt->execute();
    
    }

    public function cerrarSesion($token) {
        $query = "DELETE FROM sesiones WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

        // Obtener todos los clientes
    public function obtenerClientes() {
        $query = "SELECT * FROM cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un cliente por su ID
    public function obtenerClientePorID($id_cliente) {
        $query = "SELECT * FROM cliente WHERE Id_Cliente = :Id_Cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Id_Cliente", $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar datos de un cliente
    public function actualizarCliente($id_cliente, $nombre, $direccion, $telefono, $correo) {
        $sql = " UPDATE cliente SET Nombre = :nombre, Direccion = :direccion, Telefono = :telefono, Correo_electronico = :correo WHERE Id_Cliente = :id_cliente";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre',    $nombre,    PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':telefono',  $telefono,  PDO::PARAM_STR);
        $stmt->bindParam(':correo',    $correo,    PDO::PARAM_STR);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();  // 0 si no encontró el cliente o no cambió datos
    }

// Eliminar un cliente por su ID
public function eliminarCliente($id_cliente) {
    $sql  = "DELETE FROM cliente WHERE Id_Cliente = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();  // 0 si no se encontró, >0 si eliminó
}
}

        
?>
