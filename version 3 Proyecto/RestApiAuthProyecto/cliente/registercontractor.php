<?php
require_once('Rutas.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["Nombre"]);
    $direccion = htmlspecialchars($_POST["Direccion"]);
    $telefono = htmlspecialchars($_POST["Telefono"]);
    $correo_electronico = filter_var($_POST["Correo_electronico"], FILTER_SANITIZE_EMAIL);
    $especialidad = htmlspecialchars($_POST["Especialidad"]);
    $contrasena = $_POST["Contrasena"];
    
    $rutas = new Rutas();
    
    // Crear JSON con los datos del contratista
    $data = json_encode(["Nombre" => $nombre, "Direccion" => $direccion, "Telefono" => $telefono, "Correo_electronico" => $correo_electronico, "Especialidad" => $especialidad, "Contrasena" => $contrasena ]);
    
    $ch = curl_init($rutas->getUrlApi()); //. '/UsuariosAPI.php?action=register');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $res = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($res, true);
    
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
    <link href="../cliente/assets/estilos/estilos.css" rel="stylesheet" />

<head>
    <meta charset="UTF-8">
    <title>Registro de Contratista</title>
</head>
<body>
    <h2>Registro de Contratista</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="Nombre" required><br>

        <label>Direccion:</label>
        <input type="text" name="Direccion" required><br>

        <label>Telefono:</label>
        <input type="text" name="Telefono" required><br>

        <label>Correo_electronico:</label>
        <input type="email" name="Correo_electronico" required><br>
        
        <label>Especialidad:</label>
        <label for="especialidad" class="form-label">Especialidad</label>
        <select id="especialidad" name="Especialidad" class="form-select" required>
  <option value="">— Selecciona tu especialidad —</option>
  <option value="carpintero">Carpintero</option>
  <option value="electricista">Electricista</option>
  <option value="pintor">Pintor</option>
  <option value="jardinero">Jardinero</option>
  <option value="plomero">Plomero</option>
  <option value="limpieza">Limpieza</option>
  <option value="albañil">Albañil</option>
  <option value="gas">Gas</option>
  <option value="seguridad">Seguridad</option>
  <option value="tecnico de lavadoras">Técnico de Lavadoras</option>
  <option value="mudanzas">Mudanzas</option>
  <option value="belleza">Belleza</option>
  <option value="otros">Otros</option>
</select>
        
        <label>Contrasena:</label>
        <input type="password" name="Contrasena" required><br>

        <button type="submit">Registrarse</button>
    </form>
    
    <?= isset($error) ? "<p>$error</p>" : "" ?>
</body>
</html>
