<?php
require_once 'conexion.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Primero manejar el cliente
    $codigo_cliente = $_POST['codigo_cliente'];

    // Verificar si el cliente ya existe
    $stmt_cliente = $conn->prepare("SELECT id_cliente FROM clientes WHERE codigo_cliente = ?");
    $stmt_cliente->bind_param("s", $codigo_cliente);
    $stmt_cliente->execute();
    $result_cliente = $stmt_cliente->get_result();

    if ($result_cliente->num_rows > 0) {
        $cliente = $result_cliente->fetch_assoc();
        $id_cliente = $cliente['id_cliente'];
    } else {
        // Insertar nuevo cliente
        $stmt_new_cliente = $conn->prepare("INSERT INTO clientes (codigo_cliente, nombre_cliente, rif_ci, domicilio_fiscal) VALUES (?, ?, ?, ?)");
        $stmt_new_cliente->bind_param("ssss", $_POST['codigo_cliente'], $_POST['nombre_cliente'], $_POST['rif_ci'], $_POST['domicilio_fiscal']);
        $stmt_new_cliente->execute();
        $id_cliente = $conn->insert_id;
        $stmt_new_cliente->close();
    }

    // Manejo del equipo
    $tag_numero = $_POST['tag_numero'];

    // Verificar si el equipo ya existe
    $stmt_check = $conn->prepare("SELECT id_equipo FROM equipos WHERE tag_numero = ?");
    $stmt_check->bind_param("s", $tag_numero);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $equipo = $result_check->fetch_assoc();
        $id_equipo = $equipo['id_equipo'];
    } else {
        // Insertar el equipo
        $stmt_equipo = $conn->prepare("INSERT INTO equipos (tag_numero, tipo_equipo, ubicacion, id_cliente) VALUES (?, ?, ?, ?)");
        $stmt_equipo->bind_param("sssi", $_POST['tag_numero'], $_POST['tipo_equipo'], $_POST['ubicacion'], $id_cliente);
        $stmt_equipo->execute();
        $id_equipo = $conn->insert_id;
        $stmt_equipo->close();
    }

    // Insertar inspección y demás datos usando $id_equipo
    try {
        // Tabla inspecciones
        $temperatura_motor_1 = $_POST['temperatura_motor_1'];
        $temperatura_motor_2 = $_POST['temperatura_motor_2'];
        $temperatura_bomba_1 = $_POST['temperatura_bomba_1'];
        $temperatura_bomba_2 = $_POST['temperatura_bomba_2'];
        $fecha_inspeccion = $_POST['fecha_inspeccion'];

        $stmt_inspeccion = $conn->prepare("INSERT INTO inspecciones (id_equipo, temperatura_motor_1, temperatura_motor_2, temperatura_bomba_1, temperatura_bomba_2, fecha_inspeccion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_inspeccion->bind_param("idddds", $id_equipo, $temperatura_motor_1, $temperatura_motor_2, $temperatura_bomba_1, $temperatura_bomba_2, $fecha_inspeccion);
        $stmt_inspeccion->execute();
        $id_inspeccion = $conn->insert_id;
        $stmt_inspeccion->close();

        // Tabla inspeccion_fallas
        $tipo_falla = isset($_POST['tipo_falla']) ? $_POST['tipo_falla'] : [];
        foreach ($tipo_falla as $id_falla) {
            $stmt_falla = $conn->prepare("INSERT INTO inspeccion_fallas (id_inspeccion, id_falla) VALUES (?, ?)");
            $stmt_falla->bind_param("ii", $id_inspeccion, $id_falla);
            $stmt_falla->execute();
            $stmt_falla->close();
        }

        // Tabla inspeccion_hallazgos
        $hallazgos = isset($_POST['hallazgos']) ? $_POST['hallazgos'] : [];
        foreach ($hallazgos as $id_hallazgo) {
            $stmt_hallazgo = $conn->prepare("INSERT INTO inspeccion_hallazgos (id_inspeccion, id_hallazgo) VALUES (?, ?)");
            $stmt_hallazgo->bind_param("ii", $id_inspeccion, $id_hallazgo);
            $stmt_hallazgo->execute();
            $stmt_hallazgo->close();
        }

        // Tabla diagnosticos
        $prioridad = $_POST['prioridad'];
        $nivel_vibracion = $_POST['nivel_vibracion'];
        $stmt_diagnostico = $conn->prepare("INSERT INTO diagnosticos (id_inspeccion, prioridad, nivel_vibracion) VALUES (?, ?, ?)");
        $stmt_diagnostico->bind_param("iis", $id_inspeccion, $prioridad, $nivel_vibracion);
        $stmt_diagnostico->execute();
        $id_diagnostico = $conn->insert_id;
        $stmt_diagnostico->close();

        // Tabla analisis
        $analisis = $_POST['analisis'];
        $recomendaciones = $_POST['recomendaciones'];
        $stmt_analisis = $conn->prepare("INSERT INTO analisis (id_diagnostico, analisis, recomendaciones) VALUES (?, ?, ?)");
        $stmt_analisis->bind_param("iss", $id_diagnostico, $analisis, $recomendaciones);
        $stmt_analisis->execute();
        $stmt_analisis->close();

        $message = "Informe guardado con éxito.";
    } catch (mysqli_sql_exception $e) {
        $message = "Error al guardar el informe: " . $e->getMessage();
    }
}

