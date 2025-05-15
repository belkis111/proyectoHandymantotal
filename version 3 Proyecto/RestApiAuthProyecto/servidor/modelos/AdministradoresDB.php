<?php
require_once __DIR__ . "/../config/database.php";

// O la ruta a tu conexión, por ejemplo, Database.php

class AdministradoresDB {
    private $conn;

    public function __construct() {
        $database = new Database();  // Asumiendo que tu clase se llama Database en config/database.php
        $this->conn = $database->getConnection();
    }

    public function registrarAdministrador($nombre, $direccion, $telefono, $correo_electronico, $contrasena) {
        try {
            // Encriptar la contraseña
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO administrador (Nombre, Direccion, Telefono, Correo_electronico, Contrasena) 
                      VALUES (:nombre, :direccion, :telefono, :correo_electronico, :contrasena)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":direccion", $direccion);
            $stmt->bindParam(":telefono", $telefono);
            $stmt->bindParam(":correo_electronico", $correo_electronico);
            $stmt->bindParam(":contrasena", $hashed_password);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en registrarAdministrador: " . $e->getMessage());
            return false;
        }
    }
    
    public function iniciarSesionAdministrador($correo_electronico, $contrasena) {
        $query = "SELECT Id_admin, Contrasena FROM administrador WHERE Correo_electronico = :correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo_electronico", $correo_electronico);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) {
            return false;
        }
        if (password_verify($contrasena, $admin["Contrasena"])) {
            return $admin["Id_admin"];
        }
        return false;
    }

    public function guardarSesion($id_usuario, $token, $tipoUsuario) {
        $query = "INSERT INTO sesiones (Id_usuario, token, TipoUsuario) VALUES (:id_usuario, :token, :tipoUsuario)";
        $stmt = $this->conn->prepare($query); // 🔹 Cambié $sql a $query
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":tipoUsuario", $tipoUsuario); // 🔹 Usar ":tipoUsuario" en minúsculas como en la consulta SQL
        
        return $stmt->execute();
    }
    
    public function cerrarSesion($token) {
        $query = "DELETE FROM sesiones WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }
}
?>
