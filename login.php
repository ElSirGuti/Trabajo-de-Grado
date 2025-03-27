<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Datos incompletos']));
}

$email = trim($data['email']);
$password = $data['password']; // Contraseña ya encriptada del cliente

// Buscar usuario en la base de datos
$stmt = $conn->prepare("SELECT id_usuario, nombre, correo, contrasena, rol FROM usuarios WHERE correo = ?");
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Error de preparación: ' . $conn->error]));
}

$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Error de ejecución: ' . $stmt->error]));
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Credenciales incorrectas']));
}

$usuario = $result->fetch_assoc();

// Verificar contraseña (ya está encriptada, comparamos directamente)
if ($password !== $usuario['contrasena']) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Credenciales incorrectas']));
}

// Iniciar sesión
$_SESSION['user_id'] = $usuario['id_usuario'];
$_SESSION['user_name'] = $usuario['nombre'];
$_SESSION['user_email'] = $usuario['correo'];
$_SESSION['user_role'] = $usuario['rol'];

echo json_encode(['success' => true, 'message' => 'Login exitoso']);

$stmt->close();
$conn->close();
?>