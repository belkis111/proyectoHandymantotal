<?php
require_once('Rutas.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["Nombre"]);
    $direccion= htmlspecialchars($_POST["Direccion"]);
    $telefono= htmlspecialchars($_POST["Telefono"]);
    $correo_electronico= filter_var($_POST["Correo_electronico"], FILTER_SANITIZE_EMAIL);
    $contrasena= $_POST["Contrasena"];
    $tipoUsuario = $_POST["TipoUsuario"];
    $especialidad = $tipoUsuario === "contratista" ? htmlspecialchars($_POST["Especialidad"]) : null;

    $rutas= new Rutas();
    
    // Crear JSON
    $data = json_encode(["Nombre" => $nombre, "Direccion" => $direccion, "Telefono" => $telefono,"Correo_electronico" => $correo_electronico, "Contrasena" => $contrasena, "TipoUsuario" => $tipoUsuario,
    "Especialidad" => $especialidad]);
    

    //DEPURACIÓN: Verificar JSON antes de enviarlo

    $ch = curl_init($rutas->getRegisterApiUrl());
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $res = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($res, true);

    //DEPURACIÓN: Verificar respuesta del servidor
    if ($response && $response["status"] == "success") {
        header("Location: login.php");
        exit;
    } else {
        $error = isset($response["message"]) ? $response["message"] : "Error en el registro";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - HandyManTotal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="min-vh-100 d-flex align-items-center justify-content-center py-3">
    <div class="card shadow p-4 bg-white w-100" style="max-width: 400px; max-height: 90vh; overflow-y: auto;">

        <!-- LOGO -->
      <div class="text-center mb-3">
      <img src="assets/logo.png" alt="Logo HandyManTotal" class="img-fluid" style="max-width: 150px;">

      </div>
      
            <h2 class="text-center mb-3">Registro</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="Nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección:</label>
                    <input type="text" name="Direccion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" name="Telefono" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo Electrónico:</label>
                    <input type="email" name="Correo_electronico" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="Contrasena" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Usuario:</label>
                    <select name="TipoUsuario" id="tipoUsuario" class="form-select" required>
                        <option value="cliente">Cliente</option>
                        <option value="contratista">Contratista</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>

                <div class="mb-3" id="especialidadContainer" style="display: none;">
                    <label class="form-label">Especialidad:</label>
                    <input type="text" name="Especialidad" class="form-control">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Registrarse</button>
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

    <script>
        document.getElementById("tipoUsuario").addEventListener("change", function () {
            let especialidadContainer = document.getElementById("especialidadContainer");
            especialidadContainer.style.display = this.value === "contratista" ? "block" : "none";
        });
    </script>
</body>
</html>
