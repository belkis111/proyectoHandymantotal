<?php
error_log("DEBUG: UsuariosAPI.php iniciado", 3, __DIR__ . '/debug.log');

require_once __DIR__ . "/../modelos/ClientesDB.php";
require_once __DIR__ . "/../modelos/ContratistasDB.php";
require_once __DIR__ . "/../modelos/AdministradoresDB.php";
                
// Se define la clase UsuariosAPI
class UsuariosAPI {
    private $dbClientes;
    private $dbContratistas;
    private $dbAdministradores; 

    public function __construct() {
        $this->dbClientes = new ClientesDB();
        $this->dbContratistas = new ContratistasDB();
        $this->dbAdministradores = new AdministradoresDB();
    }

    public function API() {
        header("Content-Type: application/json");
    
    $method = $_SERVER["REQUEST_METHOD"];

        switch ($method) {
            case "GET":
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "listarClientes":
                            $this->obtenerClientes();
                            break;
                        case "obtenerClientePorID":
                            if (isset($_GET["id"])) {
                                $this->obtenerClientePorID($_GET["id"]);
                            } else {
                                $this->response(400, "error", "Falta el parámetro id");
                            }
                            break;
                        case "listarContratistas":
                            $this->obtenerContratistas();
                            break;
                        case "obtenerContratistaPorID":
                            if (isset($_GET["id"])) {
                                $this->obtenerContratistaPorID($_GET["id"]);
                            } else {
                                $this->response(400, "error", "Falta el parámetro id");
                            }
                            break;
                        default:
                            $this->response(405, "error", "Acción no permitida para GET");
                            break;
                    }
                } else {
                    $this->response(405, "error", "No se especificó acción para GET");
                }
                break;
                
