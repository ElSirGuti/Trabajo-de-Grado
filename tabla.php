<?php
require_once 'conexion.php';

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filters = [];
$params = [];
$types = "";

// Filtros del modal
if (!empty($_GET['tag_numero'])) {
    $filters[] = "equipos.tag_numero LIKE ?";
    $params[] = "%" . $_GET['tag_numero'] . "%";
    $types .= "s";
}
if (!empty($_GET['tipo_equipo'])) {
    $filters[] = "inspecciones.tipo_equipo = ?";
    $params[] = $_GET['tipo_equipo'];
    $types .= "s";
}
if (!empty($_GET['orientacion'])) {
    $filters[] = "inspecciones.orientacion = ?";
    $params[] = $_GET['orientacion'];
    $types .= "s";
}
if (!empty($_GET['prioridad'])) {
    $filters[] = "diagnosticos.prioridad = ?";
    $params[] = $_GET['prioridad'];
    $types .= "i";
}
if (!empty($_GET['nivel_vibracion'])) {
    $filters[] = "diagnosticos.nivel_vibracion = ?";
    $params[] = $_GET['nivel_vibracion'];
    $types .= "s";
}
if (!empty($_GET['fecha_inspeccion'])) {
    $filters[] = "inspecciones.fecha_inspeccion = ?";
    $params[] = $_GET['fecha_inspeccion'];
    $types .= "s";
}
if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
    $filters[] = "inspecciones.fecha_inspeccion BETWEEN ? AND ?";
    $params[] = $_GET['fecha_inicio'];
    $params[] = $_GET['fecha_fin'];
    $types .= "ss";
}

$filterSql = count($filters) > 0 ? "WHERE " . implode(" AND ", $filters) : "";

// Consulta principal modificada
$sql = "SELECT 
    equipos.id_equipo,
    equipos.tag_numero, 
    equipos.hp,
    equipos.sistema,
    equipos.ubicacion,
    equipos.descripcion,
    inspecciones.id_inspeccion,
    inspecciones.tipo_equipo, 
    inspecciones.orientacion, 
    inspecciones.vibracion,
    inspecciones.en_servicio,
    inspecciones.presion_succion,
    inspecciones.presion_descarga,
    inspecciones.temperatura_operacion,
    inspecciones.fecha_inspeccion,
    diagnosticos.id_diagnostico,
    diagnosticos.prioridad, 
    diagnosticos.nivel_vibracion
