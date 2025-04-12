<?php
class Database {
    private $host = "localhost";
    private $db_name = "handymantotal";  // Asegúrate de que el nombre es correcto
    private $username = "root";
    private $password = "luna2012";  // Verifica si la contraseña es correcta
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage()); // Mostrar error si la conexión falla
        }
        return $this->conn;
    }
}

?>
