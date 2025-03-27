<?php
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
        .user-management-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #2d3748;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
        }

        .table-users {
            width: 100%;
            border-collapse: collapse;
        }

        .table-users th,
        .table-users td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #4a5568;
            color: #e2e8f0;
        }

        .table-users th {
            background-color: #4a5568;
            font-weight: 600;
        }

        .table-users tr:hover {
            background-color: #4a5568;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .edit-btn {
            background-color: #3b82f6;
            color: white;
        }

        .delete-btn {
            background-color: #ef4444;
            color: white;
        }

        .add-user-btn {
            background-color: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        /* Modal styles */
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
            background-color: #2d3748;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
        }

        .modal-title {
            color: white;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: white;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e2e8f0;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem;
            background-color: #4a5568;
            border: 1px solid #4a5568;
            border-radius: 0.25rem;
            color: black;
            /* Texto negro por defecto */
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .submit-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #2563eb;
        }

        .cancel-btn {
            background-color: #4a5568;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            margin-right: 1rem;
        }

        .cancel-btn:hover {
            background-color: #2d3748;
        }

        /* Estilo para el select */
        .form-select {
            width: 100%;
            padding: 0.5rem;
            background-color: #4a5568;
            border: 1px solid #4a5568;
            border-radius: 0.25rem;
            color: white;
            /* Texto blanco en select */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 12px;
        }

        /* Estilo mejorado para el campo de contraseña */
        .password-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .password-input {
            width: 100%;
            padding: 0.5rem;
            padding-right: 2.5rem;
            background-color: #4a5568;
            border: 1px solid #4a5568;
            border-radius: 0.25rem;
            color: black;
            /* Texto negro en password */
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
        }

        .password-strength {
            height: 4px;
            background: #e2e8f0;
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
            color: #a0aec0;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .requirement-satisfied {
            color: #48bb78;
        }
    </style>
</head>

<body style="background-color: #1a202c;">
    <main class="main-content">
        <div class="user-management-container">
            <h1 class="text-2xl font-bold text-white mb-6">Administración de Usuarios</h1>

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
                                    <button class="action-btn edit-btn"
                                        onclick="editUser(<?php echo $usuario['id_usuario']; ?>)">Editar</button>
                                    <button class="action-btn delete-btn"
                                        onclick="confirmDelete(<?php echo $usuario['id_usuario']; ?>)">Eliminar</button>
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
                        <input type="password" id="contrasena" name="contrasena" class="password-input" required
                            oninput="checkPasswordStrength(this.value)">
                        <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
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
                const toggleIcon = document.querySelector('.toggle-password');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.textContent = '👁️';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.textContent = '👁️';
                }
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
                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new TypeError("La respuesta no es JSON");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(currentAction === 'add' ? 'Usuario creado exitosamente' : 'Usuario actualizado exitosamente');
                            closeModal();
                            location.reload(); // Recargar la página para ver los cambios
                        } else {
                            throw new Error(data.message || 'Error desconocido');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
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