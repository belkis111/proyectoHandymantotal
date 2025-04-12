<?php
session_start();
require_once('Rutas.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rutas = new Rutas();

    $tipo_usuario = $_POST["tipo_usuario"]; // Cliente o Contratista
    $correo_electronico = trim(filter_var($_POST["Correo_electronico"], FILTER_SANITIZE_EMAIL));
    $contrasena = trim($_POST["Contrasena"]);
    $data = json_encode(["Correo_electronico" => $correo_electronico, "Contrasena" => $contrasena,  "TipoUsuario" => $tipo_usuario]);

    
    $ch = curl_init($rutas->getloginApiUrl());
  
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $res = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON
    $response = json_decode($res, true);

    // Verificar la estructura de la respuesta
    if ($response && isset($response["status"]) && $response["status"] == "success") {
        if (isset($response["message"]["token"])) {
            $_SESSION["usuario"] = $correo_electronico;
            $_SESSION["tipo_usuario"] = $tipo_usuario;
            $_SESSION["token"] = $response["message"]["token"];

// Redirigir según el tipo de usuario
if ($tipo_usuario === "administrador") {
    header("Location: admin/dashboard_admin.html");
} elseif ($tipo_usuario === "cliente") {
    header("Location: index.php"); // o tu dashboard del cliente
} elseif ($tipo_usuario === "contratista") {
    header("Location: index.php"); // o tu dashboard del contratista
}
exit();

          //  exit;
        } else {
            $error = "Error en la autenticación";
        }
    } else {
        $error = "Credenciales incorrectas";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST">
    <label>Tipo de Usuario:</label>
        <select name="tipo_usuario">
            <option value="cliente">Cliente</option>
            <option value="contratista">Contratista</option>
            <option value="administrador">Administrador</option>
        </select><br>
        <label>Correo_electronico:</label>
        <input type="Correo_electronico" name="Correo_electronico" required><br>
        <label>Contrasena:</label>
        <input type="password" name="Contrasena" required><br>
        <button type="submit">Ingresar</button>
    </form>
    <?= isset($error) ? "<p>$error</p>" : "" ?>
</body>
</html>