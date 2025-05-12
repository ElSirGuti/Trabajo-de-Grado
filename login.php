<?php
session_start(); // Inicia la sesión al principio

require_once 'conexion.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['correo']) || !isset($data['contrasena'])) {
        throw new Exception('Credenciales incompletas');
    }

    $correo = $data['correo'];
    $contrasena = $data['contrasena'];

    error_log("Email recibido: " . $correo);
    error_log("Password hash recibido: " . $contrasena);

    // Consulta SQL para verificar las credenciales y obtener el rol
    $stmt = $conn->prepare("SELECT id_usuario, nombre, rol FROM usuarios WHERE correo = ? AND contrasena = ?");
    if (!$stmt) {
        throw new Exception('Error de preparación: ' . $conn->error);
    }

    $stmt->bind_param("ss", $correo, $contrasena); // Usa los nombres de las variables correctas
    if (!$stmt->execute()) {
        throw new Exception('Error de ejecución: ' . $stmt->error);
    }

    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $nombre, $rol); // Vincula la variable $rol
        $stmt->fetch();

        // Inicia la sesión y guarda información del usuario incluyendo el rol
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = $rol; // ¡Guarda el rol en la sesión!

        echo json_encode(['success' => true]);
        error_log("Inicio de sesión exitoso para: " . $correo . " con rol: " . $rol);
    } else {
        throw new Exception('Credenciales incorrectas');
        error_log("Inicio de sesión fallido para: " . $correo);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log("Error en login.php: " . $e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>