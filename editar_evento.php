<?php
session_start();
include 'conexion.php';

if (!in_array($_SESSION['rol'], ['Administrador', 'Super'])) {
    die(json_encode(['success' => false, 'message' => 'Permisos insuficientes']));
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$titulo = $data['titulo'];
$fecha = $data['fecha'];
$id_equipo = $data['id_equipo'] ?: null;

// Validar fecha pasada (solo para no-Admin/Super, aunque ya se validó en JS)
$hoy = date('Y-m-d');
if ($fecha < $hoy && !in_array($_SESSION['rol'], ['Administrador', 'Super'])) {
    die(json_encode(['success' => false, 'message' => 'No puedes editar eventos en fechas pasadas']));
}

$stmt = $conn->prepare("UPDATE eventos SET titulo = ?, fecha = ?, id_equipo = ? WHERE id = ?");
$stmt->bind_param("sssi", $titulo, $fecha, $id_equipo, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos']);
}
?>