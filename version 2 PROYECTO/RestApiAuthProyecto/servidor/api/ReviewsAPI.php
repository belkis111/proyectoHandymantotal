<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/ReviewsDB.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
  http_response_code(401);
  echo json_encode(['status'=>'error','message'=>'No autenticado']);
  exit;
}

$db = new ReviewsDB();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  if (empty($data['id_solicitud']) || empty($data['calificacion'])) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Datos incompletos']);
    exit;
  }
  $ok = $db->guardarReview(
    (int)$data['id_solicitud'],
    (int)$_SESSION['usuario_id'],
    (int)$data['calificacion'],
    $data['comentario'] ?? ''
  );
  echo json_encode([
    'status' => $ok?'success':'error',
    'message'=> $ok?'Reseña guardada':'Error al guardar reseña'
  ]);
  exit;
}

if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['action']) && $_GET['action']==='obtenerReviews') {
  $idSol = (int)($_GET['id_solicitud'] ?? 0);
  echo json_encode($db->obtenerReviewsPorSolicitud($idSol));
  exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','message'=>'Método no permitido']);
