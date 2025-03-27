<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (!isset($data['id']) || !isset($data['nombre']) || !isset($data['correo']) || !isset($data['rol'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$id = $data['id'];
$nombre = trim($data['nombre']);
$correo = trim($data['correo']);
$rol = $data['rol'];

// Validar formato de email
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
    exit;
}

// Verificar si el correo ya existe en otro usuario
$stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? AND id_usuario != ?");
$stmt->bind_param("si", $correo, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado por otro usuario']);
    exit;
}
$stmt->close();

// Construir la consulta SQL según si se actualiza la contraseña o no
if (isset($data['contrasena']) && !empty($data['contrasena'])) {
    $contrasena = $data['contrasena'];
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, contrasena = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nombre, $correo, $rol, $contrasena, $id);
} else {
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>