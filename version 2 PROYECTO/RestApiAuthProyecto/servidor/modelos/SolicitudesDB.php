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
        $sql = "SELECT Id_contratista, Nombre, Especialidad
                FROM contratista";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtrar contratistas por especialidad
     */
    public function filtrarContratistasPorEspecialidad($esp) {
        $pat = '%' . strtolower($esp) . '%';
        $sql = "SELECT Id_contratista, Nombre, Especialidad
                FROM contratista
                WHERE LOWER(Especialidad) LIKE :pat";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pat', $pat, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea una solicitud (presupuesto o servicio) según $tipoSolicitud
     *
     * @param int    $idCliente
     * @param string $categoria
     * @param string $subcategoria
     * @param string $titulo
     * @param string $descripcion
     * @param string $ubicacion
     * @param string $tipoSolicitud   'presupuesto' o 'servicio'
     * @return bool
     */
    public function crearSolicitud(
        $idCliente,
        $categoria,
        $subcategoria,
        $titulo,
        $descripcion,
        $ubicacion,
        $tipoSolicitud,
        $idContratista = null   // parámetro opcional
    ) {
        $sql = "INSERT INTO solicitudes
                (Id_Cliente, Categoria, Subcategoria, Titulo, Descripcion, Ubicacion,
                 Tipo_solicitud, Id_contratista)
                VALUES (:cli, :cat, :sub, :tit, :des, :ubi, :tipo, :cont)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cli'   => $idCliente,
            ':cat'   => $categoria,
            ':sub'   => $subcategoria,
            ':tit'   => $titulo,
            ':des'   => $descripcion,
            ':ubi'   => $ubicacion,
            ':tipo'  => $tipoSolicitud,
            ':cont'  => $idContratista   // null si es presupuesto
        ]);
    }
    /**
     * Contratar un servicio: actualiza una solicitud existente a tipo 'servicio'
     *
     * @param int   $idSolicitud
     * @param int   $idContratista
     * @param float $precioEstimado
     * @return bool
     */
    public function contratarServicio($idSolicitud, $idContratista, $precioEstimado) {
        $sql = "UPDATE solicitudes
                SET Id_contratista    = :cont,
                    Precio_estimado   = :pre,
                    Tipo_solicitud    = 'servicio',
                    Estado            = 'en_proceso'
                WHERE Id_solicitud = :sol";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cont' => $idContratista,
            ':pre'  => $precioEstimado,
            ':sol'  => $idSolicitud
        ]);
    }

    /**
 * Devuelve todas las solicitudes de un cliente, con datos básicos de contratista si ya hay uno asignado
 */
public function listarSolicitudesPorCliente($idCliente) {
    $sql = "SELECT 
                s.Id_solicitud,
                s.Tipo_solicitud,
                s.Categoria,
                s.Subcategoria,
                s.Titulo,
                s.Descripcion,
                s.Ubicacion,
                s.Precio_estimado,
                s.Estado,
                s.Fecha_solicitud,
                s.Fecha_respuesta,
                c.Nombre AS Nombre_contratista
            FROM solicitudes s
            LEFT JOIN contratista c ON s.Id_contratista = c.Id_contratista
            WHERE s.Id_Cliente = :cli
            ORDER BY s.Fecha_solicitud DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':cli', $idCliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function eliminarSolicitud($idSolicitud) {
    $sql = "DELETE FROM solicitudes WHERE Id_solicitud = :sol";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':sol' => $idSolicitud]);
}



  // Lista solicitudes tipo presupuesto y estado 'pendiente'
  public function listarSolicitudesPendientes() {
    $sql = "
      SELECT Id_solicitud, Categoria, Subcategoria,
             Titulo, Descripcion, Fecha_solicitud
        FROM solicitudes
       WHERE Tipo_solicitud='presupuesto'
         AND Estado='pendiente'
       ORDER BY Fecha_solicitud DESC
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Acepta la solicitud: fija contratista y cambia estado
  public function aceptarSolicitud($idSolicitud, $idContratista) {
    $sql = "
      UPDATE solicitudes
         SET Estado='en_proceso',
             Tipo_solicitud='servicio',
             Id_contratista = :cont
       WHERE Id_solicitud = :sol
    ";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':cont'=> $idContratista,
      ':sol' => $idSolicitud
    ]);
  }




}

