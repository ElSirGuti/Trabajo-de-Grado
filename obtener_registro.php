<?php
require_once 'conexion.php';

// Asegurar que solo se devuelva JSON
header('Content-Type: application/json');

// Verificar que el ID de inspección existe y es numérico
if (!isset($_GET['id_inspeccion']) || !is_numeric($_GET['id_inspeccion'])) {
    echo json_encode(['error' => 'ID de inspección no proporcionado o inválido']);
    exit;
}

$id_inspeccion = $_GET['id_inspeccion'];

try {
    // Obtener datos básicos del informe incluyendo información del cliente
    $stmt = $conn->prepare("
        SELECT 
            e.*, 
            i.*, 
            c.codigo_cliente,
            c.nombre_cliente,
            c.rif_ci,
            c.domicilio_fiscal,
            d.prioridad, 
            d.nivel_vibracion,
            a.analisis,
            a.recomendaciones
        FROM inspecciones i
        JOIN equipos e ON i.id_equipo = e.id_equipo
        JOIN clientes c ON e.id_cliente = c.id_cliente
        JOIN diagnosticos d ON i.id_inspeccion = d.id_inspeccion
        LEFT JOIN analisis a ON d.id_diagnostico = a.id_diagnostico
        WHERE i.id_inspeccion = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta principal: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_inspeccion);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta principal: " . $stmt->error);
    }
    
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Registro no encontrado']);
        exit;
    }

    $data = $result->fetch_assoc();

    // Obtener fallas asociadas
    $fallas = [];
    $stmt_fallas = $conn->prepare("
        SELECT lf.falla 
        FROM inspeccion_fallas inf 
        JOIN lista_fallas lf ON inf.id_falla = lf.id_falla 
        WHERE inf.id_inspeccion = ?
    ");
    
    if ($stmt_fallas) {
        if (!$stmt_fallas->bind_param("i", $id_inspeccion)) {
            throw new Exception("Error al vincular parámetros para fallas: " . $stmt_fallas->error);
        }
        
        if (!$stmt_fallas->execute()) {
            throw new Exception("Error al ejecutar consulta de fallas: " . $stmt_fallas->error);
        }
        
        $result_fallas = $stmt_fallas->get_result();
        while ($row = $result_fallas->fetch_assoc()) {
            $fallas[] = $row['falla'];
        }
        $stmt_fallas->close();
    }
    $data['fallas'] = $fallas;

    // Obtener hallazgos asociados
    $hallazgos = [];
    $stmt_hallazgos = $conn->prepare("
        SELECT lh.hallazgo 
        FROM inspeccion_hallazgos inh 
        JOIN lista_hallazgos lh ON inh.id_hallazgo = lh.id_hallazgo 
        WHERE inh.id_inspeccion = ?
    ");
    
    if ($stmt_hallazgos) {
        if (!$stmt_hallazgos->bind_param("i", $id_inspeccion)) {
            throw new Exception("Error al vincular parámetros para hallazgos: " . $stmt_hallazgos->error);
        }
        
        if (!$stmt_hallazgos->execute()) {
            throw new Exception("Error al ejecutar consulta de hallazgos: " . $stmt_hallazgos->error);
        }
        
        $result_hallazgos = $stmt_hallazgos->get_result();
        while ($row = $result_hallazgos->fetch_assoc()) {
            $hallazgos[] = $row['hallazgo'];
        }
        $stmt_hallazgos->close();
    }
    $data['hallazgos'] = $hallazgos;

    // Cerrar conexiones
    $stmt->close();
    
    // Devolver los datos en formato JSON
    echo json_encode($data);
    
} catch (Exception $e) {
    // En caso de error, devolver un JSON con el mensaje de error
    echo json_encode([
        'error' => 'Error en el servidor',
        'details' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Loggear el error para depuración
    error_log("Error en obtener_registro.php: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>