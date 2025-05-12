<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <script src="sidebar-loader.js"></script>
    <style>
        :root {
            --primary: #2563EB;
            --secondary: #1F2937;
            --accent: #10B981;
            --text: #1F2937;
            --text-light: #6B7280;
            --bg-light: #F3F4F6;
            --white: #FFFFFF;
        }

        body {
            background-color: var(--white);
            color: var(--text);
        }

        #calendar {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Estilos del calendario */
        .fc-header-toolbar {
            background-color: var(--white);
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
            border-bottom: 1px solid var(--bg-light);
        }

        .fc-toolbar-title {
            color: var(--secondary);
            font-weight: 600;
        }

        .fc-button {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .fc-button:hover {
            background-color: #1D4ED8 !important;
        }

        .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1D4ED8 !important;
        }

        .fc-col-header-cell {
            background-color: var(--bg-light);
            color: var(--text);
            font-weight: 600;
            padding: 0.5rem 0;
        }

        .fc-daygrid-day {
            border-color: var(--bg-light);
        }

        .fc-daygrid-day-number {
            color: var(--text);
        }

        .fc-day-today {
            background-color: rgb(212, 240, 255) !important;
        }

        .fc-day-past:not(.fc-day-today) {
            background-color: #f3f4f6;
            pointer-events: pointer;
            opacity: 0.6;
        }

        .fc-event {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85rem;
        }

        .fc-event:hover {
            opacity: 0.9;
        }

        /* Modales */
        .modal-container {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            color: var(--secondary);
            border-bottom: 1px solid var(--bg-light);
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary) !important;
            color: white !important;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background-color: #1D4ED8 !important;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #E5E7EB !important;
            color: var(--text) !important;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background-color: #D1D5DB !important;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #EF4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #DC2626;
        }

        input,
        select {
            border-color: var(--bg-light);
            color: var(--text);
        }

        input:focus,
        select:focus {
            border-color: var(--primary);
            ring-color: var(--primary);
        }

        /* Textos */
        .text-muted {
            color: var(--text-light);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const usuarioRol = '<?php echo isset($_SESSION['rol']) ? $_SESSION['rol'] : ''; ?>';
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                selectable: true,
                select: function (info) {
                    let fechaSeleccionada = info.startStr.split('T')[0];
                    let hoy = new Date().toISOString().split('T')[0];

                    // Solo bloquear fechas pasadas si el usuario NO es 'Administrador' o 'Super'
                    if (fechaSeleccionada < hoy && !['Administrador', 'Super'].includes(usuarioRol)) {
                        alert("No puedes agregar eventos en fechas pasadas.");
                        calendar.unselect();
                        return;
                    }

                    document.getElementById('modalFecha').value = fechaSeleccionada;
                    document.getElementById('agregarEventoModal').classList.remove('hidden');
                },
                events: 'obtener_eventos.php',
                eventClick: function (info) {
                    // Mostrar datos del evento
                    document.getElementById('detalleTitulo').textContent = 'Título: ' + info.event.title;
                    document.getElementById('detalleFecha').textContent = 'Fecha: ' + info.event.startStr.split('T')[0];
                    document.getElementById('detalleEquipos').textContent = 'Equipo: ' + (info.event.extendedProps.id_equipo || 'Ninguno');

                    // Guardar ID del evento
                    document.getElementById('eventoIdEditar').value = info.event.id;

                    // Mostrar botones según el rol
                    const botonEditar = document.getElementById('editarEvento');
                    const botonEliminar = document.getElementById('eliminarEvento');

                    if (usuarioRol === 'Super') {
                        botonEliminar.classList.remove('hidden');
                        botonEliminar.dataset.id = info.event.id; // Asignar ID al botón
                    } else {
                        botonEliminar.classList.add('hidden');
                    }

                    if (['Administrador', 'Super'].includes(usuarioRol)) {
                        botonEditar.classList.remove('hidden');
                        botonEditar.dataset.id = info.event.id;
                    } else {
                        botonEditar.classList.add('hidden');
                    }

                    // Asegurar modo visualización
                    document.getElementById('modoVisualizacion').classList.remove('hidden');
                    document.getElementById('formEditarEvento').classList.add('hidden');

                    // Mostrar modal
                    document.getElementById('detalleEventoModal').classList.remove('hidden');
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes'
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                }
            });
            calendar.render();

            document.getElementById('agregarEventoForm').addEventListener('submit', function (event) {
                event.preventDefault();
                let fecha = document.getElementById('modalFecha').value;
                let titulo = document.getElementById('modalTitulo').value;
                let equipos = Array.from(document.getElementById('modalEquipos').selectedOptions).map(option => option.value);

                console.log(JSON.stringify({ fecha: fecha, titulo: titulo, equipos: equipos }));

                fetch('agregar_evento.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ fecha: fecha, titulo: titulo, equipos: equipos }),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.success) {
                            calendar.addEvent({
                                title: titulo,
                                start: fecha,
                                allDay: true
                            });
                            document.getElementById('agregarEventoModal').classList.add('hidden');
                        } else {
                            alert('Error al agregar evento: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Botón "Editar": Cambiar a modo edición
            document.getElementById('editarEvento').addEventListener('click', function () {
                const eventoId = this.dataset.id;
                const evento = calendar.getEventById(eventoId);

                // Llenar formulario con datos actuales
                document.getElementById('editarTitulo').value = evento.title;
                document.getElementById('editarFecha').value = evento.startStr.split('T')[0];

                // Cargar equipos (reutilizando la lógica del modal de agregar)
                fetch('obtener_equipos.php')
                    .then(response => response.json())
                    .then(equipos => {
                        const select = document.getElementById('editarEquipo');
                        select.innerHTML = '<option value="">Ningún Equipo</option>';
                        equipos.forEach(equipo => {
                            const option = document.createElement('option');
                            option.value = equipo.id_equipo;
                            option.textContent = equipo.tag_numero;
                            // Seleccionar el equipo actual del evento
                            if (evento.extendedProps.id_equipo == equipo.id_equipo) {
                                option.selected = true;
                            }
                            select.appendChild(option);
                        });
                    });

                // Cambiar a modo edición
                document.getElementById('modoVisualizacion').classList.add('hidden');
                document.getElementById('formEditarEvento').classList.remove('hidden');
            });

            // Cancelar edición
            document.getElementById('cancelarEdicion').addEventListener('click', function () {
                document.getElementById('modoVisualizacion').classList.remove('hidden');
                document.getElementById('formEditarEvento').classList.add('hidden');
            });

            // Guardar cambios (submit del formulario)
            document.getElementById('formEditarEvento').addEventListener('submit', function (e) {
                e.preventDefault();
                const eventoId = document.getElementById('eventoIdEditar').value;
                const nuevoTitulo = document.getElementById('editarTitulo').value;
                const nuevaFecha = document.getElementById('editarFecha').value;
                const nuevoEquipo = document.getElementById('editarEquipo').value;

                fetch('editar_evento.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: eventoId,
                        titulo: nuevoTitulo,
                        fecha: nuevaFecha,
                        id_equipo: nuevoEquipo || null
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Actualizar evento en el calendario
                            const evento = calendar.getEventById(eventoId);
                            evento.setProp('title', nuevoTitulo);
                            evento.setStart(nuevaFecha);
                            evento.setExtendedProp('id_equipo', nuevoEquipo);

                            // Actualizar texto en el modal
                            document.getElementById('detalleTitulo').textContent = 'Título: ' + nuevoTitulo;
                            document.getElementById('detalleFecha').textContent = 'Fecha: ' + nuevaFecha;
                            document.getElementById('detalleEquipos').textContent = 'Equipo: ' + (nuevoEquipo || 'Ninguno');

                            // Volver a modo visualización
                            document.getElementById('modoVisualizacion').classList.remove('hidden');
                            document.getElementById('formEditarEvento').classList.add('hidden');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            });

            document.getElementById('cerrarModal').addEventListener('click', function () {
                document.getElementById('agregarEventoModal').classList.add('hidden');
            });

            fetch('obtener_equipos.php')
                .then(response => response.json())
                .then(equipos => {
                    let selectEquipos = document.getElementById('modalEquipos');
                    selectEquipos.innerHTML = '<option value="">Ningún Equipo</option>';
                    equipos.forEach(equipo => {
                        let option = document.createElement('option');
                        option.value = equipo.id_equipo;
                        option.text = equipo.tag_numero;
                        selectEquipos.appendChild(option);
                    });
                });

            document.getElementById('cerrarDetalleModal').addEventListener('click', function () {
                document.getElementById('detalleEventoModal').classList.add('hidden');
            });

            document.getElementById('eliminarEvento').addEventListener('click', function () {
                let id = this.dataset.id;
                fetch('eliminar_evento.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            calendar.getEventById(id).remove();
                            document.getElementById('detalleEventoModal').classList.add('hidden');
                        } else {
                            alert('Error al eliminar evento: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

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

<body class="bg-white">
    <main class="main-content p-4" style="background-color: #F9FAFB;">
        <div id='calendar' class="h-screen p-4"></div>
    </main>

    <!-- Modal Agregar Evento -->
    <div id="agregarEventoModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="modal-container w-96">
            <div class="modal-header p-4">
                <h3 class="modal-title">Agregar Evento</h3>
            </div>
            <div class="modal-body p-4">
                <form id="agregarEventoForm" class="space-y-4">
                    <input type="hidden" id="modalFecha">
                    <div>
                        <label for="modalTitulo" class="block text-sm font-medium text-gray-700">Título:</label>
                        <input type="text" id="modalTitulo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="modalEquipos" class="block text-sm font-medium text-gray-700">Equipos:</label>
                        <select id="modalEquipos" multiple
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Ningún Equipo</option>
                        </select>
                    </div>
                    <div class="modal-footer flex justify-end space-x-2 pt-4">
                        <button type="button" id="cerrarModal" class="btn-secondary px-4 py-2">Cancelar</button>
                        <button type="submit" class="btn-primary px-4 py-2">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detalle Evento -->
    <div id="detalleEventoModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="modal-container w-96">
            <div class="modal-header p-4">
                <h3 class="modal-title">Detalles del Evento</h3>
            </div>
            <div class="modal-body p-4">
                <!-- Campos en modo visualización -->
                <div id="modoVisualizacion">
                    <p id="detalleTitulo" class="mb-2"></p>
                    <p id="detalleFecha" class="mb-2"></p>
                    <p id="detalleEquipos" class="mb-4"></p>
                </div>

                <!-- Formulario de edición (oculto inicialmente) -->
                <form id="formEditarEvento" class="hidden space-y-3">
                    <input type="hidden" id="eventoIdEditar">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título:</label>
                        <input type="text" id="editarTitulo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha:</label>
                        <input type="date" id="editarFecha"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Equipo:</label>
                        <select id="editarEquipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <!-- Opciones se llenarán con JavaScript -->
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" id="cancelarEdicion" class="btn-secondary px-3 py-1">Cancelar</button>
                        <button type="submit" class="btn-primary px-3 py-1">Guardar</button>
                    </div>
                </form>

                <!-- Botones del footer -->
                <div class="modal-footer flex justify-end space-x-2 pt-4">
                    <button id="cerrarDetalleModal" class="btn-secondary px-4 py-2">Cerrar</button>
                    <button id="editarEvento" class="btn-primary px-4 py-2 hidden">Editar</button>
                    <!-- Oculto por defecto -->
                    <button id="eliminarEvento" class="btn-danger px-4 py-2 hidden">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>