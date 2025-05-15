<?php
require_once __DIR__ . '/../config/database.php';

class SolicitudesDB {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection(); // Asumimos PDO
    }

    /**
     * Listar contratistas para el dashboard del cliente
     */
    public function listarContratistas() {
        $sql = "SELECT Id_contratista, Nombre, Especialidad FROM contratista";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtrar contratistas por especialidad
     */
    public function filtrarContratistasPorEspecialidad($esp) {
        $pat = '%' . strtolower($esp) . '%';
        $sql = "SELECT Id_contratista, Nombre, Especialidad FROM contratista WHERE LOWER(Especialidad) LIKE :pat";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pat', $pat, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea una solicitud (presupuesto o servicio)
     */
   public function crearSolicitud($idCliente, $categoria, $subcategoria, $titulo, $descripcion, $ubicacion, $tipoSolicitud, $idContratista = null) {
    // Decide estado inicial según el tipo
    $estadoInicial = $tipoSolicitud === 'presupuesto'
                     ? 'pendiente'
                     : 'pendiente'; // o 'en_proceso' si quisieras que directos vayan a activos

    $sql = "INSERT INTO solicitudes
            (Id_Cliente, Categoria, Subcategoria, Titulo, Descripcion, Ubicacion, Tipo_solicitud, Id_contratista, Estado)
            VALUES (:cli, :cat, :sub, :tit, :des, :ubi, :tipo, :cont, :est)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':cli'   => $idCliente,
        ':cat'   => $categoria,
        ':sub'   => $subcategoria,
        ':tit'   => $titulo,
        ':des'   => $descripcion,
        ':ubi'   => $ubicacion,
        ':tipo'  => $tipoSolicitud,
        ':cont'  => $idContratista,
        ':est'   => $estadoInicial
    ]);
}
    /**
     * Contratar un servicio: actualiza una solicitud existente
     */
    public function contratarServicio($idSolicitud, $idContratista, $precioEstimado) {
        $sql = "UPDATE solicitudes SET Id_contratista = :cont, Precio_estimado = :pre, Tipo_solicitud = 'servicio', Estado = 'en_proceso' WHERE Id_solicitud = :sol";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':cont' => $idContratista, ':pre' => $precioEstimado, ':sol' => $idSolicitud]);
    }

    /**
     * Listar solicitudes de un cliente
     */
    public function listarSolicitudesPorCliente($idCliente) {
        $sql = "SELECT s.Id_solicitud, s.Tipo_solicitud, s.Categoria, s.Subcategoria, s.Titulo, s.Descripcion, s.Ubicacion, s.Precio_estimado, s.Estado, s.Fecha_solicitud, s.Fecha_respuesta, c.Nombre AS Nombre_contratista
                FROM solicitudes s
                LEFT JOIN contratista c ON s.Id_contratista = c.Id_contratista
                WHERE s.Id_Cliente = :cli
                ORDER BY s.Fecha_solicitud DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cli', $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

public function listarOfertasPorSolicitud($idSolicitud) {
    $sql = "SELECT o.Id_oferta, o.Precio_ofertado, o.Mensaje, o.Estado AS Estado_oferta, o.Fecha_oferta, c.Nombre AS Nombre_contratista
            FROM ofertas o
            JOIN contratistas c ON o.Id_contratista = c.Id_contratista
            WHERE o.Id_solicitud = :sol
            ORDER BY o.Fecha_oferta DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':sol' => $idSolicitud]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    /**
     * Eliminar una solicitud
     */
    public function eliminarSolicitud($idSolicitud) {
        $sql = "DELETE FROM solicitudes WHERE Id_solicitud = :sol";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':sol' => $idSolicitud]);
    }
    /**
 * Listar todas las ofertas recibidas por un cliente
 */
