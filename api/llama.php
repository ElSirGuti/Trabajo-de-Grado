<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once __DIR__.'/../conexion.php'; // Ajuste en la ruta

// Configuración de seguridad
$allowed_ips = ['127.0.0.1', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    http_response_code(403);
    die(json_encode(['error' => 'Acceso no permitido']));
}

// Obtener datos del POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['error' => 'JSON inválido']));
}

$prompt = $data['prompt'] ?? '';

if (empty($prompt)) {
    http_response_code(400);
    die(json_encode(['error' => 'Prompt vacío']));
}

// Función para interactuar con Ollama
function query_ollama($prompt) {
    $url = 'http://localhost:11434/api/generate';
    
    $data = [
        'model' => 'llama3.2:1b',
        'prompt' => $prompt,
        'stream' => false,
        'options' => [
            'temperature' => 0.7,
            'num_ctx' => 2048,
            'seed' => 42
        ]
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 90
    ]);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new Exception("Error en Ollama: $error");
    }
    
    return json_decode($response, true);
}

try {
    $ollamaResponse = query_ollama($prompt);
    
    if (!isset($ollamaResponse['response'])) {
        throw new Exception('Respuesta inesperada de Ollama');
    }
    
    echo json_encode([
        'response' => $ollamaResponse['response']
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>