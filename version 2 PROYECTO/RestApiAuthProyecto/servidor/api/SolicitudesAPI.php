<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/SolicitudesDB.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';
if (!$action) {
    echo json_encode(["status"=>"error","message"=>"Acción no especificada"]);
    exit;
}

$db = new SolicitudesDB();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    // Listar contratistas (cliente)
    if ($action === 'listarContratistas') {
        echo json_encode($db->listarContratistas());
        exit;
    }
    // Alias corto para pendientes
    if ($action === 'listarPendientes' || $action === 'listarSolicitudesPendientes') {
        echo json_encode($db->listarSolicitudesPendientes());
        exit;
    }
    // Filtrar contratistas (cliente)
    if ($action === 'filtrarContratistas') {
        $esp = $_GET['especialidad'] ?? '';
        echo json_encode($db->filtrarContratistasPorEspecialidad($esp));
        exit;
    }
    // Mis solicitudes (cliente)
    if ($action === 'listarMisSolicitudes') {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
            http_response_code(401);
            echo json_encode(["status"=>"error","message"=>"No autorizado"]);
            exit;
        }
        $idCli = $_SESSION['usuario_id'];
        echo json_encode($db->listarSolicitudesPorCliente($idCli));
        exit;
    }

    // Si llegamos aquí, acción no válida para GET
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"Acción GET no válida"]);
    exit;


    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);
    
        if (!$body || !isset($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autorizado o JSON inválido"]);
            exit;
        }
    
        $tipoUsuario = $_SESSION['tipo_usuario'];
        $idUsuario   = $_SESSION['usuario_id'];
    
        // ---------------------------
        // Cliente solicita presupuesto o servicio
        // ---------------------------
        if ($action === 'presupuesto' && $tipoUsuario === 'cliente') {
            $tipo        = $body['tipo_solicitud']   ?? 'presupuesto'; 
            $categoria   = $body['categoria']        ?? '';
            $subcat      = $body['subcategoria']     ?? '';
            $titulo      = $body['titulo']           ?? '';
            $descripcion = $body['descripcion']      ?? '';
            $ubicacion   = $body['ubicacion']        ?? '';
            $idCont      = $body['id_contratista']   ?? null; // solo si es servicio
    
            $ok = $db->crearSolicitud(
                $idUsuario, $categoria, $subcat, $titulo, $descripcion, $ubicacion, $tipo, $idCont
            );
    
            $msg = $ok
                 ? ($tipo === 'servicio' ? "Servicio solicitado con éxito." : "Presupuesto solicitado con éxito.")
                 : "Error al procesar la solicitud.";
    
            echo json_encode(["status" => $ok ? "success" : "error", "message" => $msg]);
            exit;
        }
    
        // ---------------------------
        // Cliente contrata un presupuesto (acepta propuesta del contratista)
        // ---------------------------
        if ($action === 'contratar' && $tipoUsuario === 'cliente') {
            $idSol  = $body['id_solicitud']    ?? 0;
            $idCont = $body['id_contratista']  ?? 0;
            $precio = $body['precio_estimado'] ?? 0.0;
    
            $ok = $db->contratarServicio($idSol, $idCont, $precio);
    
            echo json_encode([
                "status" => $ok ? "success" : "error",
                "message" => $ok ? "Servicio contratado." : "Error al contratar servicio."
            ]);
            exit;
        }
    
        // ---------------------------
        // Contratista acepta una solicitud pendiente
        // ---------------------------
        if ($action === 'aceptarSolicitud' && $tipoUsuario === 'contratista') {
            $idSol = (int)($body['id_solicitud'] ?? 0);
    
            if ($idSol <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
                exit;
            }
    
            $ok = $db->aceptarSolicitud($idSol, $idUsuario);
    
            echo json_encode([
                "status" => $ok ? "success" : "error",
                "message" => $ok ? "Solicitud aceptada correctamente" : "Error al aceptar la solicitud"
            ]);
            exit;
        }
    
        // ---------------------------
        // Acción POST no válida
        // ---------------------------
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción POST no válida"]);
        exit;
    
  case 'DELETE':
    if ($action === 'eliminarSolicitud') {
        parse_str(file_get_contents("php://input"), $delVars);
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario']!=='cliente') {
            http_response_code(401);
            echo json_encode(["status"=>"error","message"=>"No autorizado"]); 
            exit;
        }
        $idDel = $delVars['id_solicitud'] ?? 0;
        $ok = $db->eliminarSolicitud($idDel);
        echo json_encode([
            "status"=>$ok?"success":"error",
            "message"=>$ok?"Solicitud eliminada.":"Error al eliminar."
        ]);
        exit;
    }
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"Acción DELETE no válida"]);
    exit;


  default:
    http_response_code(405);
    echo json_encode(["status"=>"error","message"=>"Método no permitido"]);
    exit;
}
