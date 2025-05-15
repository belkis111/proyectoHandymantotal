<?php
require_once __DIR__ . '/../config/database.php';

class OfertasDB {
    private $conn;

    public function __construct() {
        $db    = new Database();
        $this->conn = $db->getConnection();  // PDO ó mysqli
    }

    /**
     * Crear una oferta para una solicitud (contratista)
     */
 public function crearOferta($idSolicitud, $idContratista, $precio, $mensaje) {
    try {
        $this->conn->beginTransaction();

        // 1) Insertar la oferta
        $sql1 = "INSERT INTO ofertas (Id_solicitud, Id_contratista, Precio_ofertado, Mensaje, Estado, Fecha_oferta)
                 VALUES (:sol, :cont, :pre, :msg, 'pendiente', NOW())";
        $stmt1 = $this->conn->prepare($sql1);
        if (!$stmt1->execute([
            ':sol' => $idSolicitud,
            ':cont'=> $idContratista,
            ':pre' => $precio,
            ':msg' => $mensaje
        ])) {
            throw new Exception("Error al insertar oferta");
        }

        // 2) Actualizar la solicitud a 'con_oferta'
        $sql2 = "UPDATE solicitudes SET Estado = 'con_oferta' WHERE Id_solicitud = :sol";
        $stmt2 = $this->conn->prepare($sql2);
        if (!$stmt2->execute([':sol' => $idSolicitud])) {
            throw new Exception("Error al actualizar solicitud");
        }

        $this->conn->commit();
        return ['status'=>'success','message'=>'Oferta creada y solicitud en oferta'];
    } catch (Exception $e) {
        $this->conn->rollBack();
        return ['status'=>'error','message'=>$e->getMessage()];
    }
}

    /**
     * Listar todas las ofertas de una solicitud dada
     */
    public function listarOfertasPorSolicitud($idCliente, $idSolicitud) {
        $sql = "SELECT 
                  o.Id_oferta,
                  o.Precio_ofertado,
                  o.Mensaje,
                  o.Estado,
                  o.Fecha_oferta,
                  c.Nombre AS Contratista
                FROM ofertas o
                JOIN contratista c ON o.Id_contratista = c.Id_contratista
                JOIN solicitudes s ON o.Id_solicitud = s.Id_solicitud
                WHERE o.Id_solicitud = :sol AND c.Id_cliente = :cli
                ORDER BY o.Fecha_oferta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sol'=>$idSolicitud , ':cli'=>$idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOfertasPorCliente(int $idCliente) {
    $sql = "
        SELECT
            o.Id_oferta,
            o.Id_solicitud,
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
    $stmt->execute([':cli' => $idCliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Aceptar una oferta (cliente):
     *  - actualiza el estado de la oferta
     *  - marca la solicitud como “servicio” y asigna el contratista elegido
     */
   public function aceptarOferta(int $idOferta) {
    try {
        $this->conn->beginTransaction();

        // 1) Obtener datos de la oferta elegida
        $sql1 = "SELECT Id_solicitud, Id_contratista, Precio_ofertado 
                 FROM ofertas 
                 WHERE Id_oferta = :of";
        $stm1 = $this->conn->prepare($sql1);
        $stm1->execute([':of' => $idOferta]);
        $of = $stm1->fetch(PDO::FETCH_ASSOC);
        if (!$of) {
            throw new Exception("Oferta $idOferta no encontrada");
        }

        // 2) Convertir la solicitud en servicio y ponerla en proceso
        $sql2 = "UPDATE solicitudes
                 SET
                   Tipo_solicitud  = 'servicio',
                   Estado          = 'en_proceso',
                   Id_contratista  = :cont,
                   Precio_estimado = :pre
                 WHERE Id_solicitud = :sol";
        $stm2 = $this->conn->prepare($sql2);
        $ok2 = $stm2->execute([
            ':cont'=> $of['Id_contratista'],
            ':pre' => $of['Precio_ofertado'],
            ':sol' => $of['Id_solicitud']
        ]);
        if (!$ok2) {
            $err = $stm2->errorInfo();
            throw new Exception("Error al actualizar solicitud: {$err[2]}");
        }

        // 3) Marcar la oferta elegida como aceptada
        $sql3 = "UPDATE ofertas
                 SET Estado = 'aceptada'
                 WHERE Id_oferta = :of";
        $this->conn->prepare($sql3)->execute([':of' => $idOferta]);

        // 4) Rechazar todas las demás ofertas de esa solicitud
        $sql4 = "UPDATE ofertas
                 SET Estado = 'rechazada'
                 WHERE Id_solicitud = :sol AND Id_oferta <> :of";
        $this->conn->prepare($sql4)
                   ->execute([':sol' => $of['Id_solicitud'], ':of' => $idOferta]);

        $this->conn->commit();
        return ['status'=>'success','message'=>'Oferta aceptada y solicitud convertida en servicio.'];
    }
    catch (Exception $e) {
        $this->conn->rollBack();
        return ['status'=>'error','message'=>$e->getMessage()];
    }
}

    public function enviarOferta($idSol, $idCon, $precio, $mensaje) {
        $sql = "INSERT INTO ofertas (Id_solicitud, Id_contratista, Precio_ofertado, Mensaje) 
                VALUES (:sol, :cont, :pre, :msg)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sol' => $idSol,
            ':cont'=> $idCon,
            ':pre' => $precio,
            ':msg' => $mensaje
        ]);
    }
    public function guardarOferta($idSol, $idCon, $precio, $mensaje) {
        $sql = "INSERT INTO ofertas (Id_solicitud, Id_contratista, Precio_ofertado, Mensaje) 
                VALUES (:sol, :cont, :pre, :msg)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sol' => $idSol,
            ':cont'=> $idCon,
            ':pre' => $precio,
            ':msg' => $mensaje
        ]);
    }
    public function rechazarOferta($idOferta) {
        $sql = "UPDATE ofertas SET Estado='rechazada' WHERE Id_oferta = :of";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([':of'=>$idOferta]);
        return [
            'status' => $ok ? 'success' : 'error',
            'message' => $ok ? 'Oferta rechazada' : 'Error al rechazar oferta'
        ];
    }
}
