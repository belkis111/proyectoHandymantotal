<?php
session_start();
// No mostrar errores al cliente
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$database = new Database();
$conn     = $database->getConnection();
$api      = new PresupuestosAPI($conn);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'obtenerPresupuestos') {
    $api->obtenerPresupuestos();
    exit;
}

if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    $idSol = isset($body['id_solicitud']) ? (int)$body['id_solicitud'] : 0;

    if (!$idSol) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Falta id_solicitud']);
        exit;
    }

    if ($action === 'aceptar') {
        $api->aceptarPresupuesto($idSol);
        exit;
    } elseif ($action === 'rechazar') {
        $api->rechazarPresupuesto($idSol);
        exit;
    }

    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Action POST no válida']);
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','message'=>'Método no permitido']);
exit;


class PresupuestosAPI {
    private $conn;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function obtenerPresupuestos() {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'No autenticado']);
            return;
        }
        $id = (int)$_SESSION['usuario_id'];
        $stmt = $this->conn->prepare("
            SELECT Id_solicitud AS id_presupuesto, Titulo AS descripcion,
                   Precio_estimado AS monto, Estado AS estado
            FROM solicitudes
            WHERE Id_Cliente = :id AND Tipo_solicitud = 'presupuesto'
            ORDER BY Fecha_solicitud DESC
        ");
        $stmt->execute([':id'=>$id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function aceptarPresupuesto(int $id_solicitud) {
        try {
            // Lee contratista y precio ya guardados al responder
            $stmt1 = $this->conn->prepare("
                SELECT Id_contratista, Precio_estimado
                FROM solicitudes
                WHERE Id_solicitud = :idSol
                  AND Tipo_solicitud = 'presupuesto'
                  AND Estado = 'pendiente'
            ");
            $stmt1->execute([':idSol'=>$id_solicitud]);
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception("No hay presupuesto pendiente para $id_solicitud");

            // Convierte a servicio
            $stmt2 = $this->conn->prepare("
                UPDATE solicitudes
                SET Tipo_solicitud='servicio', Estado='en_proceso'
                WHERE Id_solicitud = :idSol
            ");
            $stmt2->execute([':idSol'=>$id_solicitud]);

            echo json_encode(['status'=>'success','message'=>'Presupuesto aceptado y convertido en servicio activo.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function rechazarPresupuesto(int $id_solicitud) {
        $stmt = $this->conn->prepare(
            "UPDATE solicitudes SET Estado='cancelado' WHERE Id_solicitud = :id"
        );
        $ok = $stmt->execute([':id'=>$id_solicitud]);
        echo json_encode(['status'=>$ok?'success':'error','message'=>$ok?'Presupuesto rechazado.':'Error al rechazar']);
    }
}
