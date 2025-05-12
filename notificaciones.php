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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F9FAFB;
            margin: 0;
            color: #1F2937;
        }

        .notificaciones-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .notificaciones-header {
            background-color: #2563EB;
            color: white;
            padding: 18px 24px;
            font-size: 1.25rem;
            font-weight: 600;
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
            padding: 18px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .notificacion-item:hover {
            background-color: #F9FAFB;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .notificacion-item:last-child {
            border-bottom: none;
        }

        .notificacion-info {
            flex-grow: 1;
        }

        .notificacion-titulo {
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
            font-size: 1.05rem;
        }

        .notificacion-equipo {
            color: #6B7280;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .notificacion-fecha {
            color: #FFFFFF;
            font-size: 0.85rem;
            background-color: #10B981;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 500;
        }

        .sin-notificaciones {
            padding: 30px;
            text-align: center;
            color: #6B7280;
            font-size: 0.95rem;
        }

        .badge {
            background-color: #FFFFFF;
            color: #2563EB;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge-empty {
            background-color: #E5E7EB;
            color: #6B7280;
        }

        .main-content {
            margin-left: 250px; /* Ajuste para el sidebar */
            padding: 20px;
            transition: margin 0.3s ease;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .notificaciones-container {
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
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

<body>
    <!-- Sidebar se cargará aquí -->
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
                                    <div class="notificacion-equipo">Equipo: <?php echo htmlspecialchars($evento['equipo']); ?></div>
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
?>