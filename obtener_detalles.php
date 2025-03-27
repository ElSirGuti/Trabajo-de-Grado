<?php
require_once 'conexion.php';

$tipo = $_GET['tipo'] ?? '';
$valor = $_GET['valor'] ?? '';

header('Content-Type: text/html; charset=utf-8');

switch ($tipo) {
    case 'tipo_equipo':
        $query = "SELECT * FROM equipos WHERE tipo_equipo = ?";
        $titulo = "Equipos de tipo: " . htmlspecialchars($valor);
        break;

    case 'ubicacion':
        $query = "SELECT * FROM equipos WHERE ubicacion = ?";
        $titulo = "Equipos en ubicación: " . htmlspecialchars($valor);
        break;

    case 'cliente':
        $query = "SELECT e.* FROM equipos e JOIN clientes c ON e.id_cliente = c.id_cliente WHERE c.nombre_cliente = ?";
        $titulo = "Equipos del cliente: " . htmlspecialchars($valor);
        break;

    case 'prioridad':
        $query = "SELECT e.* FROM equipos e 
                     JOIN inspecciones i ON e.id_equipo = i.id_equipo 
                     JOIN diagnosticos d ON i.id_inspeccion = d.id_inspeccion 
                     WHERE d.prioridad = ?";
        $titulo = "Equipos con prioridad: " . htmlspecialchars($valor);
        break;

    case 'vibracion':
        $query = "SELECT e.* FROM equipos e 
                     JOIN inspecciones i ON e.id_equipo = i.id_equipo 
                     JOIN diagnosticos d ON i.id_inspeccion = d.id_inspeccion 
                     WHERE d.nivel_vibracion = ?";
        $titulo = "Equipos con nivel de vibración: " . htmlspecialchars($valor);
        break;

    case 'falla':
        $query = "SELECT e.* FROM equipos e 
                 JOIN inspecciones i ON e.id_equipo = i.id_equipo 
                 JOIN inspeccion_fallas ifa ON i.id_inspeccion = ifa.id_inspeccion 
                 JOIN lista_fallas lf ON ifa.id_falla = lf.id_falla 
                 WHERE lf.falla = ?";
        $titulo = "Equipos con falla: " . htmlspecialchars($valor);
        break;

    case 'hallazgo':
        $query = "SELECT e.* FROM equipos e 
                 JOIN inspecciones i ON e.id_equipo = i.id_equipo 
                 JOIN inspeccion_hallazgos ih ON i.id_inspeccion = ih.id_inspeccion 
                 JOIN lista_hallazgos lh ON ih.id_hallazgo = lh.id_hallazgo 
                 WHERE lh.hallazgo = ?";
        $titulo = "Equipos con hallazgo: " . htmlspecialchars($valor);
        break;

    case 'inspecciones_equipo':
        $query = "SELECT i.* FROM inspecciones i 
                 JOIN equipos e ON i.id_equipo = e.id_equipo 
                 WHERE e.tag_numero = ?";
        $titulo = "Inspecciones del equipo: " . htmlspecialchars($valor);
        break;

    case 'inspecciones_fecha':
        $query = "SELECT i.* FROM inspecciones i WHERE i.fecha_inspeccion = ?";
        $titulo = "Inspecciones realizadas el: " . htmlspecialchars($valor);
        break;

    default:
        echo "<h1>Tipo de consulta no válido</h1>";
        exit;
}

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $valor);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>$titulo</h1>";

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        foreach ($row as $key => $value) {
            echo "<strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "<br>";
        }
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No se encontraron resultados</p>";
}

$stmt->close();
$conn->close();
?>