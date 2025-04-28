<?php
// Archivo: servidor/api/PresupuestosAPI.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
ob_start();

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

// Instancia y conexión desde Database
$database = new Database();
$conn = $database->getConnection();

class PresupuestosAPI {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function obtenerPresupuestos() {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
            return;
        }
        $id = (int) $_SESSION['usuario_id'];
        $sql = "
            SELECT
                Id_solicitud    AS id_presupuesto,
                Titulo          AS descripcion,
                Precio_estimado AS monto,
                Estado          AS estado
            FROM solicitudes
            WHERE Id_Cliente = :id
              AND Tipo_solicitud = 'presupuesto'
            ORDER BY Fecha_solicitud DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultados);
    }

    public function aceptarPresupuesto(int $id_solicitud) {
        $stmt = $this->conn->prepare(
            "UPDATE solicitudes SET Estado = 'en_proceso' WHERE Id_solicitud = :id"
        );
        $stmt->bindParam(':id', $id_solicitud, PDO::PARAM_INT);
        $ok = $stmt->execute();
        echo json_encode([
            'status'  => $ok ? 'success' : 'error',
            'message' => $ok ? 'Presupuesto aceptado.' : 'Error al aceptar'
        ]);
    }

    public function rechazarPresupuesto(int $id_solicitud) {
        $stmt = $this->conn->prepare(
            "UPDATE solicitudes SET Estado = 'cancelado' WHERE Id_solicitud = :id"
        );
        $stmt->bindParam(':id', $id_solicitud, PDO::PARAM_INT);
        $ok = $stmt->execute();
        echo json_encode([
            'status'  => $ok ? 'success' : 'error',
            'message' => $ok ? 'Presupuesto rechazado.' : 'Error al rechazar'
        ]);
    }
}

// Instanciación y manejo de rutas
global $conn;
$api = new PresupuestosAPI($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'obtenerPresupuestos') {
    $api->obtenerPresupuestos();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['accion']) || empty($data['id_solicitud'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    } elseif ($data['accion'] === 'aceptar') {
        $api->aceptarPresupuesto((int) $data['id_solicitud']);
    } elseif ($data['accion'] === 'rechazar') {
        $api->rechazarPresupuesto((int) $data['id_solicitud']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}

ob_end_flush();
?>
