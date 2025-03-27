<?php
require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <script src="sidebar-loader.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-contenido {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .cerrar {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
        }

        .cerrar:hover,
        .cerrar:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-contenido h1 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .modal-contenido ul {
            list-style: none;
            padding: 0;
        }

        .modal-contenido li {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .modal-contenido li:last-child {
            border-bottom: none;
        }
        
        /* Contenedor principal de gráficos */
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(550px, 1fr));
            gap: 20px;
            padding: 20px;
            width: 100%;
            max-width: 1800px;
            margin: 0 auto;
        }
        
        /* Estilo para cada tarjeta de gráfico */
        .chart-card {
            background: #2d3748;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 400px; /* Altura fija para todas las tarjetas */
            display: flex;
            flex-direction: column;
        }
        
        .chart-card:hover {
            transform: translateY(-5px);
        }
        
        .chart-title {
            color: #fff;
            margin-bottom: 15px;
            font-size: 1.2rem;
            text-align: center;
            padding: 5px;
        }
        
        .chart-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Ajustes responsivos */
        @media (max-width: 1200px) {
            .dashboard-container {
                grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .chart-card {
                height: 350px;
            }
        }
    </style>
</head>
<body style="background-color: #1a202c;">
    <main class="main-content">
        <!-- Modal para detalles -->
        <div id="miModal" class="modal" style="display: none;">
            <div class="modal-contenido">
                <span class="cerrar">&times;</span>
                <div id="modalContenido"></div>
            </div>
        </div>

        <div class="dashboard-container">
            <!-- Equipos por tipo -->
            <?php
            $query = "SELECT tipo_equipo, COUNT(*) AS count FROM equipos GROUP BY tipo_equipo";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['tipo_equipo'], (int) $row['count']);
            }
            $jsonDataTipoEquipo = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Número de Equipos por Tipo</h3>
                <div class="chart-wrapper">
                    <div id="chart_tipo_equipo" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartTipoEquipo);
                    function drawChartTipoEquipo() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Tipo de Equipo');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataTipoEquipo; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'pieHole': 0.4,
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'labeled', 
                                textStyle: { color: '#fff' } 
                            },
                            'chartArea': { width: '90%', height: '80%' },
                            'tooltip': { textStyle: { color: '#333' } }
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('chart_tipo_equipo'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('tipo_equipo', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartTipoEquipo);
                </script>
            </div>

            <!-- Equipos por ubicacion -->
            <?php
            $query = "SELECT ubicacion, COUNT(*) AS count FROM equipos GROUP BY ubicacion";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['ubicacion'], (int) $row['count']);
            }
            $jsonDataUbicacion = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Número de Equipos por Ubicación</h3>
                <div class="chart-wrapper">
                    <div id="chart_ubicacion" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartUbicacion);
                    function drawChartUbicacion() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Ubicación');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataUbicacion; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'pieHole': 0.4,
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'labeled', 
                                textStyle: { color: '#fff' } 
                            },
                            'chartArea': { width: '90%', height: '80%' }
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('chart_ubicacion'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('ubicacion', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartUbicacion);
                </script>
            </div>

            <!-- Equipos por cliente -->
            <?php
            $query = "SELECT c.nombre_cliente, COUNT(e.id_equipo) AS count FROM equipos e JOIN clientes c ON e.id_cliente = c.id_cliente GROUP BY c.id_cliente";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['nombre_cliente'], (int) $row['count']);
            }
            $jsonDataCliente = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Número de Equipos por Cliente</h3>
                <div class="chart-wrapper">
                    <div id="chart_cliente" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartCliente);
                    function drawChartCliente() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Cliente');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataCliente; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'pieHole': 0.4,
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'labeled', 
                                textStyle: { color: '#fff' } 
                            },
                            'chartArea': { width: '90%', height: '80%' }
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('chart_cliente'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('cliente', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartCliente);
                </script>
            </div>

            <!-- Equipos por prioridad -->
            <?php
            $query = "SELECT d.prioridad, COUNT(*) AS count FROM diagnosticos d GROUP BY d.prioridad";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array('Prioridad ' . $row['prioridad'], (int) $row['count']);
            }
            $jsonDataPrioridad = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Equipos por Prioridad</h3>
                <div class="chart-wrapper">
                    <div id="chart_prioridad" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartPrioridad);
                    function drawChartPrioridad() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Prioridad');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataPrioridad; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            }
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_prioridad'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0).replace('Prioridad ', '');
                                mostrarDetalles('prioridad', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartPrioridad);
                </script>
            </div>
            
            <!-- Equipos por nivel de vibración -->
            <?php
            $query = "SELECT nivel_vibracion, COUNT(*) AS count FROM diagnosticos GROUP BY nivel_vibracion";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['nivel_vibracion'], (int) $row['count']);
            }
            $jsonDataVibracion = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Equipos por Nivel de Vibración</h3>
                <div class="chart-wrapper">
                    <div id="chart_vibracion" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartVibracion);
                    function drawChartVibracion() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Nivel de Vibración');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataVibracion; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            }
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_vibracion'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('vibracion', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartVibracion);
                </script>
            </div>

            <!-- Inspecciones por falla -->
            <?php
            $query = "SELECT lf.falla, COUNT(*) AS count FROM inspeccion_fallas ifa JOIN lista_fallas lf ON ifa.id_falla = lf.id_falla GROUP BY lf.falla ORDER BY count DESC LIMIT 10";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['falla'], (int) $row['count']);
            }
            $jsonDataFallas = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Fallas Más Comunes</h3>
                <div class="chart-wrapper">
                    <div id="chart_fallas" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartFallas);
                    function drawChartFallas() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Falla');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataFallas; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'bars': 'horizontal'
                        };
                        var chart = new google.visualization.BarChart(document.getElementById('chart_fallas'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('falla', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartFallas);
                </script>
            </div>

            <!-- Inspecciones por hallazgo -->
            <?php
            $query = "SELECT lh.hallazgo, COUNT(*) AS count FROM inspeccion_hallazgos iha JOIN lista_hallazgos lh ON iha.id_hallazgo = lh.id_hallazgo GROUP BY lh.hallazgo ORDER BY count DESC LIMIT 10";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['hallazgo'], (int) $row['count']);
            }
            $jsonDataHallazgos = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Hallazgos Más Comunes</h3>
                <div class="chart-wrapper">
                    <div id="chart_hallazgos" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartHallazgos);
                    function drawChartHallazgos() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Hallazgo');
                        data.addColumn('number', 'Cantidad');
                        data.addRows(<?php echo $jsonDataHallazgos; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'bars': 'horizontal'
                        };
                        var chart = new google.visualization.BarChart(document.getElementById('chart_hallazgos'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('hallazgo', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartHallazgos);
                </script>
            </div>

            <!-- Inspecciones por equipo -->
            <?php
            $query = "SELECT e.tag_numero, COUNT(i.id_inspeccion) AS count FROM equipos e JOIN inspecciones i ON e.id_equipo = i.id_equipo GROUP BY e.id_equipo";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['tag_numero'], (int) $row['count']);
            }
            $jsonDataInspeccionesEquipo = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Inspecciones por Equipo</h3>
                <div class="chart-wrapper">
                    <div id="chart_inspecciones_equipo" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartInspeccionesEquipo);
                    function drawChartInspeccionesEquipo() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Equipo');
                        data.addColumn('number', 'Inspecciones');
                        data.addRows(<?php echo $jsonDataInspeccionesEquipo; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1', '#48bb78', '#f6ad55', '#f56565', '#9f7aea'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            }
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_inspecciones_equipo'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('inspecciones_equipo', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartInspeccionesEquipo);
                </script>
            </div>

            <!-- Inspecciones por fecha -->
            <?php
            $query = "SELECT fecha_inspeccion, COUNT(id_inspeccion) AS count FROM inspecciones GROUP BY fecha_inspeccion";
            $result = $conn->query($query);
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array($row['fecha_inspeccion'], (int) $row['count']);
            }
            $jsonDataInspeccionesFecha = json_encode($data);
            ?>
            <div class="chart-card">
                <h3 class="chart-title">Inspecciones por Fecha</h3>
                <div class="chart-wrapper">
                    <div id="chart_inspecciones_fecha" style="width: 100%; height: 100%;"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', { 'packages': ['corechart'] });
                    google.charts.setOnLoadCallback(drawChartInspeccionesFecha);
                    function drawChartInspeccionesFecha() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Fecha de Inspección');
                        data.addColumn('number', 'Inspecciones');
                        data.addRows(<?php echo $jsonDataInspeccionesFecha; ?>);
                        var options = {
                            'width': '100%',
                            'height': '100%',
                            'colors': ['#4299e1'],
                            'backgroundColor': 'transparent',
                            'legend': { 
                                position: 'none' 
                            },
                            'chartArea': { width: '85%', height: '80%' },
                            'hAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'vAxis': { 
                                textStyle: { color: '#fff' },
                                titleTextStyle: { color: '#fff' }
                            },
                            'pointSize': 5,
                            'lineWidth': 2
                        };
                        var chart = new google.visualization.LineChart(document.getElementById('chart_inspecciones_fecha'));
                        google.visualization.events.addListener(chart, 'select', function () {
                            var selection = chart.getSelection();
                            if (selection.length) {
                                var selectedItem = data.getValue(selection[0].row, 0);
                                mostrarDetalles('inspecciones_fecha', selectedItem);
                            }
                        });
                        chart.draw(data, options);
                    }
                    window.addEventListener('resize', drawChartInspeccionesFecha);
                </script>
            </div>
        </div>
        
        <!-- Script para manejar todos los modales -->
        <script type="text/javascript">
            // Función genérica para mostrar detalles
            function mostrarDetalles(tipo, valor) {
                // Realizar una petición AJAX para obtener los detalles
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Mostrar los detalles en el modal
                        document.getElementById('modalContenido').innerHTML = xhr.responseText;
                        var modal = document.getElementById('miModal');
                        modal.style.display = 'flex';
                    }
                };
                xhr.open('GET', 'obtener_detalles.php?tipo=' + encodeURIComponent(tipo) + '&valor=' + encodeURIComponent(valor), true);
                xhr.send();
            }

            // Cerrar el modal
            document.getElementsByClassName('cerrar')[0].onclick = function () {
                var modal = document.getElementById('miModal');
                modal.style.display = 'none';
            };

            // Cerrar el modal si se hace clic fuera del contenido
            window.onclick = function (event) {
                var modal = document.getElementById('miModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            // Redibujar gráficos al cambiar el tamaño de la ventana
            window.addEventListener('resize', function() {
                if (typeof drawChartTipoEquipo === 'function') drawChartTipoEquipo();
                if (typeof drawChartUbicacion === 'function') drawChartUbicacion();
                if (typeof drawChartCliente === 'function') drawChartCliente();
                if (typeof drawChartPrioridad === 'function') drawChartPrioridad();
                if (typeof drawChartVibracion === 'function') drawChartVibracion();
                if (typeof drawChartFallas === 'function') drawChartFallas();
                if (typeof drawChartHallazgos === 'function') drawChartHallazgos();
                if (typeof drawChartInspeccionesEquipo === 'function') drawChartInspeccionesEquipo();
                if (typeof drawChartInspeccionesFecha === 'function') drawChartInspeccionesFecha();
            });
        </script>
    </main>
</body>
</html>