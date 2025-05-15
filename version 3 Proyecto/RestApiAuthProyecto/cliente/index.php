<?php
session_start();
require_once "Rutas.php";
$rutas = new Rutas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - HandyManTotal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../cliente/assets/estilos/estilos.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center">

        <!-- LOGO -->
      <div class="text-center mb-4">
        <img src="assets/logo.png" alt="Logo HandyManTotal" style="max-width: 200px;">
      </div>
      
            <h1 class="mb-4">👷‍♂️ Bienvenido a HandyManTotal</h1>
            <h2 class="mb-4">Aplicación de Servicios</h2>

            <?php if (isset($_SESSION['usuario']) && isset($_SESSION['tipo_usuario'])) : ?>
                <p class="lead">
                    Hola, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong> 
                    (<?= ucfirst($_SESSION['tipo_usuario']) ?>)
                </p>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            <?php else : ?>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
                    <a href="register.php" class="btn btn-success">Registrarse</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">© 2025 HandyManTotal. Todos los derechos reservados.</p>
  </footer>
</body>
</html>

