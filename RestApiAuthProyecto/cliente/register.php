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
    // echo "<pre>JSON Enviado al servidor:<br>";
    // echo $data;
    // echo "</pre>";
    
    $ch = curl_init($rutas->getRegisterApiUrl());
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $res = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($res, true);

    //DEPURACIÓN: Verificar respuesta del servidor
    // echo "<pre>Respuesta del servidor:<br>";
    // print_r($response);
    // echo "</pre>";

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
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>
    <form method="POST" >
        <label>Nombre:</label>
        <input type="text" name="Nombre" required><br>

        <label>Direccion:</label>
        <input type="text" name="Direccion" required><br>

        <label>Telefono</label>
        <input type="text" name="Telefono" required><br>

        <label>Correo_electronico:</label>
        <input type="email" name="Correo_electronico" required><br>
        
        <label>Contrasena:</label>
        <input type="password" name="Contrasena" required><br>

        <label>Tipo de Usuario:</label>
    <select name="TipoUsuario" id="tipoUsuario">
        <option value="cliente">Cliente</option>
        <option value="contratista">Contratista</option>
        <option value="administrador">Administrador</option>
    </select><br>

    <div id="especialidadContainer" style="display: none;">
        <label>Especialidad:</label>
        <input type="text" name="Especialidad"><br>
    </div>


        <button type="submit">Registrarse</button>
    </form>
    
    <?= isset($error) ? "<p>$error</p>" : "" ?>

    <script>
    document.getElementById("tipoUsuario").addEventListener("change", function() {
        let especialidadContainer = document.getElementById("especialidadContainer");
        especialidadContainer.style.display = this.value === "contratista" ? "block" : "none";
    });
</script>

</body>
</html>

