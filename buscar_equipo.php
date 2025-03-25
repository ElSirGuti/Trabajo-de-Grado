<?php
require_once 'conexion.php';

if (isset($_POST['tag_numero'])) {
    $tag_numero = $_POST['tag_numero'];

    $stmt = $conn->prepare("SELECT hp, sistema, ubicacion, descripcion FROM equipos WHERE tag_numero = ?");
    $stmt->bind_param("s", $tag_numero);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $equipo = $result->fetch_assoc();
        echo json_encode($equipo);
    } else {
        echo ''; // Devuelve una cadena vacía si no se encuentra el equipo
    }

    $stmt->close();
}
?>