FROM inspecciones 
JOIN equipos ON inspecciones.id_equipo = equipos.id_equipo 
JOIN diagnosticos ON inspecciones.id_inspeccion = diagnosticos.id_inspeccion 
$filterSql 
ORDER BY inspecciones.fecha_inspeccion DESC
LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Consulta para contar total de registros
$totalQuery = $conn->prepare("SELECT COUNT(*) as total FROM inspecciones 
JOIN equipos ON inspecciones.id_equipo = equipos.id_equipo 
JOIN diagnosticos ON inspecciones.id_inspeccion = diagnosticos.id_inspeccion 
$filterSql");

if (!empty($params)) {
    $totalQuery->bind_param($types, ...$params);
}

$totalQuery->execute();
$totalResult = $totalQuery->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

// Obtener los parámetros de filtro actuales
$tag_numero = isset($_GET['tag_numero']) ? $_GET['tag_numero'] : '';
$tipo_equipo = isset($_GET['tipo_equipo']) ? $_GET['tipo_equipo'] : '';
$orientacion = isset($_GET['orientacion']) ? $_GET['orientacion'] : '';
$prioridad = isset($_GET['prioridad']) ? $_GET['prioridad'] : '';
$nivel_vibracion = isset($_GET['nivel_vibracion']) ? $_GET['nivel_vibracion'] : '';
$fecha_inspeccion = isset($_GET['fecha_inspeccion']) ? $_GET['fecha_inspeccion'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Equipos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <link rel="stylesheet" href="estilosTabla.css">
    <script src="sidebar-loader.js"></script>
</head>

<body class="">
    <main class="main-content">
        <section class="p-3 sm:p-5">
            <!-- Modal para ver los filtros -->
            <div id="filterModal"
                class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-900 bg-opacity-75">
                <div class="relative w-full max-w-2xl p-4 mx-auto my-auto">
                    <div class="relative bg-gray-800 rounded-lg shadow dark:bg-gray-700">
                        <div class="p-6 text-center">
                            <h3 class="mb-5 text-lg font-semibold text-white">Filtrar</h3>
                            <form class="space-y-4" id="filterForm">
                                <div>
                                    <label for="tag_numero" class="block mb-2 text-sm font-medium text-white">Número de
                                        Tag:</label>
                                    <input type="text" name="tag_numero" value="<?php echo $tag_numero; ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="tipo_equipo" class="block mb-2 text-sm font-medium text-white">Tipo de
                                        Equipo:</label>
                                    <select name="tipo_equipo"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Todos</option>
                                        <option value="motor" <?php if ($tipo_equipo == 'motor')
                                            echo 'selected'; ?>>Motor
                                        </option>
                                        <option value="bomba" <?php if ($tipo_equipo == 'bomba')
                                            echo 'selected'; ?>>Bomba
                                        </option>
                                        <option value="turbina" <?php if ($tipo_equipo == 'turbina')
                                            echo 'selected'; ?>>
                                            Turbina</option>
                                        <option value="compresor" <?php if ($tipo_equipo == 'compresor')
                                            echo 'selected'; ?>>
                                            Compresor</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="orientacion"
                                        class="block mb-2 text-sm font-medium text-white">Orientación:</label>
                                    <select name="orientacion"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Todas</option>
                                        <option value="vertical" <?php if ($orientacion == 'vertical')
                                            echo 'selected'; ?>>
                                            Vertical</option>
                                        <option value="horizontal" <?php if ($orientacion == 'horizontal')
                                            echo 'selected'; ?>>Horizontal</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="prioridad"
                                        class="block mb-2 text-sm font-medium text-white">Prioridad:</label>
                                    <select name="prioridad"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Todas</option>
                                        <option value="1" <?php if ($prioridad == '1')
                                            echo 'selected'; ?>>1. Equipo en condiciones de vibración severas, debe tomarse acción inmediata</option>
                                        <option value="2" <?php if ($prioridad == '2')
                                            echo 'selected'; ?>>2. Equipo en condición de vibración crítica, debe tomarse acción planificada</option>
                                        <option value="3" <?php if ($prioridad == '3')
                                            echo 'selected'; ?>>3. Equipo bajo observación, monitorear operación y planificar mantenimiento</option>
                                        <option value="4" <?php if ($prioridad == '4')
                                            echo 'selected'; ?>>4. Condición normal de operación</option>
                                        <option value="5" <?php if ($prioridad == '5')
                                            echo 'selected'; ?>>F. Fuera de Servicio</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="nivel_vibracion" class="block mb-2 text-sm font-medium text-white">Nivel
                                        de
                                        Vibración:</label>
                                    <select name="nivel_vibracion"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Todos</option>
                                        <option value="Severo" <?php if ($nivel_vibracion == 'Severo')
                                            echo 'selected'; ?>>
                                            Severo</option>
                                        <option value="Alto" <?php if ($nivel_vibracion == 'Alto')
                                            echo 'selected'; ?>>Alto
                                        </option>
                                        <option value="Moderado" <?php if ($nivel_vibracion == 'Moderado')
                                            echo 'selected'; ?>>Moderado</option>
                                        <option value="Bajo" <?php if ($nivel_vibracion == 'Bajo')
                                            echo 'selected'; ?>>Bajo
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="fecha_inspeccion"
                                        class="block mb-2 text-sm font-medium text-white">Fecha
                                        Inspección:</label>
                                    <input type="date" name="fecha_inspeccion" value="<?php echo $fecha_inspeccion; ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-white">Rango de
                                        Fechas:</label>
                                    <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    -
                                    <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <button type="submit"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Aplicar
                                    Filtros</button>
                                <button type="button" onclick="clearFilters()"
                                    class="w-full text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Vaciar
                                    Filtros</button>
                            </form>
                            <button onclick="toggleModal()"
                                class="mt-4 px-4 py-2 bg-red-600 rounded text-white">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para ver el registro -->
            <div id="viewModal"
                class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-900 bg-opacity-75">
                <div class="relative w-full max-w-4xl p-4 mx-auto my-auto">
                    <div class="relative bg-gray-800 rounded-lg shadow dark:bg-gray-700">
                        <div class="p-6">
                            <h3 class="mb-5 text-lg font-semibold text-white">Detalles del Registro</h3>
                            <div id="modalContent" class="space-y-4 text-white">
                                <!-- Aquí se cargará la información del registro -->
                            </div>
                            <button onclick="toggleViewModal()"
                                class="mt-4 px-4 py-2 bg-red-600 rounded text-white">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="flex gap-2" style="margin-top: 10px;">
                    <button onclick="toggleModal()" class="px-4 py-2 bg-blue-600 rounded text-white">
                        Filtrar Contenido
                    </button>
                    <a href="informe.php" class="px-4 py-2 bg-green-600 rounded text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar Informe
                    </a>
                </div>
                <div class="bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden" style="margin-top: 55px;">

                    <table>
                        <thead>
                            <tr>
                                <th>TAG Numero</th>
                                <th>Tipo de Equipo</th>
                                <th>Orientacion</th>
                                <th>Prioridad</th>
                                <th>Nivel de Vibracion</th>
                                <th>Fecha de Inspeccion</th>
                                <th>Visualizar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['tag_numero']; ?></td>
                                    <td><?php echo $row['tipo_equipo']; ?></td>
                                    <td><?php echo $row['orientacion']; ?></td>
                                    <td><?php echo $row['prioridad']; ?></td>
                                    <td><?php echo $row['nivel_vibracion']; ?></td>
                                    <td><?php echo $row['fecha_inspeccion']; ?></td>
                                    <td>
                                        <button type="button" onclick="verRegistro(<?php echo $row['id_inspeccion']; ?>)"
                                            class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700">Ver</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="pagination text-center mt-4 flex justify-center items-center">
                        <?php if ($page > 1) { ?>
                            <a href="?page=<?php echo $page - 1; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&orientacion=<?php echo $orientacion; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
                                class="inline-flex items-center px-4 py-2 mx-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </a>
                        <?php } ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                            <a href="?page=<?php echo $i; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&orientacion=<?php echo $orientacion; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
                                class="inline-flex items-center px-4 py-2 mx-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white <?php echo ($page == $i) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                        <?php if ($page < $totalPages) { ?>
                            <a href="?page=<?php echo $page + 1; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&orientacion=<?php echo $orientacion; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
                                class="inline-flex items-center px-4 py-2 mx-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Next</span>
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts de Javascript -->
    <script>
        // Modal para filtrar en la tabla
        const modal = document.getElementById('filterModal');

        function toggleModal() {
            modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        }
        modal.style.display = 'none';

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        function clearFilters() {
            const form = document.getElementById('filterForm');
            form.reset();
            window.location.href = window.location.pathname;
        }

        // Modal para Ver Registro
        const viewModal = document.getElementById('viewModal');

        function toggleViewModal() {
            viewModal.style.display = viewModal.style.display === 'block' ? 'none' : 'block';
        }

        function verRegistro(id_inspeccion) {
            fetch(`obtener_registro.php?id_inspeccion=${id_inspeccion}`)
                .then(response => {
                    // Verificar si la respuesta es JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            throw new Error(`Respuesta no es JSON: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const modalContent = document.getElementById('modalContent');

                    // Función para manejar valores null
                    function manejarNull(valor, mensajeAlternativo = 'No disponible') {
                        return valor === null || valor === undefined ? mensajeAlternativo : valor;
                    }

                    // Crear HTML para fallas
                    let fallasHTML = '';
                    if (data.fallas && data.fallas.length > 0) {
                        fallasHTML = `<div class="info"><strong>Falla(s):</strong> ${data.fallas.join(', ')}</div>`;
                    }

                    // Crear HTML para hallazgos
                    let hallazgosHTML = '';
                    if (data.hallazgos && data.hallazgos.length > 0) {
                        hallazgosHTML = `<div class="info"><strong>Hallazgo(s):</strong> ${data.hallazgos.join(', ')}</div>`;
                    }

                    let prioridadTexto = '';
                    switch (data.prioridad) {
                        case 1:
                            prioridadTexto = '1. Equipo en condiciones de vibración severas, debe tomarse acción inmediata';
                            break;
                        case 2:
                            prioridadTexto = '2. Equipo en condición de vibración crítica, debe tomarse acción planificada';
                            break;
                        case 3:
                            prioridadTexto = '3. Equipo bajo observación, monitorear operación y planificar mantenimiento';
                            break;
                        case 4:
                            prioridadTexto = '4. Condición normal de operación';
                            break;
                        case 5:
                            prioridadTexto = '5. F. Fuera de Servicio';
                            break;
                        default:
                            prioridadTexto = manejarNull(data.prioridad);
                    }

                    modalContent.innerHTML = `
                <div class="modal-sections">
                    <div class="modal-section datos-equipo">
                        <h3>Datos del Equipo</h3><br>
                        <div class="info"><strong>Tag Número:</strong> ${manejarNull(data.tag_numero)}</div><br>
                        <div class="info"><strong>HP:</strong> ${manejarNull(data.hp)}</div><br>
                        <div class="info"><strong>Sistema:</strong> ${manejarNull(data.sistema)}</div><br>
                        <div class="info"><strong>Ubicación:</strong> ${manejarNull(data.ubicacion)}</div><br>
                        <div class="info"><strong>Descripción:</strong> ${manejarNull(data.descripcion)}</div><br>
                        <div class="info"><strong>Tipo de Equipo:</strong> ${manejarNull(data.tipo_equipo)}</div><br>
                        <div class="info"><strong>Orientación:</strong> ${manejarNull(data.orientacion)}</div><br>
                        <div class="info"><strong>Fecha Inspección:</strong> ${manejarNull(data.fecha_inspeccion)}</div><br>
                    </div>

                    <div class="modal-section condiciones-operacion">
                        <h3>Condiciones de Operación</h3><br>
                        <div class="info"><strong>¿Fue posible tomar mediciones de vibración?</strong> ${manejarNull(data.vibracion)}</div><br>
                        <div class="info"><strong>¿El equipo estaba en servicio durante la inspección?</strong> ${manejarNull(data.en_servicio)}</div><br>
                        <div class="info"><strong>¿Fue posible tomar medidas de presión de succión?</strong> ${manejarNull(data.presion_succion)}</div><br>
                        <div class="info"><strong>¿Fue posible tomar medidas de presión de descarga?</strong> ${manejarNull(data.presion_descarga)}</div><br>
                        <div class="info"><strong>Temperatura Operación (Fahrenheit):</strong> ${manejarNull(data.temperatura_operacion)}</div><br>
                    </div>
                </div>

                <div class="modal-section hallazgos-diagnostico">
                    <h3>Hallazgos, Fallas y Diagnóstico</h3><br>
                    ${hallazgosHTML}<br>
                    ${fallasHTML}<br>
                    <div class="info"><strong>Prioridad:</strong> ${prioridadTexto}</div><br>
                    <div class="info"><strong>Nivel de Vibración:</strong> ${manejarNull(data.nivel_vibracion)}</div><br>
                    <div class="info"><strong>Análisis:</strong> ${manejarNull(data.analisis)}</div><br>
                    <div class="info"><strong>Recomendaciones:</strong> ${manejarNull(data.recomendaciones)}</div><br>
                </div>
            `;

                    toggleViewModal();
                })
                .catch(error => {
                    console.error('Error al obtener el registro:', error);
                    alert('Error al cargar los datos del registro: ' + error.message);
                });
        }

        window.addEventListener('click', function (event) {
            if (event.target === viewModal) {
                toggleViewModal();
            }
        });
    </script>

    <!-- CDN Flowbite 1.5.3 -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.js"></script>
</body>

</html>
<?php
$stmt->close();
$totalQuery->close();
$conn->close();
?>