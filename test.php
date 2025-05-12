<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Prueba de Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <?php
        session_start();

        if (isset($_SESSION['rol'])) {
            $rol_usuario = $_SESSION['rol'];
            echo "<h1 class='text-2xl font-semibold mb-4'>Bienvenido a la Página de Prueba</h1>";
            echo "<p class='mb-4'>Tu rol de usuario es: <span class='font-bold text-blue-600'>" . htmlspecialchars($rol_usuario) . "</span></p>";

            // Ahora puedes usar $rol_usuario para controlar el acceso o mostrar información específica
            if ($rol_usuario === 'Super') {
                echo "<div class='bg-purple-100 p-4 rounded mb-4'>";
                echo "<h2 class='text-xl font-semibold mb-2'>Área de Super Usuario</h2>";
                echo "<p>Aquí tienes acceso a todas las funcionalidades del sistema:</p>";
                echo "<ul class='list-disc list-inside'>";
                echo "<li>Gestionar usuarios</li>";
                echo "<li>Ver informes detallados</li>";
                echo "<li>Configurar el sistema</li>";
                echo "<li>Administrar equipos</li>";
                echo "<li>Generar órdenes de trabajo</li>";
                echo "<li>Acceder a datos críticos</li>";
                echo "</ul>";
                echo "</div>";
            } elseif ($rol_usuario === 'Administrador') {
                echo "<div class='bg-green-100 p-4 rounded mb-4'>";
                echo "<h2 class='text-xl font-semibold mb-2'>Área de Administración</h2>";
                echo "<p>Aquí puedes acceder a las funcionalidades de administración. Por ejemplo:</p>";
                echo "<ul class='list-disc list-inside'>";
                echo "<li>Gestionar usuarios</li>";
                echo "<li>Ver informes detallados</li>";
                echo "<li>Configurar el sistema</li>";
                echo "</ul>";
                echo "</div>";
            } elseif ($rol_usuario === 'Tecnico') {
                echo "<div class='bg-yellow-100 p-4 rounded mb-4'>";
                echo "<h2 class='text-xl font-semibold mb-2'>Área Técnica</h2>";
                echo "<p>Aquí puedes acceder a las funcionalidades técnicas. Por ejemplo:</p>";
                echo "<ul class='list-disc list-inside'>";
                echo "<li>Registrar mantenimientos</li>";
                echo "<li>Ver historial de equipos</li>";
                echo "<li>Generar órdenes de trabajo</li>";
                echo "</ul>";
                echo "</div>";
            } else {
                echo "<p class='text-red-500'>Rol desconocido.</p>";
            }

            echo "<p class='mt-4'><a href='logout.php' class='text-blue-500 hover:underline'>Cerrar Sesión</a></p>";
        } else {
            // El rol no está en la sesión, el usuario probablemente no ha iniciado sesión correctamente
            echo "<h1 class='text-2xl font-semibold mb-4'>Acceso Denegado</h1>";
            echo "<p class='mb-4 text-red-500'>No has iniciado sesión correctamente. Por favor, inicia sesión para acceder a esta página.</p>";
            echo "<p><a href='index.php' class='text-blue-500 hover:underline'>Volver a la página de inicio de sesión</a></p>";
        }
        ?>
    </div>

</body>

</html>