<?php
session_start();
// En producción puedes desactivar display_errors
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/SolicitudesDB.php';
require_once __DIR__ . '/../modelos/OfertasDB.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';
if (!$action) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Acción no especificada"]);
    exit;
}

function noAuth() {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$dbSol = new SolicitudesDB();
$dbOf = new OfertasDB();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($action) {
            case 'listarContratistas':
                echo json_encode($dbSol->listarContratistas());
                break;

            case 'filtrarContratistas':
                $esp = $_GET['especialidad'] ?? '';
                echo json_encode($dbSol->filtrarContratistasPorEspecialidad($esp));
                break;

            case 'listarMisSolicitudes':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                echo json_encode($dbSol->listarSolicitudesPorCliente($_SESSION['usuario_id']));
                break;

            case 'ofertasRecibidas':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                $data = $dbOf->getOfertasPorCliente((int)$_SESSION['usuario_id']);
                echo json_encode($data);
                break;

            case 'listarOfertasPorSolicitud':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
                $idSol = (int)($_GET['id'] ?? 0);
                if ($idSol <= 0) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Solicitud inválida"]);
                    break;
                }
                echo json_encode($dbOf->listarOfertasPorSolicitud($idSol));
                break;

            case 'presupuestosPendientesContratista':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'contratista') noAuth();
                echo json_encode($dbSol->cargarPresupuestosPendientesContratista());
                break;

            case 'serviciosActivos':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'contratista') noAuth();
                echo json_encode($dbSol->cargarServiciosActivos($_SESSION['usuario_id']));
                break;

            case 'serviciosDisponibles':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'contratista') noAuth();
                $idContratista = (int) $_SESSION['usuario_id'];
                echo json_encode($dbSol->cargarServiciosDisponibles($idContratista));
                break;

            case 'serviciosFinalizados':
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'contratista') noAuth();
                echo json_encode($dbSol->cargarFinalizados($_SESSION['usuario_id']));
                break;

            default:
                http_response_code(400);
                echo json_encode(["status"=>"error","message"=>"Acción GET no válida"]);
        }
        exit;

    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true) ?: [];
        if (!isset($_SESSION['usuario_id'])) noAuth();
        $tipoUsuario = $_SESSION['tipo_usuario'];
        $idUsuario = $_SESSION['usuario_id'];

        switch ($action) {
            case 'presupuesto':
                if ($tipoUsuario !== 'cliente') noAuth();
                $ok = $dbSol->crearSolicitud(
                    $idUsuario,
                    $body['categoria']        ?? '',
                    $body['subcategoria']     ?? '',
                    $body['titulo']           ?? '',
                    $body['descripcion']      ?? '',
                    $body['ubicacion']        ?? '',
                    $body['tipo_solicitud']   ?? 'presupuesto',
                    $body['id_contratista']   ?? null
                );
                echo json_encode(["status"=> $ok ? "success" : "error", "message"=> $ok ? "Solicitud creada." : "Error al crear solicitud."]);
                break;

            case 'responderSolicitud':
                if ($tipoUsuario !== 'contratista') noAuth();
                $idSol = (int)($body['id_solicitud'] ?? 0);
                if ($idSol <= 0) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Solicitud inválida"]);
                    break;
                }
                $ok = $dbOf->crearOferta(
                    $idSol,
                    $idUsuario,
                    (float)($body['precio_estimado'] ?? 0),
                    $body['mensaje_respuesta'] ?? ''
                );
                echo json_encode(["status"=> $ok ? "success" : "error", "message"=> $ok ? "Oferta enviada." : "Error al enviar oferta."]);
                break;

            case 'aceptarSolicitud':
                if ($tipoUsuario !== 'contratista') noAuth();
                $idSol = (int)($body['id_solicitud'] ?? 0);
                if ($idSol <= 0) {
                    http_response_code(400);
                    echo json_encode(["status"=>"error","message"=>"Solicitud inválida"]);
                    break;
                }
                $ok = $dbSol->aceptarSolicitud($idSol, $idUsuario);
                echo json_encode(["status"=> $ok ? "success" : "error", "message"=> $ok ? "Solicitud aceptada." : "Error al aceptar solicitud."]);
                break;

            case 'marcarCompletado':
                if ($tipoUsuario !== 'contratista') noAuth();
                $ok = $dbSol->marcarCompletado((int)($body['id_solicitud'] ?? 0));
                echo json_encode(["status"=> $ok ? "success" : "error", "message"=> $ok ? "Completado." : "Error al completar."]);
                break;

            default:
                http_response_code(400);
                echo json_encode(["status"=>"error","message"=>"Acción POST no válida"]);
        }
        exit;

    case 'DELETE':
        if ($action === 'eliminarSolicitud') {
            parse_str(file_get_contents('php://input'), $delVars);
            if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') noAuth();
            $id = (int)($delVars['id_solicitud'] ?? 0);
            $ok = $dbSol->eliminarSolicitud($id);
            echo json_encode(["status"=> $ok ? "success" : "error", "message"=> $ok ? "Eliminado." : "Error al eliminar."]);
        } else {
            http_response_code(400);
            echo json_encode(["status"=>"error","message"=>"Acción DELETE no válida"]);
        }
        exit;

    default:
        http_response_code(405);
        echo json_encode(["status"=>"error","message"=>"Método no permitido"]);
        exit;
}
?>

