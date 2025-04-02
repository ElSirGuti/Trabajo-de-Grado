<?php
require 'conexion.php';

$query = "SELECT COUNT(*) as count FROM eventos WHERE fecha >= CURDATE()";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode(['count' => $row['count']]);

mysqli_close($conn);
?>