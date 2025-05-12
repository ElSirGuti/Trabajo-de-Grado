<?php
include 'auth.php';
verificarRol(['Super', 'Administrador']);

// session_start();
require_once 'conexion.php';

// Obtener lista de usuarios
$query = "SELECT id_usuario, nombre, correo, rol FROM usuarios";
$result = $conn->query($query);
$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Obtener el rol del usuario autenticado
$usuario_autenticado_rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.css">
    <script src="sidebar-loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        /* Contenedor principal con nuevo esquema de colores */
        .user-management-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #FFFFFF;
            /* Fondo blanco */
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            /* Borde gris claro */
        }

        /* Tabla de usuarios */
        .table-users {
            width: 100%;
            border-collapse: collapse;
        }

        .table-users th,
        .table-users td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
            /* Borde gris claro */
            color: #1F2937;
            /* Texto oscuro */
        }

        .table-users th {
            background-color: #F3F4F6;
            /* Fondo gris muy claro */
            font-weight: 600;
            color: #1F2937;
            /* Texto oscuro */
        }

        .table-users tr:hover {
            background-color: #F3F4F6;
            /* Fondo gris muy claro al hacer hover */
        }

        /* Botones de acción */
        .action-btn {
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .edit-btn {
            background-color: #2563EB;
            /* Azul vibrante */
            color: white;
        }

        .edit-btn:hover {
            background-color: #1D4ED8;
            /* Azul más oscuro al hover */
        }

        .delete-btn {
            background-color: #EF4444;
            /* Rojo */
            color: white;
        }

        .delete-btn:hover {
            background-color: #DC2626;
            /* Rojo más oscuro al hover */
        }

        .add-user-btn {
            background-color: #10B981;
            /* Verde esmeralda */
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-user-btn:hover {
            background-color: #059669;
            /* Verde más oscuro al hover */
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #FFFFFF;
            /* Fondo blanco */
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
        }

        .modal-title {
            color: #1F2937;
            /* Texto oscuro */
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .close-modal {
            color: #6B7280;
            /* Gris neutro */
            float: right;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #1F2937;
            /* Texto oscuro al hover */
        }

        /* Formulario */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1F2937;
            /* Texto oscuro */
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0.25rem;
            color: #1F2937;
            /* Texto oscuro */
        }

        .form-input:focus {
            outline: none;
            border-color: #2563EB;
            /* Azul vibrante */
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        /* Select personalizado */
        .form-select {
            width: 100%;
            padding: 0.5rem;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0.25rem;
            color: #1F2937;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231F2937' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 12px;
        }

        .form-select:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        /* Botones del formulario */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
            gap: 0.5rem;
        }

        .submit-btn {
            background-color: #2563EB !important;
            /* Azul vibrante */
            color: white;
            /* Texto gris oscuro */
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #1D4ED8;
            /* Azul más oscuro */
        }

        .cancel-btn {
            background-color: #F3F4F6;
            /* Gris muy claro */
            color: #1F2937;
            /* Texto oscuro */
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .cancel-btn:hover {
            background-color: #E5E7EB;
            /* Gris claro */
        }

        /* Asegurar que el contenedor de contraseña tenga el mismo ancho */
        .password-container {
            width: 100%;
        }

        /* Estilo específico para el input de contraseña */
        .password-input {
            width: 100%;
            padding: 0.5rem;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0.25rem;
            color: #1F2937;
            /* Texto oscuro */
        }

        /* Posición del ícono de mostrar contraseña */
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6B7280;
            /* Gris neutro */
            background: none;
            border: none;
            padding: 0;
        }

        /* Contenedor relativo para el input de contraseña */
        .password-input-container {
            position: relative;
            width: 100%;
        }


        /* Indicador de fortaleza de contraseña */
        .password-strength {
            height: 4px;
            background: #E5E7EB;
            margin-top: 0.5rem;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        .password-requirements {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6B7280;
            /* Gris neutro */
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .requirement-satisfied {
            color: #10B981;
            /* Verde esmeralda */
        }

        /* Fondo de la página */
        body {
            background-color: #F3F4F6 !important;
            /* Gris muy claro */
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
</head>

<body style="background-color: #1a202c;">
    <main class="main-content">
        <div class="user-management-container">
            <h1 class="text-2xl font-bold text-black mb-6">Administración de Usuarios</h1>

            <button id="addUserBtn" class="add-user-btn">+ Agregar Usuario</button>

            <div class="table-container">
                <table class="table-users">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                            <tr data-id="<?php echo $usuario['id_usuario']; ?>">
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td>
                                    <?php if ($usuario['rol'] !== 'Super'): ?>
                                        <button class="action-btn edit-btn"
                                            onclick="editUser(<?php echo $usuario['id_usuario']; ?>)">Editar</button>
                                    <?php endif; ?>
                                    <?php if ($usuario['rol'] !== 'Super' && $usuario_autenticado_rol === 'Super'): ?>
                                        <button class="action-btn delete-btn"
                                            onclick="confirmDelete(<?php echo $usuario['id_usuario']; ?>)">Eliminar</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para agregar/editar usuario -->
        <div id="userModal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 id="modalTitle" class="modal-title">Agregar Nuevo Usuario</h2>
                <form id="userForm">
                    <input type="hidden" id="userId">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-input" required>
                    </div>
                    <!-- Actualiza el campo de rol para usar select -->
                    <div class="form-group">
                        <label for="rol" class="form-label">Rol</label>
                        <select id="rol" name="rol" class="form-select" required>
                            <option value="Administrador">Administrador</option>
                            <option value="Tecnico">Técnico</option>
                        </select>
                    </div>
                    <div class="form-group password-container">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="contrasena" name="contrasena" class="password-input" required
                                oninput="checkPasswordStrength(this.value)">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="toggle-password">👁️</button>
                        </div>
                        <div class="password-strength">
                            <div id="passwordStrengthBar" class="password-strength-bar"></div>
                        </div>
                        <div class="password-requirements">
                            <div id="lengthReq" class="requirement">• Mínimo 8 caracteres</div>
                            <div id="upperReq" class="requirement">• Al menos una mayúscula</div>
                            <div id="numberReq" class="requirement">• Al menos un número</div>
                            <div id="specialReq" class="requirement">• Al menos un carácter especial</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancelar</button>
                        <button type="submit" class="submit-btn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>



        <script>
            // Función para mostrar/ocultar contraseña
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('contrasena');
                passwordInput.type = passwordInput.type === 'contrasena' ? 'text' : 'contrasena';
            }

            // Función para verificar fortaleza de contraseña
            function checkPasswordStrength(password) {
                const strengthBar = document.getElementById('passwordStrengthBar');
                let strength = 0;

                // Verificar requisitos
                const hasMinLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecialChar = /[^A-Za-z0-9]/.test(password);

                // Actualizar indicadores visuales
                document.getElementById('lengthReq').className = hasMinLength ? 'requirement requirement-satisfied' : 'requirement';
                document.getElementById('upperReq').className = hasUpperCase ? 'requirement requirement-satisfied' : 'requirement';
                document.getElementById('numberReq').className = hasNumber ? 'requirement requirement-satisfied' : 'requirement';
                document.getElementById('specialReq').className = hasSpecialChar ? 'requirement requirement-satisfied' : 'requirement';

                // Calcular fortaleza
                if (hasMinLength) strength += 1;
                if (hasUpperCase) strength += 1;
                if (hasNumber) strength += 1;
                if (hasSpecialChar) strength += 1;

                // Actualizar barra de fortaleza
                const strengthPercent = strength * 25;
                strengthBar.style.width = `${strengthPercent}%`;

                // Cambiar color según fortaleza
                if (strength <= 1) {
                    strengthBar.style.backgroundColor = '#ef4444'; // Rojo
                } else if (strength === 2) {
                    strengthBar.style.backgroundColor = '#f59e0b'; // Amarillo
                } else if (strength === 3) {
                    strengthBar.style.backgroundColor = '#3b82f6'; // Azul
                } else {
                    strengthBar.style.backgroundColor = '#10b981'; // Verde
                }
            }
        </script>

        <!-- Script para el manejo de usuarios -->
        <script>
            // Variables globales
            let currentAction = 'add'; // 'add' o 'edit'

            // Elementos del DOM
            const modal = document.getElementById('userModal');
            const addUserBtn = document.getElementById('addUserBtn');
            const closeModalBtn = document.querySelector('.close-modal');
            const userForm = document.getElementById('userForm');
            const modalTitle = document.getElementById('modalTitle');

            // Mostrar modal para agregar usuario
            addUserBtn.addEventListener('click', function () {
                currentAction = 'add';
                modalTitle.textContent = 'Agregar Nuevo Usuario';
                userForm.reset();
                document.getElementById('userId').value = '';
                document.getElementById('contrasena').required = true;
                modal.style.display = 'flex';
            });

            // Cerrar modal
            closeModalBtn.addEventListener('click', closeModal);

            // Función para cerrar modal
            function closeModal() {
                modal.style.display = 'none';
            }

            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Mostrar/ocultar contraseña
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('contrasena');
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            }

            // Función para encriptar contraseña con SHA-256
            function encryptPassword(password) {
                return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
            }

            // Editar usuario
            function editUser(userId) {
                currentAction = 'edit';
                modalTitle.textContent = 'Editar Usuario';
                document.getElementById('userId').value = userId;
                document.getElementById('contrasena').required = false;

                // Obtener datos del usuario
                fetch(`get_user.php?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('nombre').value = data.usuario.nombre;
                            document.getElementById('correo').value = data.usuario.correo;
                            document.getElementById('rol').value = data.usuario.rol;
                            modal.style.display = 'flex';
                        } else {
                            alert('Error al cargar datos del usuario');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar datos del usuario');
                    });
            }

            // Confirmar eliminación de usuario
            function confirmDelete(userId) {
                if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                    deleteUser(userId);
                }
            }

            // Eliminar usuario
            function deleteUser(userId) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: userId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Usuario eliminado correctamente');
                            // Eliminar fila de la tabla
                            document.querySelector(`tr[data-id="${userId}"]`).remove();
                        } else {
                            alert('Error al eliminar usuario: ' + (data.message || ''));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar usuario');
                    });
            }

            // Manejar envío del formulario
            userForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Mostrar indicador de carga
                const submitBtn = userForm.querySelector('.submit-btn');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Guardando...';

                // Obtener valores del formulario
                const userId = document.getElementById('userId').value;
                const nombre = document.getElementById('nombre').value;
                const correo = document.getElementById('correo').value;
                const rol = document.getElementById('rol').value;
                const contrasena = document.getElementById('contrasena').value;

                // Validación básica
                if (!nombre || !correo || !rol) {
                    alert('Por favor complete todos los campos obligatorios');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Guardar';
                    return;
                }

                // Validar formato de email
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                    alert('Por favor ingrese un correo electrónico válido');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Guardar';
                    return;
                }

                // Crear objeto con los datos
                const userData = {
                    id: userId,
                    nombre: nombre,
                    correo: correo,
                    rol: rol
                };

                // Solo agregar contraseña si es nuevo usuario o se cambió
                if (currentAction === 'add' || contrasena) {
                    userData.contrasena = encryptPassword(contrasena);
                }

                // Determinar la URL según la acción
                const url = currentAction === 'add' ? 'create_user.php' : 'update_user.php';

                // Enviar datos al servidor con mejor manejo de errores
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(userData)
                })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            console.error('Respuesta no es JSON:', response);
                            throw new TypeError("La respuesta no es JSON");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(currentAction === 'add' ? 'Usuario creado exitosamente' : 'Usuario actualizado exitosamente');
                            closeModal();
                            location.reload();
                        } else {
                            console.error('Error del servidor:', data);
                            alert('Error: ' + (data.message || 'Error desconocido'));
                        }
                    })
                    .catch(error => {
                        console.error('Error de fetch:', error);
                        alert('Error: ' + error.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Guardar';
                    });
            });
        </script>
    </main>
</body>

</html>