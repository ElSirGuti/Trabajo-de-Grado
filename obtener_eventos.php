<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$sql = "SELECT eventos.id, eventos.titulo, eventos.fecha, eventos.id_equipo, equipos.tag_numero 
        FROM eventos 
        LEFT JOIN equipos ON eventos.id_equipo = equipos.id_equipo";
$result = $conn->query($sql);

$eventos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventos[] = [
            'id' => $row['id'],
            'title' => $row['titulo'] . ' (Equipo: ' . ($row['tag_numero'] ?? 'Sin equipo') . ')', // Mostrar título y equipo
            'start' => $row['fecha'],
            'id_equipo' => $row['id_equipo']
        ];
    }
}

echo json_encode($eventos);

$conn->close();
?>