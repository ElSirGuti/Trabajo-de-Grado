<?php
// Inicia la sesión
session_start();

// Establece el código de estado HTTP para "Acceso Denegado" (403) por defecto
http_response_code(403);
$mensaje_error = "Acceso Denegado";

// Verifica si hay un mensaje de error personalizado en la sesión
if (isset($_SESSION['error_message'])) {
    $mensaje_error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Oops! Error</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #f0f0f0, #ffffff);
            /* Suave degradado de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Sombra más pronunciada */
            padding: 3rem;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
            /* Animación de entrada */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #e74c3c;
            /* Rojo vibrante */
            font-size: 2.5rem;
            /* Tamaño más grande */
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            /* Suave sombra al texto */
        }

        p {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .back-link {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #3498db;
            /* Azul moderno */
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            /* Transición suave */
        }

        .back-link:hover {
            background-color: #2980b9;
            /* Azul más oscuro al pasar el ratón */
        }

        .sad-face {
            font-size: 3rem;
            color: #f39c12;
            /* Amarillo anaranjado */
            margin-bottom: 1rem;
            animation: bounce 1s infinite alternate;
            /* Animación de rebote */
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="sad-face">:(</div>
        <h1>¡Oops! Parece que te has perdido.</h1>
        <p><?php echo htmlspecialchars($mensaje_error); ?></p>
        <a href="javascript:history.back()" class="back-link">Volver a la página anterior</a>
    </div>
</body>

</html>