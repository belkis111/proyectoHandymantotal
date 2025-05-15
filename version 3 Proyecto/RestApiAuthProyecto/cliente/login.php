<?php
session_start();
require_once('Rutas.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rutas = new Rutas();

    $tipo_usuario     = $_POST["tipo_usuario"]; // cliente, contratista, administrador
    $correo_electronico = trim(filter_var($_POST["Correo_electronico"], FILTER_SANITIZE_EMAIL));
    $contrasena       = trim($_POST["Contrasena"]);

    // Preparamos el payload de login
    $data = json_encode([
        "Correo_electronico" => $correo_electronico,
        "Contrasena"         => $contrasena,
        "TipoUsuario"        => $tipo_usuario
    ]);

    // Llamada cURL al API de login
    $ch = curl_init($rutas->getloginApiUrl());
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $res = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($res, true);

    if ($response && isset($response["status"]) && $response["status"] === "success" &&
        isset($response["message"]["token"])) {

        // 1) Guardamos datos básicos en la sesión
        $_SESSION["usuario"]      = $correo_electronico;
        $_SESSION["tipo_usuario"] = $tipo_usuario;
        $_SESSION["token"]        = $response["message"]["token"];

        // 2) Ahora consultamos el ID numérico para guardarlo en sesión
        if ($tipo_usuario === "cliente") {
            require_once '../servidor/modelos/ClientesDB.php';
            $clientesDB = new ClientesDB();
            $cliente    = $clientesDB->obtenerPorCorreo($correo_electronico);

            if ($cliente && isset($cliente['Id_Cliente'])) {
                $_SESSION['usuario_id'] = $cliente['Id_Cliente'];
            } else {
                $error = "No se pudo obtener el ID de cliente.";
            }

        } elseif ($tipo_usuario === "contratista") {
            require_once '../servidor/modelos/ContratistasDB.php';
            $contratistasDB = new ContratistasDB();
            $cont           = $contratistasDB->obtenerPorCorreo($correo_electronico);

            if ($cont && isset($cont['Id_contratista'])) {
                $_SESSION['usuario_id'] = $cont['Id_contratista'];
            } else {
                $error = "No se pudo obtener el ID de contratista.";
            }
        }

        // 3) Si no hubo error al obtener el ID, redirigimos
        if (!isset($error)) {
            if ($tipo_usuario === "administrador") {
                header("Location: admin/dashboard_admin.html");
            } elseif ($tipo_usuario === "cliente") {
                header("Location: cliente/dashboard_cliente.html");
            } else { // contratista
                header("Location: contratista/dashboard_contratista.html");
            }
            exit;
        }

    } else {
        $error = "Credenciales incorrectas o fallo en autenticación.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - HandyManTotal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../cliente/assets/estilos/estilos.css" rel="stylesheet" />
</head>

   <!-- 
  <body class="bg-light" style="background: url('assets/fondo.png') no-repeat center center fixed; background-size: cover;">
-->

  <div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4 bg-white" style="max-width: 400px; width: 100%;">

      <!-- LOGO -->
      <div class="text-center mb-4">
        <img src="assets/logo.png" alt="Logo HandyManTotal" style="max-width: 150px;">
      </div>

      <h2 class="text-center mb-3">Iniciar Sesión</h2>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
          <select name="tipo_usuario" id="tipo_usuario" class="form-select" required>
            <option value="cliente">Cliente</option>
            <option value="contratista">Contratista</option>
            <option value="administrador">Administrador</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="Correo_electronico" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" name="Correo_electronico" id="Correo_electronico" required>
        </div>

        <div class="mb-3">
          <label for="Contrasena" class="form-label">Contraseña</label>
          <input type="password" class="form-control" name="Contrasena" id="Contrasena" required>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Ingresar</button>
        </div>
          <!-- Botón de volver al inicio con ícono -->
          <div class="d-grid mt-2">
          <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-house-door-fill me-1"></i>
            Volver al Inicio
          </a>
        </div>
      </form>
    </div>
  </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

