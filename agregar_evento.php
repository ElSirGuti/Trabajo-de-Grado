<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['fecha']) && isset($data['titulo']) && isset($data['equipos'])) {
    $fecha = $data['fecha'];
    $titulo = $data['titulo'];
    $equipos = $data['equipos'];

    $success = true;
    $message = '';

    foreach ($equipos as $id_equipo) {
        if ($id_equipo === "") {
            $id_equipo = null;
        }
        $sql = "INSERT INTO eventos (fecha, titulo, id_equipo) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $fecha, $titulo, $id_equipo); // Aquí está la línea que continúa

        if (!$stmt->execute()) {
            $success = false;
            $message = $stmt->error;
            break;
        }
    }

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $message]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

$conn->close();
?>