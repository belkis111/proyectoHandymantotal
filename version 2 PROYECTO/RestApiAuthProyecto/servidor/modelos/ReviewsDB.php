<?php
require_once __DIR__ . '/../config/database.php';

class ReviewsDB {
  private $conn;
  public function __construct() {
    $db = new Database();
    $this->conn = $db->getConnection();
  }

  public function guardarReview($idSolicitud, $idCliente, $calificacion, $comentario) {
    $sql = "INSERT INTO reviews
            (Id_solicitud, Id_cliente, Calificacion, Comentario)
            VALUES (:sol, :cli, :cal, :com)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':sol' => $idSolicitud,
      ':cli' => $idCliente,
      ':cal' => $calificacion,
      ':com' => $comentario
    ]);
  }

  public function obtenerReviewsPorSolicitud($idSolicitud) {
    $sql = "SELECT Calificacion, Comentario, Fecha
            FROM reviews
            WHERE Id_solicitud = :sol
            ORDER BY Fecha DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':sol', $idSolicitud, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
