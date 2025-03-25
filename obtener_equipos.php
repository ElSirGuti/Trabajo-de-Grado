<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$sql = "SELECT id_equipo, tag_numero FROM equipos";
$result = $conn->query($sql);

$equipos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipos[] = $row;
    }
}

echo json_encode($equipos);

$conn->close();
?>