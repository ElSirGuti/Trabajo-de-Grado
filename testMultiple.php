<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Cambiar el nombre de la variable a $conn
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'agregar_hallazgo') {
        $nombre = $_POST['nombre'];
        $nombre = $conn->real_escape_string($nombre);

        // Insertar el nuevo hallazgo
        $sql = "INSERT INTO lista_hallazgos (hallazgo) VALUES ('$nombre')";
        if ($conn->query($sql)) {
            $id_generado = $conn->insert_id;
            echo json_encode([
                'success' => true,
                'id' => $id_generado,
                'nombre' => $nombre
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $conn->error
            ]);
        }
        exit();
    }

    if ($action === 'agregar_falla') {
        $nombre = $_POST['nombre'];
        $nombre = $conn->real_escape_string($nombre);

        // Insertar la nueva falla
        $sql = "INSERT INTO lista_fallas (falla) VALUES ('$nombre')";
        if ($conn->query($sql)) {
            $id_generado = $conn->insert_id;
            echo json_encode([
                'success' => true,
                'id' => $id_generado,
                'nombre' => $nombre
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $conn->error
            ]);
        }
        exit();
    }

    if ($action === 'guardar_inspeccion') {
        $id_inspeccion = 13; // Puedes cambiar esto según tu lógica
        $hallazgos_seleccionados = $_POST['hallazgos'] ?? [];
        $fallas_seleccionadas = $_POST['fallas'] ?? [];

        // Insertar en la tabla intermedia inspeccion_hallazgos
        foreach ($hallazgos_seleccionados as $id_hallazgo) {
            $id_hallazgo = intval($id_hallazgo);
            $sql = "INSERT INTO inspeccion_hallazgos (id_inspeccion, id_hallazgo) VALUES ($id_inspeccion, $id_hallazgo)";
            if (!$conn->query($sql)) {
                echo json_encode([
                    'success' => false,
                    'error' => $conn->error
                ]);
                exit();
            }
        }

        // Insertar en la tabla intermedia inspeccion_fallas
        foreach ($fallas_seleccionadas as $id_falla) {
            $id_falla = intval($id_falla);
            $sql = "INSERT INTO inspeccion_fallas (id_inspeccion, id_falla) VALUES ($id_inspeccion, $id_falla)";
            if (!$conn->query($sql)) {
                echo json_encode([
                    'success' => false,
                    'error' => $conn->error
                ]);
                exit();
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Datos guardados correctamente.'
        ]);
        exit();
    }
}

// Obtener los hallazgos de la base de datos
$hallazgos = obtenerHallazgos($conn);

// Obtener las fallas de la base de datos
$fallas = obtenerFallas($conn);

// Funciones para obtener los datos de la base de datos
function obtenerHallazgos($conn) {
    $sql = "SELECT id_hallazgo, hallazgo FROM lista_hallazgos";
    $result = $conn->query($sql);
    $hallazgos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hallazgos[] = $row;
        }
    }
    return $hallazgos;
}

function obtenerFallas($conn) {
    $sql = "SELECT id_falla, falla FROM lista_fallas";
    $result = $conn->query($sql);
    $fallas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fallas[] = $row;
        }
    }
    return $fallas;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Inspección</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Usamos jQuery para simplificar AJAX -->
</head>
<body>
    <h1>Formulario de Inspección</h1>
    <form id="formulario">
        <div>
            <label for="hallazgos">Hallazgos:</label>
            <select name="hallazgos[]" id="hallazgos" multiple>
                <?php foreach ($hallazgos as $hallazgo): ?>
                    <option value="<?php echo $hallazgo['id_hallazgo']; ?>"><?php echo $hallazgo['hallazgo']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="nuevo_hallazgo" placeholder="Nuevo hallazgo">
            <button type="button" onclick="agregarHallazgo()">Agregar</button>
        </div>

        <div>
            <label for="fallas">Fallas:</label>
            <select name="fallas[]" id="fallas" multiple>
                <?php foreach ($fallas as $falla): ?>
                    <option value="<?php echo $falla['id_falla']; ?>"><?php echo $falla['falla']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="nueva_falla" placeholder="Nueva falla">
            <button type="button" onclick="agregarFalla()">Agregar</button>
        </div>

        <button type="button" onclick="guardarInspeccion()">Guardar</button>
    </form>

    <script>
        // Función para agregar un nuevo hallazgo
        function agregarHallazgo() {
            var nuevoHallazgo = document.getElementById('nuevo_hallazgo').value;
            if (nuevoHallazgo.trim() === "") {
                alert("Por favor, ingresa un hallazgo válido.");
                return;
            }

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: 'testMultiple.php', // Mismo archivo
                method: 'POST',
                data: {
                    action: 'agregar_hallazgo',
                    nombre: nuevoHallazgo
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Agregar el nuevo hallazgo al <select>
                        var selectHallazgos = document.getElementById('hallazgos');
                        var option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.nombre;
                        selectHallazgos.add(option);

                        // Limpiar el input
                        document.getElementById('nuevo_hallazgo').value = '';
                    } else {
                        alert("Error al agregar el hallazgo: " + data.error);
                    }
                },
                error: function() {
                    alert("Error en la solicitud AJAX.");
                }
            });
        }

        // Función para agregar una nueva falla
        function agregarFalla() {
            var nuevaFalla = document.getElementById('nueva_falla').value;
            if (nuevaFalla.trim() === "") {
                alert("Por favor, ingresa una falla válida.");
                return;
            }

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: 'testMultiple.php', // Mismo archivo
                method: 'POST',
                data: {
                    action: 'agregar_falla',
                    nombre: nuevaFalla
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Agregar la nueva falla al <select>
                        var selectFallas = document.getElementById('fallas');
                        var option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.nombre;
                        selectFallas.add(option);

                        // Limpiar el input
                        document.getElementById('nueva_falla').value = '';
                    } else {
                        alert("Error al agregar la falla: " + data.error);
                    }
                },
                error: function() {
                    alert("Error en la solicitud AJAX.");
                }
            });
        }

        // Función para guardar la inspección
        function guardarInspeccion() {
            var hallazgosSeleccionados = $('#hallazgos').val() || [];
            var fallasSeleccionadas = $('#fallas').val() || [];

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: 'testMultiple.php', // Mismo archivo
                method: 'POST',
                data: {
                    action: 'guardar_inspeccion',
                    hallazgos: hallazgosSeleccionados,
                    fallas: fallasSeleccionadas
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert("Error al guardar la inspección: " + data.error);
                    }
                },
                error: function() {
                    alert("Error en la solicitud AJAX.");
                }
            });
        }
    </script>
</body>
</html>