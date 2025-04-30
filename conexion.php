<?php
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "rsc_mantenimiento";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $servername = "34.10.152.2"; // Usa la IP pública de tu Cloud SQL
// $username = "root"; // O el usuario específico que creaste
// $password = "123456789"; // Cambia por tu contraseña real
// $dbname = "rsc_mantenimiento";

// // Establecer conexión
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Verificar conexión
// if ($conn->connect_error) {
//     die("Error de conexión: " . $conn->connect_error . ". Revisa la configuración de Cloud SQL.");
// }

// // Configurar charset (recomendado)
// $conn->set_charset("utf8mb4");

// echo "¡Conexión exitosa a Cloud SQL!";

?>