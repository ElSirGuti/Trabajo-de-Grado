<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

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

// Consulta principal modificada para incluir datos del cliente
$sql = "SELECT 
    equipos.id_equipo,
    equipos.tag_numero, 
    equipos.tipo_equipo,
    equipos.ubicacion,
    clientes.id_cliente,
    clientes.codigo_cliente,
    clientes.nombre_cliente,
    clientes.rif_ci,
    clientes.domicilio_fiscal,
    inspecciones.id_inspeccion,
    inspecciones.temperatura_motor_1,
    inspecciones.temperatura_motor_2,
    inspecciones.temperatura_bomba_1,
    inspecciones.temperatura_bomba_2,
    inspecciones.fecha_inspeccion,
    diagnosticos.id_diagnostico,
    diagnosticos.prioridad, 
    diagnosticos.nivel_vibracion
FROM inspecciones 
JOIN equipos ON inspecciones.id_equipo = equipos.id_equipo 
JOIN clientes ON equipos.id_cliente = clientes.id_cliente
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
                    // Actualizar el badge de notificaciones periódicamente
                    function updateNotificationBadge() {
                fetch('get_notifications_count.php')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.notification-badge');
                        const notificationLink = document.querySelector('.notification-item a');
                        if (data.count > 0) {
                            if (!badge) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'notification-badge';
                                newBadge.textContent = data.count;
                                notificationLink.appendChild(newBadge);
                            } else {
                                badge.textContent = data.count;
                            }
                        } else if (badge) {
                            badge.remove();
                        }
                    });
            }

            updateNotificationBadge();
            setInterval(updateNotificationBadge, 300000);
        });
    </script>
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
            <div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-900 bg-opacity-75">
                <div class="relative w-full max-w-[600px] p-4 mx-auto my-auto"> <!-- Cambiado a max-w-5xl para más ancho -->
                    <div class="relative bg-gray-800 rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white mb-6">Detalles del Registro</h3>
                            <div id="modalContent" class="data-container text-white">
                                <!-- Aquí se cargará la información del registro -->
                            </div>
                            <div class="flex justify mt-6">
                                <button onclick="toggleViewModal()" class="px-4 py-2 bg-red-600 rounded text-white">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                * {
                    list-style: circle;
                }
                /* Estilos mejorados para el modal de visualización */
                .data-container {
                    display: grid;
                    grid-template-columns: 220px 1fr; /* Aumentado el ancho de las etiquetas */
                    gap: 12px;
                    width: 50%;
                }
                
                .data-label {
                    font-weight: 600;
                    color: #a0aec0;
                    text-align: right;
                    white-space: nowrap;
                    padding-right: 12px;
                }
                
                .data-value {
                    word-break: break-word;
                    padding-left: 8px;
                    border-left: 1px solid #4a5568;
                    color: #e2e8f0;
                }
                
                .section-title {
                    grid-column: 1 / -1;
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin-top: 16px;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #4a5568;
                    color: #ffffff;
                }
                
                .long-text {
                    white-space: pre;
                    background-color: rgba(74, 85, 104, 0.3);
                    padding: 12px;
                    border-radius: 6px;
                    border: 1px solid #4a5568;
                    grid-column: 1 / -1; /* El texto largo abarca ambas columnas */
                    margin-left: 0;
                    text-indent: 0;
                }
                
                .long-text p,
                .long-text ul,
                .long-text ol {
                    margin-left: 0;
                    padding-left: 20px; /* Sangría normal para listas */
                }
                
                .long-text li {
                    margin-bottom: 4px;
                }
                
                ul.hallazgos-list {
                    list-style-type: disc;
                    padding-left: 20px;
                    margin: 0;
                    grid-column: 1 / -1;
                }
                
                ul.hallazgos-list li {
                    margin-bottom: 4px;
                }
                
                @media (max-width: 768px) {
                    .data-container {
                        grid-template-columns: 1fr;
                        gap: 8px;
                    }
                    
                    .data-label {
                        text-align: left;
                        padding-bottom: 4px;
                        padding-right: 0;
                    }
                    
                    .data-value {
                        border-left: none;
                        padding-left: 0;
                        padding-bottom: 12px;
                        border-bottom: 1px dashed #4a5568;
                    }
                }
            </style>

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
                            <a href="?page=<?php echo $page - 1; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
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
                            <a href="?page=<?php echo $i; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
                                class="inline-flex items-center px-4 py-2 mx-1 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white <?php echo ($page == $i) ? 'current' : 'text-gray-500 bg-white'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php } ?>
                        
                        <?php if ($page < $totalPages) { ?>
                            <a href="?page=<?php echo $page + 1; ?>&tag_numero=<?php echo $tag_numero; ?>&tipo_equipo=<?php echo $tipo_equipo; ?>&prioridad=<?php echo $prioridad; ?>&nivel_vibracion=<?php echo $nivel_vibracion; ?>&fecha_inspeccion=<?php echo $fecha_inspeccion; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>"
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
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            const modalContent = document.getElementById('modalContent');

            // Función para limpiar y formatear el texto
            function formatearTexto(texto) {
                if (!texto) return 'No disponible';
                // Eliminar guiones al inicio de línea y espacios extra
                return texto.replace(/^\s*-\s*/gm, '').trim();
            }

            // Crear HTML para fallas
            let fallasHTML = '';
            if (data.fallas && data.fallas.length > 0) {
                fallasHTML = `<div class="data-value">
                    <ul class="hallazgos-list">
                        ${data.fallas.map(falla => `<li>${falla}</li>`).join('')}
                    </ul>
                </div>`;
            }

            // Crear HTML para hallazgos
            let hallazgosHTML = '';
            if (data.hallazgos && data.hallazgos.length > 0) {
                hallazgosHTML = `<div class="data-value">
                    <ul class="hallazgos-list">
                        ${data.hallazgos.map(hallazgo => `<li>${hallazgo}</li>`).join('')}
                    </ul>
                </div>`;
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
                    prioridadTexto = 'F. Fuera de Servicio';
                    break;
                default:
                    prioridadTexto = data.prioridad || 'No disponible';
            }

            modalContent.innerHTML = `
    <div class="section-title">Datos del Cliente</div>
    <div class="data-label">Código de Cliente:</div>
    <div class="data-value">${data.codigo_cliente || 'No disponible'}</div>
    <div class="data-label">Nombre:</div>
    <div class="data-value">${data.nombre_cliente || 'No disponible'}</div>
    <div class="data-label">RIF/C.I.:</div>
    <div class="data-value">${data.rif_ci || 'No disponible'}</div>
    <div class="data-label">Domicilio Fiscal:</div>
    <div class="data-value">${data.domicilio_fiscal || 'No disponible'}</div>

    <div class="section-title">Datos del Equipo</div>
    <div class="data-label">Tag Número:</div>
    <div class="data-value">${data.tag_numero || 'No disponible'}</div>
    <div class="data-label">Tipo de Equipo:</div>
    <div class="data-value">${data.tipo_equipo || 'No disponible'}</div>
    <div class="data-label">Ubicación:</div>
    <div class="data-value">${data.ubicacion || 'No disponible'}</div>

    <div class="section-title">Datos de Inspección</div>
    <div class="data-label">Fecha de Inspección:</div>
    <div class="data-value">${data.fecha_inspeccion || 'No disponible'}</div>
    <div class="data-label">Temperaturas Motor:</div>
    <div class="data-value">Punto 1: ${data.temperatura_motor_1 || 'N/A'}°C, Punto 2: ${data.temperatura_motor_2 || 'N/A'}°C</div>
    <div class="data-label">Temperaturas Bomba:</div>
    <div class="data-value">Punto 1: ${data.temperatura_bomba_1 || 'N/A'}°C, Punto 2: ${data.temperatura_bomba_2 || 'N/A'}°C</div>

    <div class="section-title">Hallazgos</div>
    ${hallazgosHTML || '<div class="data-value">No se registraron hallazgos</div>'}

    <div class="section-title">Fallas</div>
    ${fallasHTML || '<div class="data-value">No se registraron fallas</div>'}

    <div class="section-title">Diagnóstico</div>
    <div class="data-label">Prioridad:</div>
    <div class="data-value">${prioridadTexto}</div>
    <div class="data-label">Nivel de Vibración:</div>
    <div class="data-value">${data.nivel_vibracion || 'No disponible'}</div>

    <div class="section-title">Análisis</div>
    <div class="long-text">${data.analisis || 'No disponible'}</div>

    <div class="section-title">Recomendaciones</div>
    <div class="long-text">${data.recomendaciones || 'No disponible'}</div>
    `;

            toggleViewModal();
        })
        .catch(error => {
            console.error('Error al obtener el registro:', error);
            alert('Error al cargar los datos del registro');
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