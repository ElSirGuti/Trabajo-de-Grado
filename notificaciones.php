<?php
require 'conexion.php';

// Consulta modificada con los nombres correctos de columnas
$query = "SELECT e.id, e.fecha, e.titulo, eq.tag_numero as equipo 
          FROM eventos e 
          LEFT JOIN equipos eq ON e.id_equipo = eq.id_equipo 
          WHERE e.fecha >= CURDATE() 
          ORDER BY e.fecha ASC 
          LIMIT 5";

$result = mysqli_query($conn, $query);
$hayEventos = mysqli_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próximos Mantenimientos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a202c;
            margin: 0;
            color: #e2e8f0;
        }

        .notificaciones-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #2d3748;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #4a5568;
        }

        .notificaciones-header {
            background-color: #4a5568;
            color: white;
            padding: 16px 24px;
            font-size: 1.5em;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .lista-notificaciones {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .notificacion-item {
            padding: 16px 24px;
            border-bottom: 1px solid #4a5568;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }

        .notificacion-item:hover {
            background-color: #3c4657;
        }

        .notificacion-item:last-child {
            border-bottom: none;
        }

        .notificacion-info {
            flex-grow: 1;
        }

        .notificacion-titulo {
            font-weight: bold;
            margin-bottom: 8px;
            color: #ffffff;
            font-size: 1.1em;
        }

        .notificacion-equipo {
            font-style: italic;
            color: #a0aec0;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .notificacion-fecha {
            color: #a0aec0;
            font-size: 0.9em;
            background-color: #4a5568;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .sin-notificaciones {
            padding: 24px;
            text-align: center;
            color: #a0aec0;
            font-style: italic;
        }

        .badge {
            background-color: #3b82f6;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            font-weight: bold;
        }

        .badge-empty {
            background-color: #4a5568;
        }
    </style>
    <script src="sidebar-loader.js"></script>
</head>

<body>
    <main class="main-content">
        <div class="notificaciones-container">
            <div class="notificaciones-header">
                <span>Próximos Mantenimientos</span>
                <?php if ($hayEventos): ?>
                    <span class="badge"><?php echo mysqli_num_rows($result); ?></span>
                <?php else: ?>
                    <span class="badge badge-empty">0</span>
                <?php endif; ?>
            </div>

            <?php if ($hayEventos): ?>
                <ul class="lista-notificaciones">
                    <?php while ($evento = mysqli_fetch_assoc($result)): ?>
                        <li class="notificacion-item">
                            <div class="notificacion-info">
                                <div class="notificacion-titulo"><?php echo htmlspecialchars($evento['titulo']); ?></div>
                                <?php if (!empty($evento['equipo'])): ?>
                                    <div class="notificacion-equipo">Equipo: <?php echo htmlspecialchars($evento['equipo']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="notificacion-fecha">
                                    <?php
                                    $fecha = new DateTime($evento['fecha']);
                                    echo $fecha->format('d/m/Y');
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="sin-notificaciones">
                    No hay mantenimientos programados en este momento.
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);