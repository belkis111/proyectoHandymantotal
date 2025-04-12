<?php
session_start();
require_once "Rutas.php";
$rutas = new Rutas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h1>BIENVENIDO AL SISTEMA HANDYMANTOTAL</h1>
    <?php if (isset($_SESSION['usuario']) && isset($_SESSION['tipo_usuario'])) : ?>
        <p>Hola, <?= htmlspecialchars($_SESSION['usuario']) ?> (<?= ucfirst($_SESSION['tipo_usuario']) ?>) |
            <a href="logout.php">Cerrar sesión</a>
        </p>
    <?php else : ?>
        <p><a href="login.php">Iniciar sesión</a> | <a href="register.php">Registrarse</a></p>
    <?php endif; ?>
</body>
</html>
