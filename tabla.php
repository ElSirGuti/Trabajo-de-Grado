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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados con la nueva paleta */
        body {
            background-color: #F9FAFB;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .table-container {
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: #F3F4F6;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid #E5E7EB;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #4B5563;
        }

        tr:hover td {
            background-color: #F9FAFB;
        }

        .btn-primary {
            background-color: #2563EB;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #1D4ED8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6B7280;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #4B5563;
        }

        .btn-danger {
            background-color: #EF4444;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #DC2626;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #E5E7EB;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
        }

        .data-container {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 12px;
        }

        .data-label {
            font-weight: 500;
            color: #6B7280;
            text-align: right;
        }

        .data-value {
            color: #374151;
            word-break: break-word;
        }

        .section-title {
            grid-column: 1 / -1;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
            color: #111827;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .page-item {
            margin: 0 4px;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 1px solid #D1D5DB;
            color: #4B5563;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background-color: #F3F4F6;
        }

        .page-link.current {
            background-color: #2563EB;
            color: white;
            border-color: #2563EB;
        }
    </style>
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
    <script src="sidebar-loader.js"></script>
</head>

<body class="min-h-screen">
    <main class="main-content container mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Listado de Equipos</h1>
            <div class="flex gap-3">
                <button onclick="openFilterModal()" class="btn-primary flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Filtrar
                </button>
                <a href="informe.php" class="btn-secondary flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Nuevo Informe
                </a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>TAG Número</th>
                        <th>Tipo de Equipo</th>
                        <th>Prioridad</th>
                        <th>Nivel de Vibración</th>
                        <th>Fecha de Inspección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['tag_numero']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo_equipo']); ?></td>
                            <td>
                                <?php
                                $prioridad = $row['prioridad'];
                                $prioridadTexto = '';
                                switch ($prioridad) {
                                    case 1:
                                        $prioridadTexto = '1. Severa';
                                        break;
                                    case 2:
                                        $prioridadTexto = '2. Crítica';
                                        break;
                                    case 3:
                                        $prioridadTexto = '3. Observación';
                                        break;
                                    case 4:
                                        $prioridadTexto = '4. Normal';
                                        break;
                                    case 5:
                                        $prioridadTexto = 'F. Fuera de Servicio';
                                        break;
                                    default:
                                        $prioridadTexto = $prioridad;
                                }
                                echo $prioridadTexto;
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['nivel_vibracion']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_inspeccion']); ?></td>
                            <td>
                                <button onclick="verRegistro(<?php echo $row['id_inspeccion']; ?>)" class="btn-primary">
                                    Ver Detalles
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="?<?php
                echo http_build_query(array_merge(
                    $_GET,
                    ['page' => $page - 1]
                ));
                ?>" class="page-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            <?php } ?>

            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?<?php
                echo http_build_query(array_merge(
                    $_GET,
                    ['page' => $i]
                ));
                ?>" class="page-item <?php echo ($page == $i) ? 'current' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>

            <?php if ($page < $totalPages) { ?>
                <a href="?<?php
                echo http_build_query(array_merge(
                    $_GET,
                    ['page' => $page + 1]
                ));
                ?>" class="page-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            <?php } ?>
        </div>

        <!-- Modal de Filtros -->
        <div id="filterModal" class="modal-overlay" style="display: none;">
            <div class="modal-container">
                <div class="modal-header">
                    <h3 class="modal-title">Filtrar Equipos</h3>
                </div>
                <div class="modal-body">
                    <form id="filterForm" class="space-y-4">
                        <div>
                            <label for="tag_numero" class="block text-sm font-medium text-gray-700 mb-1">Número de
                                Tag:</label>
                            <input type="text" name="tag_numero" value="<?php echo htmlspecialchars($tag_numero); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="tipo_equipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                Equipo:</label>
                            <select name="tipo_equipo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="motor" <?php if ($tipo_equipo == 'motor')
                                    echo 'selected'; ?>>Motor
                                </option>
                                <option value="bomba" <?php if ($tipo_equipo == 'bomba')
                                    echo 'selected'; ?>>Bomba
                                </option>
                                <option value="turbina" <?php if ($tipo_equipo == 'turbina')
                                    echo 'selected'; ?>>Turbina
                                </option>
                                <option value="compresor" <?php if ($tipo_equipo == 'compresor')
                                    echo 'selected'; ?>>
                                    Compresor</option>
                            </select>
                        </div>
                        <div>
                            <label for="prioridad"
                                class="block text-sm font-medium text-gray-700 mb-1">Prioridad:</label>
                            <select name="prioridad"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" selected>Todas</option>
                                <option value="1" <?php if ($prioridad == '1')
                                    echo 'selected'; ?>>1. Severa</option>
                                <option value="2" <?php if ($prioridad == '2')
                                    echo 'selected'; ?>>2. Crítica</option>
                                <option value="3" <?php if ($prioridad == '3')
                                    echo 'selected'; ?>>3. Observación</option>
                                <option value="4" <?php if ($prioridad == '4')
                                    echo 'selected'; ?>>4. Normal</option>
                                <option value="5" <?php if ($prioridad == '5')
                                    echo 'selected'; ?>>F. Fuera de Servicio
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="nivel_vibracion" class="block text-sm font-medium text-gray-700 mb-1">Nivel de
                                Vibración:</label>
                            <select name="nivel_vibracion"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="Severo" <?php if ($nivel_vibracion == 'Severo')
                                    echo 'selected'; ?>>Severo
                                </option>
                                <option value="Alto" <?php if ($nivel_vibracion == 'Alto')
                                    echo 'selected'; ?>>Alto
                                </option>
                                <option value="Moderado" <?php if ($nivel_vibracion == 'Moderado')
                                    echo 'selected'; ?>>
                                    Moderado</option>
                                <option value="Bajo" <?php if ($nivel_vibracion == 'Bajo')
                                    echo 'selected'; ?>>Bajo
                                </option>
                                <option value="Ninguno" <?php if ($nivel_vibracion == 'Ninguno')
                                    echo 'selected'; ?>>Ninguno
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="fecha_inspeccion" class="block text-sm font-medium text-gray-700 mb-1">Fecha
                                Inspección:</label>
                            <input type="date" name="fecha_inspeccion"
                                value="<?php echo htmlspecialchars($fecha_inspeccion); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas:</label>
                            <div class="flex space-x-2">
                                <input type="date" name="fecha_inicio"
                                    value="<?php echo htmlspecialchars($fecha_inicio); ?>"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <span class="flex items-center">a</span>
                                <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="clearFilters()" class="btn-secondary mr-2">
                        Limpiar Filtros
                    </button>
                    <button type="button" onclick="applyFilters()" class="btn-primary">
                        Aplicar Filtros
                    </button>
                    <button type="button" onclick="closeFilterModal()" class="btn-danger ml-2">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Visualización de Registro -->
        <div id="viewModal" class="modal-overlay" style="display: none;">
            <div class="modal-container" style="max-width: 800px;">
                <div class="modal-header">
                    <h3 class="modal-title">Detalles del Equipo</h3>
                    <button type="button" onclick="closeViewModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalContent" class="data-container">
                        <!-- Aquí se cargará la información del registro -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Variables para los modales
        const filterModal = document.getElementById('filterModal');
        const viewModal = document.getElementById('viewModal');

        // Funciones para el modal de filtros
        function openFilterModal() {
            filterModal.style.display = 'flex';
        }

        function closeFilterModal() {
            filterModal.style.display = 'none';
        }

        function applyFilters() {
            document.getElementById('filterForm').submit();
        }

        function clearFilters() {
            window.location.href = window.location.pathname;
        }

        // Funciones para el modal de visualización
        function openViewModal() {
            viewModal.style.display = 'flex';
        }

        function closeViewModal() {
            viewModal.style.display = 'none';
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

                    // Función para formatear prioridad
                    function formatPrioridad(prioridad) {
                        switch (prioridad) {
                            case 1: return '1. Equipo en condiciones de vibración severas, debe tomarse acción inmediata';
                            case 2: return '2. Equipo en condición de vibración crítica, debe tomarse acción planificada';
                            case 3: return '3. Equipo bajo observación, monitorear operación y planificar mantenimiento';
                            case 4: return '4. Condición normal de operación';
                            case 5: return 'F. Fuera de Servicio';
                            default: return prioridad || 'No disponible';
                        }
                    }

                    // Función para crear listas HTML
                    function createList(items) {
                        if (!items || items.length === 0) return 'No disponible';
                        return `<ul class="list-disc pl-5">${items.map(item => `<li>${item}</li>`).join('')}</ul>`;
                    }

                    modalContent.innerHTML = `
                        <div class="section-title">Datos del Cliente</div>
                        <div class="data-label">Código:</div>
                        <div class="data-value">${data.codigo_cliente || 'No disponible'}</div>
                        <div class="data-label">Nombre:</div>
                        <div class="data-value">${data.nombre_cliente || 'No disponible'}</div>
                        <div class="data-label">RIF/C.I.:</div>
                        <div class="data-value">${data.rif_ci || 'No disponible'}</div>
                        <div class="data-label">Domicilio:</div>
                        <div class="data-value">${data.domicilio_fiscal || 'No disponible'}</div>

                        <div class="section-title">Datos del Equipo</div>
                        <div class="data-label">Tag Número:</div>
                        <div class="data-value">${data.tag_numero || 'No disponible'}</div>
                        <div class="data-label">Tipo:</div>
                        <div class="data-value">${data.tipo_equipo || 'No disponible'}</div>
                        <div class="data-label">Ubicación:</div>
                        <div class="data-value">${data.ubicacion || 'No disponible'}</div>

                        <div class="section-title">Inspección</div>
                        <div class="data-label">Fecha:</div>
                        <div class="data-value">${data.fecha_inspeccion || 'No disponible'}</div>
                        <div class="data-label">Temperaturas Motor:</div>
                        <div class="data-value">${data.temperatura_motor_1 || 'N/A'}°C / ${data.temperatura_motor_2 || 'N/A'}°C</div>
                        <div class="data-label">Temperaturas Bomba:</div>
                        <div class="data-value">${data.temperatura_bomba_1 || 'N/A'}°C / ${data.temperatura_bomba_2 || 'N/A'}°C</div>

                        <div class="section-title">Hallazgos</div>
                        <div class="data-value" style="grid-column: 1 / -1">${createList(data.hallazgos)}</div>

                        <div class="section-title">Fallas</div>
                        <div class="data-value" style="grid-column: 1 / -1">${createList(data.fallas)}</div>

                        <div class="section-title">Diagnóstico</div>
                        <div class="data-label">Prioridad:</div>
                        <div class="data-value">${formatPrioridad(data.prioridad)}</div>
                        <div class="data-label">Nivel Vibración:</div>
                        <div class="data-value">${data.nivel_vibracion || 'No disponible'}</div>

                        <div class="section-title">Análisis</div>
                        <div class="data-value" style="grid-column: 1 / -1; white-space: pre-wrap;">${data.analisis || 'No disponible'}</div>

                        <div class="section-title">Recomendaciones</div>
                        <div class="data-value" style="grid-column: 1 / -1; white-space: pre-wrap;">${data.recomendaciones || 'No disponible'}</div>
                    `;

                    openViewModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los detalles del equipo');
                });
        }

        // Cerrar modales al hacer clic fuera del contenido
        window.addEventListener('click', function (event) {
            if (event.target === filterModal) {
                closeFilterModal();
            }
            if (event.target === viewModal) {
                closeViewModal();
            }
        });
    </script>
</body>

</html>
<?php
$stmt->close();
$totalQuery->close();
$conn->close();
?>