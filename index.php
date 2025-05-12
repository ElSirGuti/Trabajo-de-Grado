<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        /* Estilos personalizados con la nueva paleta */
        body {
            background-color: #F9FAFB;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .login-container {
            background-image: url('https://source.unsplash.com/1600x900/?technology,modern');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.05;
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .login-form {
            background-color: #FFFFFF;
            border: 1px solid #F3F4F6;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 20px 40px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .input-field {
            background-color: #F3F4F6;
            border: 1px solid #E5E7EB;
            color: #1F2937;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: #FFFFFF;
        }

        .btn-primary {
            background-color: #2563EB;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1D4ED8;
            transform: translateY(-1px);
        }

        .text-muted {
            color: #6B7280;
        }

        .text-accent {
            color: #2563EB;
            transition: all 0.2s ease;
        }

        .text-accent:hover {
            color: #1D4ED8;
        }

        .toggle-password {
            color: #6B7280;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #2563EB;
        }
    </style>

</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Fondo sutil -->
    <div class="login-container"></div>

    <!-- Formulario de login -->
    <div class="login-form relative z-10 w-full max-w-md p-8" style="min-height: 450px;">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-900">BIENVENIDO/A</h1>
        </div>

        <form id="loginForm" class="space-y-6">
            <!-- Campo de correo -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email" required
                    class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-1 text-gray-900">
            </div>

            <!-- Campo de contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-1 pr-10 text-gray-900">
                    <button type="button" onclick="togglePasswordVisibility()"
                        class="toggle-password absolute right-3 top-3">
                        👁️
                    </button>
                </div>
            </div>

            <!-- Botón de submit -->
            <button type="submit" class="btn-primary w-full py-3 px-4 rounded-lg font-medium">
                Iniciar Sesión
            </button>
        </form>

        <!-- Enlace para administrador -->
        <div class="mt-6 text-center">
            <p class="text-sm text-muted">
                ¿Problemas para acceder? Contacta al
                <a href="#" class="text-accent hover:underline font-medium">administrador</a>
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
            const correo = document.getElementById('email').value;
            const contrasena = document.getElementById('password').value;

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