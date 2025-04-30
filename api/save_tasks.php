<?php
header('Content-Type: application/json');

// Habilitar reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la conexión
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "rsc_mantenimiento";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Método no permitido']));
}

// Leer el input JSON
$json = file_get_contents('php://input');
if ($json === false) {
    http_response_code(400);
    die(json_encode(['error' => 'Error leyendo el input JSON']));
}

$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]));
}

if (empty($data['tasks'])) {
    http_response_code(400);
    die(json_encode(['error' => 'No se recibieron tareas']));
}

session_start();
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    die(json_encode(['error' => 'No autenticado']));
}

// Validar que el usuario_id sea numérico
if (!is_numeric($_SESSION['id_usuario'])) {
    http_response_code(400);
    die(json_encode(['error' => 'ID de usuario inválido']));
}
$usuario_id = (int)$_SESSION['id_usuario'];

try {
    // Iniciar transacción
    if (!$conn->begin_transaction()) {
        throw new Exception("No se pudo iniciar la transacción");
    }
    
    $saved = 0;
    foreach ($data['tasks'] as $task) {
        // Validar estructura de la tarea
        if (!isset($task['fecha']) || !isset($task['actividad']) || !isset($task['equipo'])) {
            continue;
        }
        
        // Validar fecha
        $fecha = trim($task['fecha']);
        if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
            continue;
        }
        
        // Buscar id_equipo por tag_numero
        $id_equipo = null;
        $tag_equipo = trim($task['equipo']);
        if (!empty($tag_equipo)) {
            $stmt = $conn->prepare("SELECT id_equipo FROM equipos WHERE tag_numero = ? LIMIT 1");
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $conn->error);
            }
            
            $stmt->bind_param("s", $tag_equipo);
            if (!$stmt->execute()) {
                $stmt->close();
                throw new Exception("Error ejecutando consulta: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            if ($result && $equipo = $result->fetch_assoc()) {
                $id_equipo = (int)$equipo['id_equipo'];
            }
            $stmt->close();
        }
        
        // Insertar evento
        $stmt = $conn->prepare("INSERT INTO eventos (fecha, titulo, id_equipo) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparando inserción: " . $conn->error);
        }
        
        // Manejar NULL para id_equipo
        $stmt->bind_param("ssi", $fecha, $task['actividad'], $id_equipo);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Error ejecutando inserción: " . $stmt->error);
        }
        
        if ($stmt->affected_rows > 0) {
            $saved++;
        }
        $stmt->close();
    }
    
    // Confirmar transacción
    if (!$conn->commit()) {
        throw new Exception("Error al confirmar transacción");
    }
    
    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'total' => count($data['tasks'])
    ]);
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if (isset($conn) && method_exists($conn, 'rollback')) {
        $conn->rollback();
    }
    
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}