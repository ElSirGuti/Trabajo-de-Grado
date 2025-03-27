<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        /* Estilos personalizados con tu paleta de colores */
        body {
            background-color: #1a202c;
        }

        .login-container {
            background-image: url('https://source.unsplash.com/1600x900/?nature,water');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.2;
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .login-form {
            background-color: #2d3748;
            border: 1px solid #4a5568;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .input-field {
            background-color: #4a5568;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        .input-field:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }

        .btn-primary {
            background-color: #4299e1;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3182ce;
        }

        .text-muted {
            color: #a0aec0;
        }

        .text-accent {
            color: #4299e1;
        }

        .toggle-password {
            color: #a0aec0;
        }

        .toggle-password:hover {
            color: #e2e8f0;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Fondo con opacidad -->
    <div class="login-container"></div>

    <!-- Formulario de login -->
    <div class="login-form relative z-10 w-full max-w-md p-8 rounded-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">BIENVENIDO/A</h1>
            <p class="text-muted">Sistema de Mantenimiento Proactivo de R.S.C. Services C.A.</p>
        </div>

        <form id="loginForm" class="space-y-6">
            <!-- Campo de correo -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email" required
                    class="input-field w-full px-4 py-3 rounded-md focus:outline-none focus:ring-1">
            </div>

            <!-- Campo de contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="input-field w-full px-4 py-3 rounded-md focus:outline-none focus:ring-1 pr-10">
                    <button type="button" onclick="togglePasswordVisibility()"
                        class="toggle-password absolute right-3 top-3">
                        👁️
                    </button>
                </div>
            </div>

            <!-- Botón de submit -->
            <button type="submit" class="btn-primary w-full py-3 px-4 rounded-md font-medium transition-colors">
                Iniciar Sesión
            </button>
        </form>

        <!-- Enlace para administrador -->
        <div class="mt-6 text-center">
            <p class="text-sm text-muted">
                ¿Problemas para acceder? Contacta al
                <a href="#" class="text-accent hover:underline">administrador</a>
            </p>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        // Función para encriptar contraseña con SHA-256
        function encryptPassword(password) {
            return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
        }

        // Manejar envío del formulario
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Obtener valores del formulario
            const correo = document.getElementById('email').value; // El ID del input sigue siendo email
            const contrasena = document.getElementById('password').value; // El ID del input sigue siendo password

            // Validación básica
            if (!correo || !contrasena) {
                alert('Por favor complete todos los campos');
                return;
            }

            // Validar formato de email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                alert('Por favor ingrese un correo electrónico válido');
                return;
            }

            // Encriptar contraseña
            const encryptedPassword = encryptPassword(contrasena);

            // Mostrar indicador de carga
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-block animate-spin">⏳</span> Verificando...';

            // Depuración: ver qué se está enviando
            console.log("Datos a enviar:", {
                correo: correo,
                contrasena: encryptedPassword
            });

            // Enviar datos al servidor
            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    correo: correo,
                    contrasena: encryptedPassword
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = 'tabla.php';
                    } else {
                        alert(data.message || 'Credenciales incorrectas');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error en el servidor. Por favor intente más tarde.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Iniciar Sesión';
                });
        });
    </script>
</body>

</html>