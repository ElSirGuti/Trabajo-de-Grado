<?php
require_once 'conexion.php';

// Establecer headers primero, sin espacios antes
header('Content-Type: application/json');

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (!isset($data['nombre']) || !isset($data['correo']) || !isset($data['rol']) || !isset($data['contrasena'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Datos incompletos']));
}

$nombre = trim($data['nombre']);
$correo = trim($data['correo']);
$rol = $data['rol'];
$contrasena = $data['contrasena']; // Ya viene encriptada del cliente

// Validar formato de email
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Correo electrónico no válido']));
}

// Verificar si el usuario ya existe
$stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Error de preparación: ' . $conn->error]));
}

$stmt->bind_param("s", $correo);
if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Error de ejecución: ' . $stmt->error]));
}

$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']));
}
$stmt->close();

// Insertar nuevo usuario
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Error de preparación: ' . $conn->error]));
}

$stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>