public function cargarOfertasRecibidas(int $idCliente) {
    $sql = "
      SELECT
        o.Id_oferta,
        s.Id_solicitud,
        s.Tipo_solicitud,
        s.Titulo,
        o.Precio_ofertado,
        o.Mensaje,
        o.Estado AS Estado_oferta,
        o.Fecha_oferta,
        c.Nombre AS Nombre_contratista
      FROM ofertas o
      JOIN solicitudes s ON o.Id_solicitud = s.Id_solicitud
      JOIN contratista c ON o.Id_contratista = c.Id_contratista
      WHERE s.Id_Cliente = :cli
      ORDER BY o.Fecha_oferta DESC
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':cli', $idCliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // ------------ Funciones para el Contratista ------------

    /**
     * Presupuestos pendientes con nombre de cliente
     */
    public function cargarPresupuestosPendientesContratista() {
        $sql = "SELECT s.Id_solicitud,  s.Categoria, s.Subcategoria, s.Titulo, s.Descripcion, s.Fecha_solicitud, cl.Nombre AS Nombre_cliente
                FROM solicitudes s
                JOIN cliente cl ON s.Id_Cliente = cl.Id_Cliente
                WHERE s.Tipo_solicitud = 'presupuesto' AND s.Estado IN ('pendiente', 'con_oferta')
                ORDER BY s.Fecha_solicitud DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   // Servicios en proceso con nombre de cliente
   public function cargarServiciosActivos($idContratista) {
    $sql = "
        SELECT
            s.Id_solicitud,
            s.Categoria,
            s.Subcategoria,
            s.Titulo,
            s.Descripcion,
            s.Precio_estimado,       /* para ver el monto */
            s.Fecha_solicitud,
            cl.Nombre    AS Nombre_cliente
        FROM solicitudes AS s
        JOIN cliente    AS cl     /* tu tabla real es 'cliente' */
          ON s.Id_Cliente = cl.Id_Cliente
        WHERE
            s.Id_contratista = :contratista
            AND s.Estado     = 'en_proceso'
        ORDER BY s.Fecha_solicitud DESC
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':contratista', $idContratista, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /**
     * Servicios disponibles con nombre de cliente
     */
   public function cargarServiciosDisponibles(int $idContratista) {
    $sql = "
      SELECT s.Id_solicitud, s.Categoria, s.Subcategoria, s.Titulo, s.Descripcion, s.Fecha_solicitud, cl.Nombre AS Nombre_cliente
      FROM solicitudes s
      JOIN cliente cl ON s.Id_Cliente = cl.Id_Cliente
      WHERE s.Tipo_solicitud = 'servicio'
        AND s.Estado = 'pendiente'
        AND (
             s.Id_contratista IS NULL
             OR s.Id_contratista = :idContratista
        )
      ORDER BY s.Fecha_solicitud DESC
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':idContratista' => $idContratista]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Servicios finalizados con nombre de cliente
     */
    public function cargarFinalizados($idContratista) {
        $sql = "SELECT s.Id_solicitud, s.Categoria, s.Subcategoria, s.Titulo, s.Descripcion, s.Fecha_solicitud, s.Fecha_respuesta, cl.Nombre AS Nombre_cliente
                FROM solicitudes s
                JOIN cliente cl ON s.Id_Cliente = cl.Id_Cliente
                WHERE s.Estado = 'completado' AND s.Tipo_solicitud = 'servicio' AND s.Id_contratista = :contratista
                ORDER BY s.Fecha_respuesta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':contratista', $idContratista, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Responde una solicitud de presupuesto
     */
    public function responderSolicitud($idSolicitud, $precio, $mensaje) {
    $sql = "
      UPDATE solicitudes 
      SET 
        Mensaje_contratista = :mensaje,
        Precio_estimado     = :precio,
        Fecha_respuesta     = NOW(),
        Id_contratista      = :cont,   
        Estado              = 'con_oferta'
      WHERE Id_solicitud = :sol
    ";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':mensaje' => $mensaje,
      ':precio'  => $precio,
      ':cont'    => $_SESSION['usuario_id'], 
      ':sol'     => $idSolicitud
    ]);
}

    /**
     * Aceptar solicitud pendiente
     */
    public function aceptarSolicitud($idSolicitud, $idContratista) {
        $sql = "UPDATE solicitudes SET Estado = 'en_proceso', Tipo_solicitud = 'servicio', Id_contratista = :cont WHERE Id_solicitud = :sol";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':cont' => $idContratista, ':sol' => $idSolicitud]);
    }

    /**
     * Marcar solicitud como completada
     */
public function marcarCompletado($idSolicitud) {
    // Actualiza estado y asigna la fecha de respuesta al momento actual
    $sql = "
        UPDATE solicitudes 
        SET 
          Estado = 'completado',
          Fecha_respuesta = NOW()  /* <-- aquí asignamos la fecha */
        WHERE Id_solicitud = :sol
    ";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':sol' => $idSolicitud]);
}
}