            case "POST":
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "register":
                            $this->registrarUsuario();
                            break;
                        case "login":
                            $this->iniciarSesion();
                            break;
                            case "actualizarCliente":
                                $this->actualizarCliente(); 
                                break;
                            case "actualizarContratista":
                                $this->actualizarContratista(); 
                                break;
                            break;
                        default:
                            $this->response(405, "error", "Acción no permitida para POST");
                            break;
                    }
                } else {
                    $this->response(405, "error", "No se especificó acción para POST");
                }
                break;
                
            
                
            case "DELETE":
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "logout":
                            $this->cerrarSesion();
                            break;
                            case "eliminarCliente":
                                if (!isset($_GET["id"])) {
                                    $this->response(400, "error", "Falta el parámetro id");
                                }
                                $id = intval($_GET["id"]);
                                try {
                                    $count = $this->dbClientes->eliminarCliente($id);
                                    if ($count > 0) {
                                        $this->response(200, "success", "Cliente eliminado correctamente");
                                    } else {
                                        $this->response(404, "error", "Cliente no encontrado");
                                    }
                                } catch (\PDOException $e) {
                                    if ($e->getCode() === '23000') {
                                        $this->response(409, "error", "No se puede eliminar el cliente porque tiene solicitudes asociadas");
                                    } else {
                                        $this->response(500, "error", "Error en la base de datos: " . $e->getMessage());
                                    }
                                }
                                break;
                            case "eliminarContratista":
                                if (!isset($_GET["id"])) {
                                    $this->response(400, "error", "Falta el parámetro id");
                                }
                                $id = intval($_GET["id"]);
                                try {
                                    $count = $this->dbContratistas->eliminarContratista($id);
                                    if ($count > 0) {
                                        $this->response(200, "success", "Contratista eliminado correctamente");
                                    } else {
                                        $this->response(404, "error", "Contratista no encontrado");
                                    }
                                } catch (\PDOException $e) {
                                    // 23000 = violation of integrity constraint
                                    if ($e->getCode() === '23000') {
                                        $this->response(409, "error", "No se puede eliminar el contratista porque tiene solicitudes asociadas");
                                    } else {
                                        $this->response(500, "error", "Error en la base de datos: " . $e->getMessage());
                                    }
                                }
                                break;
                        default:
                            $this->response(405, "error", "Acción no permitida para DELETE");
                            break;
                    }
                } else {
                    $this->response(405, "error", "No se especificó acción para DELETE");
                }
                break;
        }
    }

    private function registrarUsuario() {
        try {
            // Leer y decodificar datos del cuerpo de la petición
            $datos = json_decode(file_get_contents("php://input"), true);

            if (!$datos) {
                throw new Exception("Error al decodificar JSON o JSON vacío");
            }

            // Validar que los datos básicos están presentes
            if (!isset($datos["Nombre"], $datos["Direccion"], $datos["Telefono"], $datos["Correo_electronico"], $datos["Contrasena"], $datos["TipoUsuario"])) {
                throw new Exception("Datos incompletos: Se requieren Nombre, Direccion, Telefono, Correo_electronico, Contrasena y TipoUsuario");
            }
            // Verificar tipo de usuario
            $tipoUsuario = $datos["TipoUsuario"];
            $registroExitoso = false;

            if ($datos["TipoUsuario"] === "cliente") {
                $registroExitoso = $this->dbClientes->registrarUsuario(
                    $datos["Nombre"], $datos["Direccion"], $datos["Telefono"], 
                    $datos["Correo_electronico"], $datos["Contrasena"]
                );
            } elseif ($datos["TipoUsuario"] === "contratista") {
                if (!isset($datos["Especialidad"])) {
                    throw new Exception("El campo 'Especialidad' es obligatorio para contratistas");
                }
                $registroExitoso = $this->dbContratistas->registrarContratista(
                    $datos["Nombre"], $datos["Direccion"], $datos["Telefono"], 
                    $datos["Correo_electronico"], $datos["Especialidad"], $datos["Contrasena"]
                );

            } elseif ($tipoUsuario === "administrador") {
                require_once __DIR__ . "/../modelos/AdministradoresDB.php";
                $dbAdministradores = new AdministradoresDB();
 
                $registroExitoso = $dbAdministradores->registrarAdministrador(
                    $datos["Nombre"], $datos["Direccion"], $datos["Telefono"], 
                    $datos["Correo_electronico"], $datos["Contrasena"]
                );
            } else {
                throw new Exception("Tipo de usuario inválido");
            }

            if (!$registroExitoso) {
                return $this->response(200, "error", "El usuario ya se encuentra registrado o ocurrió un error");
            }

            return $this->response(201, "success", "Usuario registrado correctamente");

        } catch (Exception $e) {
            return $this->response(500, "error", $e->getMessage());
        }
    }
    private function iniciarSesion() {
        header("Content-Type: application/json");
        ob_clean();
        
        $datos = json_decode(file_get_contents("php://input"), true);
        if (!$datos || !isset($datos["Correo_electronico"], $datos["Contrasena"], $datos["TipoUsuario"])) {
            echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
            exit;
        }
        
        $correo_electronico = $datos["Correo_electronico"];
        $contrasena = $datos["Contrasena"];
        $tipoUsuario = $datos["TipoUsuario"];
        $id_usuario = false;
        $token = bin2hex(random_bytes(32));
        
        if ($tipoUsuario === "contratista") {
            $id_usuario = $this->dbContratistas->iniciarSesionContratista($correo_electronico, $contrasena);
            if ($id_usuario) {
                $this->dbContratistas->guardarSesion($id_usuario, $token, 'contratista');
            }

        } elseif ($tipoUsuario === "administrador") {
            $id_usuario = $this->dbAdministradores->iniciarSesionAdministrador($correo_electronico, $contrasena);
            if ($id_usuario) {
                $this->dbAdministradores->guardarSesion($id_usuario, $token, 'administrador');
            }
        } else { // Asumimos cliente
            $id_usuario = $this->dbClientes->iniciarSesion($correo_electronico, $contrasena);
            if ($id_usuario) {
                $this->dbClientes->guardarSesion($id_usuario, $token, 'cliente');
            }
        }
        
        if ($id_usuario) {
            echo json_encode(["status" => "success", "message" => ["token" => $token]]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
            exit;
        }
    }
    public function cerrarSesion($token) {
        $query = "DELETE FROM sesiones WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

//Gestión de clientes 

    // Obtener todos los clientes
    public function obtenerClientes() {
        $clientes = $this->dbClientes->obtenerClientes();
        return $this->response(200, "success", $clientes);
    }

    // Obtener cliente por ID
    public function obtenerClientePorID($id_cliente) {
        $cliente = $this->dbClientes->obtenerClientePorID($id_cliente);
        if ($cliente) {
            return $this->response(200, "success", $cliente);
        } else {
            return $this->response(404, "error", "Cliente no encontrado");
        }
    }

    // Actualizar cliente
    public function actualizarCliente() {
        $datos = json_decode(file_get_contents("php://input"), true);
        if (!isset($datos['Id_Cliente'], $datos['Nombre'], $datos['Direccion'], $datos['Telefono'], $datos['Correo_electronico'] )) {
            return $this->response(400, "error", "Datos incompletos");
        }
        $resultado = $this->dbClientes->actualizarCliente($datos['Id_Cliente'], $datos['Nombre'], $datos['Direccion'], $datos['Telefono'], $datos['Correo_electronico']);
        if ($resultado) {
            return $this->response(200, "success", "Cliente actualizado correctamente");
        }
    }

    // Eliminar cliente
    public function eliminarCliente($id_cliente) {
        $resultado = $this->dbClientes->eliminarCliente($id_cliente);
        if ($resultado) {
            return $this->response(200, "success", "Cliente eliminado correctamente");
        } else {
            return $this->response(500, "error", "No se pudo eliminar el cliente");
        }
    }

//Gestión de  contratistas

    // Obtener todos los contratistas
    public function obtenerContratistas() {
        $contratistas = $this->dbContratistas->obtenerContratistas();
        return $this->response(200, "success", $contratistas);
    }

    // Obtener Contratistas por ID
    public function obtenerContratistaPorID($id_contratista) {
        $contratista = $this->dbContratistas->obtenerContratistaPorID($id_contratista);
        if ($contratista) {
            return $this->response(200, "success", $contratista);
        } else {
            return $this->response(404, "error", "Contratista no encontrado");
        }
    }

    
    // Actualizar  contratista
    public function actualizarContratista() {
        $datos = json_decode(file_get_contents("php://input"), true);
        if (!isset($datos['Id_contratista'], $datos['Nombre'], $datos['Direccion'], $datos['Telefono'], $datos['Correo_electronico'], $datos['Especialidad'] )) {
            return $this->response(400, "error", "Datos incompletos");
        }
        $resultado = $this->dbContratistas->actualizarContratista($datos['Id_contratista'], $datos['Nombre'], $datos['Direccion'], $datos['Telefono'], $datos['Correo_electronico'], $datos['Especialidad']);
        if ($resultado) {
            return $this->response(200, "success", "Contratista actualizado correctamente");
        } else {
            return $this->response(500, "error", "No se pudo actualizar el contratista");
        }
    }

    // Eliminar contratista
    public function eliminarContratista($id_contratista) {
        $resultado = $this->dbContratistas->eliminarContratista($id_contratista);
        if ($resultado) {
            return $this->response(200, "success", "Contratista eliminado correctamente");
        } else {
            return $this->response(500, "error", "No se pudo eliminar el contratista");
        }
    }

    
    private function response($code, $status, $message) {
        ob_clean(); // 🔹 Elimina cualquier salida anterior
        http_response_code($code);
        echo json_encode(["status" => $status, "message" => $message], JSON_PRETTY_PRINT);
        exit(); // 🔹 Detiene la ejecución después de enviar la respuesta
    }

    }


// Ejecutar la API
$api = new UsuariosAPI();
$api->API();
?>