<?php
// Configuración de seguridad mejorada
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    header('Content-Type: application/json');
    http_response_code(403);
    die(json_encode(['error' => 'Acceso no permitido']));
}

// Configuración de Ollama
define('OLLAMA_HOST', 'http://localhost:11434');
define('OLLAMA_MODEL', 'llama3.2:1b'); // Nombre exacto de tu modelo

// Tiempo máximo de ejecución (en segundos)
set_time_limit(120);

// Habilitar CORS solo para desarrollo local
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
?>