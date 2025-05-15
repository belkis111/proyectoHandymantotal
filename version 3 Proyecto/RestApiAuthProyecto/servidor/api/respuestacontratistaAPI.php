<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/SolicitudesDB.php';

header('Content-Type: application/json; charset=utf-8');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['accion'] === 'responder') {
    $datos = json_decode(file_get_contents("php://input"), true);
    $id_solicitud = $datos['Id_solicitud'];
    $precio = $datos['Precio_estimado'];
    $mensaje = $datos['Mensaje_respuesta'];  // El mensaje del contratista
    
    // Concatenamos precio y mensaje para que todo esté en la misma columna 'Mensaje'
    $mensaje_respuesta = "Presupuesto estimado: $precio. Mensaje: $mensaje";

    $sql = "UPDATE solicitudes SET  Mensaje = ?,  Estado = 'en_proceso'  WHERE Id_solicitud = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $mensaje_respuesta, $id_solicitud);
    $stmt->execute();

    if ($stmt->error) {
        echo json_encode(["error" => "Error al responder la solicitud: " . $stmt->error]);
    } else {
        echo json_encode(["mensaje" => "Solicitud respondida correctamente."]);
    }
}
