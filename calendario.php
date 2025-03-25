<?php
require_once 'conexion.php';
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <script src="sidebar-loader.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                selectable: true,
                select: function (info) {
                    let fecha = info.startStr;
                    document.getElementById('modalFecha').value = fecha;
                    document.getElementById('agregarEventoModal').classList.remove('hidden');
                },
                events: 'obtener_eventos.php',
                eventClick: function (info) {
                    document.getElementById('detalleTitulo').textContent = 'Título: ' + info.event.title;
                    document.getElementById('detalleEquipos').textContent = 'ID del Equipo: ' + (info.event.extendedProps.id_equipo ? info.event.extendedProps.id_equipo : 'Ninguno');
                    document.getElementById('eliminarEvento').dataset.id = info.event.id;
                    document.getElementById('detalleEventoModal').classList.remove('hidden');
                },
                buttonText: {
                    today: 'Hoy'
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
        });
    </script>
    <style>
        body {
            background-color: #1a202c;
        }

        #modalTitulo,
        #modalEquipos {
            color: black;
        }

        .fc-theme-standard th,
        .fc-theme-standard td,
        .fc-theme-standard h2,
        .fc-theme-standard .fc-button {
            color: white;
        }
    </style>
</head>

<body class="">
    <main class="main-content">
        <div id='calendar' class="h-screen" style=""></div>
        </main>
        <div id="agregarEventoModal"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Agregar Evento</h3>
                    <div class="mt-2 px-7 py-3">
                        <form id="agregarEventoForm">
                            <input type="hidden" id="modalFecha">
                            <label for="modalTitulo" class="block text-sm font-medium text-gray-700">Título:</label>
                            <input type="text" id="modalTitulo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="modalEquipos" class="block text-sm font-medium text-gray-700">Equipos:</label>
                            <select id="modalEquipos" multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Ningún Equipo</option>
                            </select>
                            <div class="items-center px-4 py-3">
                                <button id="cerrarModal"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancelar</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="detalleEventoModal"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detalles del Evento</h3>
                    <div class="mt-2 px-7 py-3">
                        <p id="detalleTitulo"></p>
                        <p id="detalleEquipos"></p>
                        <div class="items-center px-4 py-3">
                            <button id="cerrarDetalleModal"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cerrar</button>
                            <button id="eliminarEvento"
                                class="px-4 py-2 bg-red-500 text-white rounded-md">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</body>

</html>