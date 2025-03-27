<?php
require_once 'conexion.php';

if (isset($_GET['tipo'])) {
    $tipo_equipo = $_GET['tipo'];
    $query = "SELECT * FROM equipos WHERE tipo_equipo = '$tipo_equipo'";
    $result = $conn->query($query);
    echo "<h1>Equipos de tipo: $tipo_equipo</h1>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['tag_numero'] . " - " . $row['ubicacion'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Tipo de equipo no especificado.";
}
?>