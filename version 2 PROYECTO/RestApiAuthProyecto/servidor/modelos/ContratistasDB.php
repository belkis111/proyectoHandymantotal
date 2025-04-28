<?php
require_once __DIR__ . '/../config/database.php';

class ContratistasDB {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para verificar si un contratista ya existe (por correo electrónico, por ejemplo)

    public function contratistaExiste($correo_electronico) {
        $query = "SELECT Id_contratista FROM contratista WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Método para registrar un nuevo contratista
    public function registrarContratista($nombre, $direccion, $telefono, $correo_electronico, $especialidad, $contrasena) {
        try {
            // Opcional: Puedes verificar si el contratista ya existe
            if ($this->contratistaExiste($correo_electronico)) {
                return false;
            }

            // Encriptar la contraseña
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

            // Consulta SQL para insertar el nuevo contratista
            $query = "INSERT INTO contratista (Nombre, Direccion, Telefono, Correo_electronico, Especialidad, Contrasena)
                    VALUES (:Nombre, :Direccion, :Telefono, :Correo_electronico, :Especialidad, :Contrasena)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Nombre", $nombre);
            $stmt->bindParam(":Direccion", $direccion);
            $stmt->bindParam(":Telefono", $telefono);
            $stmt->bindParam(":Correo_electronico", $correo_electronico);
            $stmt->bindParam(":Especialidad", $especialidad);
            $stmt->bindParam(":Contrasena", $hashed_password);

            if (!$stmt->execute()) {
                // Opcional: puedes lanzar una excepción o devolver false
                throw new Exception("Error en la consulta SQL: " . implode(" ", $stmt->errorInfo()));
            }
            return true;
        } catch (Exception $e) {
            // Registrar el error en un archivo de log
    error_log($e->getMessage(), 3, __DIR__ . '/error_log.txt');
            // Aquí podrías registrar el error en un log o manejarlo según corresponda
            return false;
        }
    }

    // Puedes agregar otros métodos específicos para contratistas, como login o actualizar perfil, según se requiera.

    public function iniciarSesioncontratista($correo_electronico, $contrasena) {
        $query = "SELECT Id_contratista, Nombre, Contrasena FROM contratista WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        $contratista  = $stmt->fetch(PDO::FETCH_ASSOC);

     //   if (!$contratista ) {
    
    if ($contratista && password_verify($contrasena, $contratista["Contrasena"])) {
        return $contratista["Id_contratista"];
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

     // Obtener todos los contratistas
     public function obtenerContratistas() {
        $query = "SELECT * FROM contratista";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un contratistas por su ID
    public function obtenerContratistaPorID($id_contratista) {
        $query = "SELECT * FROM contratista WHERE Id_contratista = :Id_contratista";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Id_contratista", $id_contratista, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar datos de un contratistas
    public function actualizarContratista($id_contratista, $nombre, $direccion, $telefono, $correo_electronico, $especialidad) {
        $query = "UPDATE contratista SET Nombre = :Nombre, Direccion = :Direccion, Telefono = :Telefono, Correo_electronico = :Correo_electronico, Especialidad = :Especialidad WHERE Id_contratista = :Id_contratista ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Id_contratista", $id_contratista, PDO::PARAM_INT);
        $stmt->bindParam(":Nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":Direccion", $direccion, PDO::PARAM_STR);
        $stmt->bindParam(":Telefono", $telefono, PDO::PARAM_STR);
        $stmt->bindParam(":Correo_electronico", $correo_electronico, PDO::PARAM_STR);
        $stmt->bindParam(":Especialidad", $especialidad, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Eliminar un contratistas por su ID
    public function eliminarContratista($id_contratista) {
        $query = "DELETE FROM contratista WHERE Id_contratista = :Id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Id", $id_contratista, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorCorreo($correo_electronico) {
        $query = "SELECT * FROM contratista WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   


}


?>
