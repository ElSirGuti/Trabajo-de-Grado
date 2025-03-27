<?php
require_once 'conexion.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre']) || !isset($data['correo']) || !isset($data['rol']) || !isset($data['contrasena'])) {
        throw new Exception('Datos incompletos');
    }

    $nombre = trim($data['nombre']);
    $correo = trim($data['correo']);
    $rol = $data['rol'];
    $contrasena = $data['contrasena'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Correo electrónico no válido');
    }

    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    if (!$stmt) {
        throw new Exception('Error de preparación: ' . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    if (!$stmt->execute()) {
        throw new Exception('Error de ejecución: ' . $stmt->error);
    }

    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        throw new Exception('El correo electrónico ya está registrado');
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception('Error de preparación: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);

    if (!$stmt->execute()) {
        throw new Exception('Error al crear usuario: ' . $stmt->error);
    }

    echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>