// Obtener lista de fallas
$stmt_lista_fallas = $conn->prepare("SELECT id_falla, falla FROM lista_fallas");
$stmt_lista_fallas->execute();
$result_lista_fallas = $stmt_lista_fallas->get_result();
$lista_fallas = $result_lista_fallas->fetch_all(MYSQLI_ASSOC);
$stmt_lista_fallas->close();

// Obtener lista de hallazgos
$stmt_lista_hallazgos = $conn->prepare("SELECT id_hallazgo, hallazgo FROM lista_hallazgos");
$stmt_lista_hallazgos->execute();
$result_lista_hallazgos = $stmt_lista_hallazgos->get_result();
$lista_hallazgos = $result_lista_hallazgos->fetch_all(MYSQLI_ASSOC);
$stmt_lista_hallazgos->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Inspección</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <link rel="stylesheet" href="estilosInforme.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold mb-4">Informe de Inspección</h1>

        <?php if ($message): ?>
            <p
                class="mb-4 p-3 <?php echo strpos($message, 'éxito') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form id="miFormulario" method="post">
            <div class="progress-numbers">
                <div class="progress-line" id="progressLine"></div>
                <div class="progress-number-container">
                    <div class="progress-number active" id="number1" onclick="goToStep(1)">1</div>
                    <div class="progress-step-text">Cliente</div>
                </div>
                <div class="progress-number-container">
                    <div class="progress-number" id="number2" onclick="goToStep(2)">2</div>
                    <div class="progress-step-text">Equipo</div>
                </div>
                <div class="progress-number-container">
                    <div class="progress-number" id="number3" onclick="goToStep(3)">3</div>
                    <div class="progress-step-text">Inspección</div>
                </div>
                <div class="progress-number-container">
                    <div class="progress-number" id="number4" onclick="goToStep(4)">4</div>
                    <div class="progress-step-text">Fallas</div>
                </div>
                <div class="progress-number-container">
                    <div class="progress-number" id="number5" onclick="goToStep(5)">5</div>
                    <div class="progress-step-text">Diagnóstico</div>
                </div>
                <div class="progress-number-container">
                    <div class="progress-number" id="number6" onclick="goToStep(6)">6</div>
                    <div class="progress-step-text">Análisis</div>
                </div>
            </div>

            <div class="form-step" id="step1">
                <h2 class="text-lg font-medium mb-2">Datos del Cliente</h2>

                <div class="mb-4">
                    <label for="codigo_cliente" class="block text-sm font-medium text-gray-700">Código de
                        Cliente:</label>
                    <input type="text" name="codigo_cliente" id="codigo_cliente" required
                        class="mt-1 p-2 w-full border rounded-md" oninput="buscarCliente(this.value)">
                </div>

                <div class="mb-4">
                    <label for="nombre_cliente" class="block text-sm font-medium text-gray-700">Nombre del
                        Cliente:</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" required
                        class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="rif_ci" class="block text-sm font-medium text-gray-700">RIF/C.I.:</label>
                    <div class="flex">
                        <!-- Dropdown con ancho fijo usando w-16 (equivalente a 4rem) -->
                        <select name="tipo_documento" id="tipo_documento" 
                            class="mt-1 p-2 w-20 border rounded-l-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                            <option value="V">V</option>
                            <option value="E">E</option>
                            <option value="J">J</option>
                        </select>
                        <!-- Campo para el número -->
                        <input type="text" name="rif_ci" id="rif_ci" required 
                            class="mt-1 p-2 w-full border rounded-r-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Número de documento">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="domicilio_fiscal" class="block text-sm font-medium text-gray-700">Domicilio
                        Fiscal:</label>
                    <textarea name="domicilio_fiscal" id="domicilio_fiscal" required rows="3"
                        class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>

                <div class="flex justify-between mt-4">
                    <a href="tabla.php"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
                    <button type="button" onclick="nextStep(2)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>
                </div>
            </div>

            <div class="form-step hidden" id="step2">
                <h2 class="text-lg font-medium mb-2">Equipo</h2>
                <div class="mb-4">
                    <label for="tag_numero" class="block text-sm font-medium text-gray-700">Tag Número:</label>
                    <input type="text" name="tag_numero" id="tag_numero" required
                        class="mt-1 p-2 w-full border rounded-md" oninput="buscarEquipo(this.value)">
                </div>

                <div class="mb-4">
                    <label for="tipo_equipo" class="block text-sm font-medium text-gray-700">Tipo de Equipo:</label>
                    <select name="tipo_equipo" required class="mt-1 p-2 w-full border rounded-md">
                        <option value="Motor">Motor</option>
                        <option value="Bomba">Bomba</option>
                        <option value="Compresor">Compresor</option>
                        <option value="Turbina">Turbina</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación:</label>
                    <input type="text" name="ubicacion" id="ubicacion" class="mt-1 p-2 w-full border rounded-md">
                </div>

                <button type="button" onclick="goToStep(1)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Anterior</button>
                <button type="button" onclick="nextStep(3)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>
            </div>

            <div class="form-step hidden" id="step3">
                <h2 class="text-lg font-medium mb-2">Inspección</h2>

                <div class="mb-4">
                    <label for="temperatura_motor_1" class="block text-sm font-medium text-gray-700">Temperatura de
                        Operación: Motor, Punto 1 (Centígrados):</label>
                    <input type="number" name="temperatura_motor_1" class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="temperatura_motor_2" class="block text-sm font-medium text-gray-700">Temperatura de
                        Operación: Motor, Punto 2 (Centígrados):</label>
                    <input type="number" name="temperatura_motor_2" class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="temperatura_bomba_1" class="block text-sm font-medium text-gray-700">Temperatura de
                        Operación: Bomba, Punto 1 (Centígrados):</label>
                    <input type="number" name="temperatura_bomba_1" class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="temperatura_bomba_2" class="block text-sm font-medium text-gray-700">Temperatura de
                        Operación: Bomba, Punto 2 (Centígrados):</label>
                    <input type="number" name="temperatura_bomba_2" class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="fecha_inspeccion" class="block text-sm font-medium text-gray-700">Fecha de la
                        Inspección:</label>
                    <div class="relative w-full">
                        <input id="fecha_inspeccion" type="date" name="fecha_inspeccion" required
                            class="w-full border rounded-md" onfocus="this.max=new Date().toISOString().split('T')[0]">
                    </div>
                </div>

                <button type="button" onclick="goToStep(2)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Anterior</button>
                <button type="button" onclick="nextStep(4)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>
            </div>

            <div class="form-step hidden" id="step4">
                <h2 class="text-lg font-medium mb-2">Hallazgos</h2>
                <div class="mb-4">
                    <select name="hallazgos[]" id="hallazgos" multiple class="w-full border rounded-md p-2">
                        <?php foreach ($lista_hallazgos as $hallazgo): ?>
                            <option value="<?php echo $hallazgo['id_hallazgo']; ?>"><?php echo $hallazgo['hallazgo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="flex mt-2">
                        <input type="text" id="nuevo_hallazgo" placeholder="Nuevo hallazgo"
                            class="w-full border rounded-md p-2">
                        <button type="button" onclick="agregarHallazgo()"
                            class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                    </div>
                </div>
                <h2 class="text-lg font-medium mb-2">Fallas</h2>
                <div class="mb-4">
                    <select name="tipo_falla[]" id="fallas" multiple class="w-full border rounded-md p-2">
                        <?php foreach ($lista_fallas as $falla): ?>
                            <option value="<?php echo $falla['id_falla']; ?>"><?php echo $falla['falla']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="flex mt-2">
                        <input type="text" id="nueva_falla" placeholder="Nueva falla"
                            class="w-full border rounded-md p-2">
                        <button type="button" onclick="agregarFalla()"
                            class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                    </div>
                </div>
                <button type="button" onclick="goToStep(3)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Anterior</button>
                <button type="button" onclick="nextStep(5)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>
            </div>

            <div class="form-step hidden" id="step5">
                <h2 class="text-lg font-medium mb-2">Diagnóstico</h2>
                <div class="mb-4">
                    <label for="prioridad" class="block text-sm font-medium text-gray-700">Prioridad:</label>
                    <select name="prioridad" required class="mt-1 p-2 w-full border rounded-md">
                        <option value="1">1. Equipo en condiciones de vibración severas, debe tomarse acción inmediata
                        </option>
                        <option value="2">2. Equipo en condición de vibración crítica, debe tomarse acción planificada
                        </option>
                        <option value="3">3. Equipo bajo observación, monitorear operación y planificar mantenimiento
                        </option>
                        <option value="4">4. Condición normal de operación</option>
                        <option value="5">F. Fuera de Servicio</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nivel_vibracion" class="block text-sm font-medium text-gray-700">Nivel de Vibración
                        Encontrado:</label>
                    <select name="nivel_vibracion" required class="mt-1 p-2 w-full border rounded-md">
                        <option value="Severo">Severo</option>
                        <option value="Alto">Alto</option>
                        <option value="Moderado">Moderado</option>
                        <option value="Bajo">Bajo</option>
                    </select>
                </div>
                <button type="button" onclick="goToStep(4)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Anterior</button>
                <button type="button" onclick="nextStep(6)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>
            </div>

            <div class="form-step hidden" id="step6">
                <h2 class="text-lg font-medium mb-2">Análisis y Recomendaciones</h2>
                <div class="mb-4">
                    <label for="analisis" class="block text-sm font-medium text-gray-700">Análisis:</label>
                    <textarea name="analisis" class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>

                <div class="mb-4">
                    <label for="recomendaciones"
                        class="block text-sm font-medium text-gray-700">Recomendaciones:</label>
                    <textarea name="recomendaciones" class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>

                <button type="button" onclick="goToStep(5)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Anterior</button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar
                    Informe</button>
            </div>
        </form>
    </div>
    <script>
        let currentStep = 1;
        let previousStep = 0; // Para almacenar el paso anterior

        function nextStep(step) {
            const currentStepElement = document.getElementById("step" + currentStep);
            const nextStepElement = document.getElementById("step" + step);

            // Clase para la transición de salida (hacia la izquierda)
            currentStepElement.classList.add("previous");

            // Preparar el siguiente paso para la entrada (desde la derecha)
            nextStepElement.classList.remove("hidden");
            nextStepElement.classList.add("next"); // Nueva clase para la transición hacia adelante

            // Actualizar los números del progreso
            document.getElementById("number" + currentStep).classList.remove("active");
            document.getElementById("number" + step).classList.add("active");

            // Almacenar el paso actual antes de cambiarlo
            previousStep = currentStep;
            currentStep = step; // Actualizar currentStep

            updateProgressLine(step); // Pasar el número de paso a la función

            // Usar un setTimeout para controlar la secuencia de las transiciones
            setTimeout(() => {
                currentStepElement.classList.add("hidden"); // Ocultar el paso anterior
                nextStepElement.classList.remove("next"); // Remover la clase después de la transición
            }, 500); // La duración debe coincidir con la transición CSS
        }

        function goToStep(step) {
            if (step !== currentStep) {
                // Determinar la dirección de la transición
                if (step > currentStep) {
                    nextStep(step); // Si es hacia adelante, usar nextStep
                } else {
                    // Transición para retroceder
                    const currentStepElement = document.getElementById("step" + currentStep);
                    const prevStepElement = document.getElementById("step" + step);

                    currentStepElement.classList.add("hidden"); // Ocultar el paso actual inmediatamente
                    currentStepElement.classList.remove("previous"); // Remover la clase 'previous' para evitar conflictos

                    prevStepElement.classList.remove("hidden"); // Mostrar el paso anterior
                    prevStepElement.classList.add("previous"); // Agregar la clase 'previous' para la transición de retroceso

                    document.getElementById("number" + currentStep).classList.remove("active");
                    document.getElementById("number" + step).classList.add("active");

                    currentStep = step;
                    updateProgressLine(step);

                    setTimeout(() => {
                        prevStepElement.classList.remove("previous"); // Remover la clase después de la transición
                    }, 100);
                }
            }
        }


        function updateProgressLine(step) { // Ahora acepta el número de paso
            const progressLine = document.getElementById('progressLine');
            progressLine.classList.add('active');
            let progressWidth = 0;

            // Calcular el ancho basado en el paso actual
            switch (step) {
                case 1: progressWidth = 0; console.log(progressWidth); break;
                case 2: progressWidth = 20; console.log(progressWidth); break;
                case 3: progressWidth = 35; console.log(progressWidth); break;
                case 4: progressWidth = 55; console.log(progressWidth); break;
                case 5: progressWidth = 75; console.log(progressWidth); break;
                case 6: progressWidth = 95; console.log(progressWidth); break;
            }
            progressLine.style.width = progressWidth + '%';
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Tab') {
                event.preventDefault();
                let inputs = document.querySelectorAll('#step' + currentStep + ' input');
                let currentIndex = Array.prototype.indexOf.call(inputs, document.activeElement);
                if (event.shiftKey) {
                    if (currentIndex > 0) {
                        inputs[currentIndex - 1].focus();
                    } else {
                        if (currentStep > 1) {
                            goToStep(currentStep - 1); // Usar goToStep para retroceder
                            let prevInputs = document.querySelectorAll('#step' + currentStep + ' input');
                            prevInputs[prevInputs.length - 1].focus();
                        }
                    }
                } else {
                    if (currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    } else {
                        if (currentStep < 5) {
                            nextStep(currentStep + 1);
                            document.querySelector('#step' + (currentStep) + ' input').focus();
                        } else {
                            inputs[0].focus();
                        }
                    }
                }
            }
        });

        updateProgressLine(currentStep);

        document.getElementById('miFormulario').addEventListener('submit', function (event) {
            // Combinar tipo de documento y número antes de enviar
            const tipoDocumento = document.getElementById('tipo_documento').value;
            const numeroDocumento = document.getElementById('rif_ci').value;
            document.getElementById('rif_ci').value = tipoDocumento + '-' + numeroDocumento;
            
            let camposRequeridos = document.querySelectorAll('[required]');
            let formularioValido = true;

            camposRequeridos.forEach(campo => {
                if (!campo.value) {
                    formularioValido = false;
                }
            });

            if (!formularioValido) {
                event.preventDefault(); // Evita que el formulario se envíe
                alert('Por favor, complete todos los campos requeridos.');
            }
        });

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
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Agregar el nuevo hallazgo al <select>
                        var selectHallazgos = document.getElementById('hallazgos');
                        var option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.nombre;
                        selectHallazgos.add(option);

                        // Agregar el nuevo hallazgo a la lista de checkboxes
                        var contenedorHallazgos = document.querySelector('#step3 .space-y-2:first-child');
                        var nuevoLabel = document.createElement('label');
                        var nuevoCheckbox = document.createElement('input');
                        nuevoCheckbox.type = 'checkbox';
                        nuevoCheckbox.name = 'hallazgos[]';
                        nuevoCheckbox.value = data.id;
                        nuevoCheckbox.classList.add('mr-2');
                        nuevoLabel.appendChild(nuevoCheckbox);
                        nuevoLabel.appendChild(document.createTextNode(data.nombre));
                        contenedorHallazgos.appendChild(nuevoLabel);
                        contenedorHallazgos.appendChild(document.createElement('br'));

                        // Limpiar el input
                        document.getElementById('nuevo_hallazgo').value = '';
                    } else {
                        alert("Error al agregar el hallazgo: " + data.error);
                    }
                },
                error: function () {
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
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Agregar la nueva falla al <select>
                        var selectFallas = document.getElementById('fallas');
                        var option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.nombre;
                        selectFallas.add(option);

                        // Agregar la nueva falla a la lista de checkboxes
                        var contenedorFallas = document.querySelector('#step3 .space-y-2:last-child');
                        var nuevoLabel = document.createElement('label');
                        var nuevoCheckbox = document.createElement('input');
                        nuevoCheckbox.type = 'checkbox';
                        nuevoCheckbox.name = 'tipo_falla[]';
                        nuevoCheckbox.value = data.id;
                        nuevoCheckbox.classList.add('mr-2');
                        nuevoLabel.appendChild(nuevoCheckbox);
                        nuevoLabel.appendChild(document.createTextNode(data.nombre));
                        contenedorFallas.appendChild(nuevoLabel);
                        contenedorFallas.appendChild(document.createElement('br'));

                        // Limpiar el input
                        document.getElementById('nueva_falla').value = '';
                    } else {
                        alert("Error al agregar la falla: " + data.error);
                    }
                },
                error: function () {
                    alert("Error en la solicitud AJAX.");
                }
            });
        }

        function buscarEquipo(tagNumero) {
            if (tagNumero.length > 0) {
                $.ajax({
                    url: 'buscar_equipo.php',
                    method: 'POST',
                    data: { tag_numero: tagNumero },
                    success: function (response) {
                        if (response) {
                            let equipo = JSON.parse(response);
                            $('#tipo_equipo').val(equipo.tipo_equipo);
                            $('#ubicacion').val(equipo.ubicacion);
                        } else {
                            $('#tipo_equipo').val('');
                            $('#ubicacion').val('');
                        }
                    }
                });
            }
        }

        function buscarCliente(codigoCliente) {
            if (codigoCliente.length > 0) {
                $.ajax({
                    url: 'buscar_cliente.php',
                    method: 'POST',
                    data: { codigo_cliente: codigoCliente },
                    success: function(response) {
                        if (response) {
                            let cliente = JSON.parse(response);
                            $('#nombre_cliente').val(cliente.nombre_cliente);
                            
                            // Separar el RIF/CI en tipo y número
                            if (cliente.rif_ci && cliente.rif_ci.includes('-')) {
                                const [tipo, numero] = cliente.rif_ci.split('-');
                                $('#tipo_documento').val(tipo);
                                $('#rif_ci').val(numero);
                            } else {
                                $('#tipo_documento').val('V');
                                $('#rif_ci').val(cliente.rif_ci || '');
                            }
                            
                            $('#domicilio_fiscal').val(cliente.domicilio_fiscal);
                        } else {
                            // Limpiar campos si no se encuentra
                            $('#nombre_cliente').val('');
                            $('#tipo_documento').val('V');
                            $('#rif_ci').val('');
                            $('#domicilio_fiscal').val('');
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>