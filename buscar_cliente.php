<?php
require_once 'conexion.php';

$codigo_cliente = $_POST['codigo_cliente'];

$stmt = $conn->prepare("SELECT * FROM clientes WHERE codigo_cliente = ?");
$stmt->bind_param("s", $codigo_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(false);
}
?>