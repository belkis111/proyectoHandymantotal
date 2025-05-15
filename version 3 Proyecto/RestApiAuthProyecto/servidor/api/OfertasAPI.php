<?php 
session_start();
// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/OfertasDB.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';
if (!$action) {
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"Acción no especificada"]);
    exit;
}

$ofertasDB = new OfertasDB();

function noAuth() {
    http_response_code(401);
    echo json_encode(["status"=>"error","message"=>"No autorizado"]);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($action) {
            case 'listarOfertasPorSolicitud':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                $idSol = (int)($_GET['id_solicitud'] ?? 0);
                $idCliente = $_SESSION['usuario_id'];
                if ($idSol <= 0) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Id inválido"]);
                    exit;
                }
                $data = $ofertasDB->listarOfertasPorSolicitud($idSol);
                echo json_encode($data);
                exit;

    
                case 'ofertasPorCliente':
                    if (!isset($_SESSION['usuario_id'], $_SESSION['tipo_usuario'])
                    || $_SESSION['tipo_usuario']!=='cliente'
                    ) {
                    noAuth();
                   }
                     // devolvemos TODO lo que getOfertasPorCliente traiga
                    $data = $ofertasDB->getOfertasPorCliente((int)$_SESSION['usuario_id']);
                    echo json_encode($data);
                    exit;

            default:
                http_response_code(400);
                echo json_encode(["status"=>"error","message"=>"GET action no válida"]);
                exit;
        }
        break;

    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true) ?: [];
        switch ($action) {
            case 'guardar':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'contratista') noAuth();
                if (empty($body['Id_solicitud']) || !isset($body['Precio_ofertado'])) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Faltan datos obligatorios"]);
                    exit;
                }
                $result = $ofertasDB->crearOferta(
                    (int)$body['Id_solicitud'], 
                    $_SESSION['usuario_id'], 
                    (float)$body['Precio_ofertado'], 
                    $body['Mensaje'] ?? ''
                );
                echo json_encode($result);
                exit;

            case 'aceptarOferta':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                if (empty($body['Id_oferta'])) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Id_oferta no especificado"]);
                    exit;
                }
                $resultado = $ofertasDB->aceptarOferta((int)$body['Id_oferta']);
                echo json_encode($resultado);
                exit;

            case 'rechazarOferta':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                if (empty($body['Id_oferta'])) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Id_oferta no especificado"]);
                    exit;
                }
                $resultado = $ofertasDB->rechazarOferta((int)$body['Id_oferta']);
                echo json_encode($resultado);
                exit;

            default:
                http_response_code(400);
                echo json_encode(["status"=>"error","message"=>"POST action no válida"]);
                exit;
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status"=>"error","message"=>"Método no permitido"]);
        exit;
}
?>
