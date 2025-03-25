<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];

    $stmt = $conn->prepare("INSERT INTO fecha (fecha) VALUES (?)"); // Usamos '?' como marcador de posición
    $stmt->bind_param("s", $fecha); // "s" indica que $fecha es una cadena (string)

    if ($stmt->execute()) {
        echo "Fecha insertada correctamente.";
    } else {
        echo "Error al insertar la fecha: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Fecha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <style>
        body {
            background-color: #1a202c;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .formulario {
            background-color: #2d3748;
            padding: 2rem;
            border-radius: 0.5rem;
        }

        .formulario label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .formulario input[type="date"] {
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: 1px solid #4a5568;
            background-color: #4a5568;
            color: white;
        }

        .formulario button {
            background-color: #48bb78;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <form method="POST">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" required>
            <button type="submit">Subir</button>
        </form>
    </div>
</body>
</html>