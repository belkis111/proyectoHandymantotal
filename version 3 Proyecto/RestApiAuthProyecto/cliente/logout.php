<?php
session_start();
require_once('Rutas.php');

if (isset($_SESSION["token"])) {
    $rutas = new Rutas();

    $ch = curl_init($rutas->getlogoutApiUrl($_SESSION["token"]));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_exec($ch);
    curl_close($ch);
}

session_destroy();
header("Location: index.php");
exit;
?>

