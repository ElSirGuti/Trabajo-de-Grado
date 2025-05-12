<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Asistente</title>
    <script src="sidebar-loader.js"></script>
    <link rel="stylesheet" href="CSS/estilosChatbot.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
    :root {
        --primary: #2563EB;         /* Azul vibrante */
        --primary-hover: #1D4ED8;    /* Azul más oscuro */
        --dark: #1F2937;            /* Gris oscuro elegante */
        --darker: #374151;          /* Gris un poco más claro */
        --light: #F3F4F6;           /* Gris claro para fondos */
        --text-light: #FFFFFF;       /* Texto blanco */
        --text-dark: #1F2937;       /* Texto oscuro */
        --text-muted: #6B7280;       /* Texto gris neutro */
        --success: #10B981;          /* Verde esmeralda */
        --danger: #EF4444;           /* Rojo para errores */
        --border: #E5E7EB;           /* Borde gris claro */
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light);
        color: var(--text-dark);
        margin: 0;
        display: flex;
        min-height: 100vh;
    }

    .chat-container {
        width: 100%;
        max-width: 800px;
        min-width: 500px;
        background-color: var(--text-light);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        height: 80vh;
        border: 1px solid var(--border);
    }

    .chat-header {
        background-color: var(--primary);
        color: var(--text-light);
        padding: 15px;
        text-align: center;
        font-weight: 600;
    }

    .chat-messages {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        background-color: var(--light);
    }

    .message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 18px;
        line-height: 1.4;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .user-message {
        align-self: flex-end;
        background-color: var(--primary);
        color: var(--text-light);
        border-bottom-right-radius: 5px;
    }

    .bot-message {
        align-self: flex-start;
        background-color: var(--text-light);
        color: var(--text-dark);
        border: 1px solid var(--border);
        border-bottom-left-radius: 5px;
    }

    .chat-input {
        display: flex;
        padding: 15px;
        border-top: 1px solid var(--border);
        background-color: var(--text-light);
    }

    #user-input {
        flex-grow: 1;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 20px;
        background-color: var(--text-light);
        color: var(--text-dark);
        outline: none;
    }

    #user-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    #send-button {
        margin-left: 10px;
        padding: 12px 20px;
        background-color: var(--primary);
        color: var(--text-light);
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    #send-button:hover {
        background-color: var(--primary-hover);
    }

    #send-button:disabled {
        background-color: var(--text-muted);
        cursor: not-allowed;
    }

    /* Estilos para el Markdown renderizado */
    .bot-message .markdown-rendered h1,
    .bot-message .markdown-rendered h2,
    .bot-message .markdown-rendered h3 {
        color: var(--primary);
        margin-top: 0.5em;
        margin-bottom: 0.3em;
    }

    .bot-message .markdown-rendered a {
        color: var(--primary);
        text-decoration: underline;
    }

    .bot-message .markdown-rendered code {
        background-color: var(--light);
        color: var(--danger);
        padding: 2px 4px;
        border-radius: 4px;
    }

    .bot-message .markdown-rendered pre {
        background-color: var(--light);
        padding: 10px;
        border-radius: 6px;
        overflow-x: auto;
    }

    /* Tablas mejoradas */
    .table-container {
        max-width: 100%;
        overflow-x: auto;
        margin: 1em 0;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .markdown-rendered table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--text-light);
        color: var(--text-dark);
    }

    .markdown-rendered th {
        background-color: var(--primary);
        color: var(--text-light);
        padding: 12px 15px;
        text-align: left;
    }

    .markdown-rendered td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--border);
    }

    .markdown-rendered tr:hover {
        background-color: rgba(37, 99, 235, 0.05);
    }

    /* Notificaciones */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 16px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .notification.info {
        background-color: var(--primary);
    }

    .notification.error {
        background-color: var(--danger);
    }

    /* Typing indicator */
    .typing-indicator {
        display: flex;
        gap: 5px;
        padding: 12px 16px;
        background-color: var(--text-light);
        border-radius: 18px;
        width: fit-content;
        border: 1px solid var(--border);
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--primary);
        animation: typingAnimation 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingAnimation {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-5px);
        }
    }
</style>
    <script>
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
    </script>
</head>

<body>
    <main class="main-content"></main>
    <div class="chat-container" style="margin-top: 20px; margin-right: 10px;">
        <div class="chat-header">
            <h2>Chatbot MecaBot</h2>
            <p>Generado por IA, usar como referencia</p>
        </div>

        <div class="chat-messages" id="chat-messages">
            <!-- Mensajes aparecerán aquí -->
        </div>

        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Escribe tu mensaje..." autocomplete="off">
            <button id="send-button">Enviar</button>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const sendButton = document.getElementById('send-button');
        const API_URL = 'http://localhost/Mantenimiento/api/llama.php';

        // Configuración de marked.js (opcional)
        marked.setOptions({
            breaks: true,
            gfm: true
        });

        // Función para extraer tareas del mensaje del bot
        function extractTasksFromMessage(content) {
            // Buscar la sección "Tareas Individuales"
            const tasksSectionIndex = content.indexOf('Tareas Individuales');
            if (tasksSectionIndex === -1) return null;

            // Extraer el contenido después de "Tareas Individuales"
            const tasksText = content.substring(tasksSectionIndex);

            // Expresión regular para encontrar las tareas
            const taskRegex = /Fecha:\s*(\d{4}-\d{2}-\d{2}),\s*Actividad:\s*([^,]+),\s*Equipo:\s*([^\n]+)/g;
            let matches;
            let tasks = [];

            while ((matches = taskRegex.exec(tasksText)) !== null) {
                tasks.push({
                    fecha: matches[1],
                    actividad: matches[2].trim(),
                    equipo: matches[3].trim()
                });
            }

            return tasks.length > 0 ? tasks : null;
        }

        // Función para guardar tareas en la base de datos
        async function saveTasksToDatabase(tasks) {
            try {
                const response = await fetch('api/save_tasks.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ tasks })
                });

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error(`Respuesta no JSON: ${text}`);
                }

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'Error al guardar tareas');
                }

                console.log('Tareas guardadas:', result);
                return result;
            } catch (error) {
                console.error('Error completo al guardar tareas:', error);
                throw new Error(`Error al comunicarse con el servidor: ${error.message}`);
            }
        }

        // Modificación en la función addMessage para guardar las tareas
        function addMessage(role, content) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${role}-message`;

            if (role === 'bot') {
                // Procesar el contenido para tablas más bonitas
                const processedContent = enhanceTables(content);

                const renderedContent = document.createElement('div');
                renderedContent.className = 'markdown-rendered';
                renderedContent.innerHTML = marked.parse(processedContent);

                // Aplicar estilos especiales a las tablas después de renderizar
                setTimeout(() => {
                    const tables = renderedContent.querySelectorAll('table');
                    tables.forEach(table => {
                        const container = document.createElement('div');
                        container.className = 'table-container';
                        table.parentNode.insertBefore(container, table);
                        container.appendChild(table);
                    });
                }, 50);

                messageDiv.appendChild(renderedContent);

                // Extraer y guardar tareas
                const tasks = extractTasksFromMessage(content);
                if (tasks) {
                    console.log('Tareas individuales detectadas:', tasks);

                    // Guardar en la base de datos
                    saveTasksToDatabase(tasks)
                        .then(result => {
                            showNotification(`Se guardaron ${result.saved} tareas`);
                        })
                        .catch(error => {
                            showNotification('Error al guardar tareas', 'error');
                            console.error('Error:', error);
                        });
                }
            } else {
                messageDiv.textContent = content;
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Función para mostrar notificaciones (opcional)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            notification.style.position = 'fixed';
            notification.style.bottom = '20px';
            notification.style.right = '20px';
            notification.style.padding = '10px 15px';
            notification.style.background = type === 'info' ? '#4299e1' : '#e53e3e';
            notification.style.color = 'white';
            notification.style.borderRadius = '4px';
            notification.style.zIndex = '1000';
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Añade este estilo al head para las notificaciones
        const style = document.createElement('style');
        style.textContent = `
        .notification {
            transition: opacity 0.5s ease;
        }
        .notification.info {
            background-color: #4299e1;
        }
        .notification.error {
            background-color: #e53e3e;
        }
        `;
        document.head.appendChild(style);

        // Función para mejorar el formato de las tablas
        function enhanceTables(content) {
            // 1. Asegurar que las tablas tengan formato consistente
            let enhanced = content.replace(/\|\s*\n/g, '|\n'); // Eliminar espacios antes de saltos

            // 2. Mejorar la legibilidad de la leyenda
            enhanced = enhanced.replace(
                /Leyenda:[\s\S]*?(NPR \(Número de Prioridad de Riesgo\): S x O x D)/g,
                '<div class="table-legend">$&</div>'
            );

            // 3. Asegurar separadores de columnas correctos
            enhanced = enhanced.replace(
                /^(\|.*\|)\n\|([-:| ]+)\|/gm,
                (match, headers, separators) => {
                    const columns = headers.split('|').length - 1;
                    return `${headers}\n|${' --- |'.repeat(columns).slice(0, -1)}`;
                }
            );

            return enhanced;
        }

        // Función para corregir tablas Markdown
        function fixMarkdownTables(content) {
            return content
                // Corregir separadores de filas mal formados
                .replace(/\|[-]+\|/g, match => {
                    const count = (match.match(/\|/g) || []).length;
                    return '|' + ' --- |'.repeat(count - 1);
                })
                // Eliminar filas completamente vacías
                .replace(/\|\s*---\s*\|\s*---\s*\|/g, '')
                // Eliminar separadores redundantes
                .replace(/\n[-]+\n/g, '\n');
        }

        // Función para formatear tablas con pipes
        function formatTables(content) {
            // Detectar tablas con formato de tabs
            if (content.includes('\t')) {
                const lines = content.split('\n');
                let formattedContent = '';
                let inTable = false;

                for (const line of lines) {
                    if (line.includes('\t')) {
                        // Es una fila de tabla
                        if (!inTable) {
                            // Primera fila de la tabla (encabezados)
                            const headers = line.split('\t');
                            formattedContent += '\n' + headers.join(' | ') + '\n';
                            formattedContent += headers.map(() => '---').join(' | ') + '\n';
                            inTable = true;
                        } else {
                            // Filas de datos
                            formattedContent += line.split('\t').join(' | ') + '\n';
                        }
                    } else {
                        // No es una fila de tabla
                        if (inTable) {
                            inTable = false;
                            formattedContent += '\n'; // Espacio después de la tabla
                        }
                        formattedContent += line + '\n';
                    }
                }
                return formattedContent;
            }
            return content;
        }

        // Función de typing indicator modificada
        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot-message typing-indicator';
            typingDiv.id = 'typing-indicator';
            typingDiv.innerHTML = `
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideTyping() {
            const typing = document.getElementById('typing-indicator');
            if (typing) typing.remove();
        }

        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            addMessage('user', message);
            userInput.value = '';
            sendButton.disabled = true;
            showTyping();

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ prompt: message })
                });

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('La respuesta del servidor no es JSON válido');
                }

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Error en el servidor');
                }

                hideTyping();

                // Renderizar Markdown directamente (sin sanitización)
                const renderedHtml = marked.parse(data.response || 'No hay respuesta');
                addMessage('bot', renderedHtml);

            } catch (error) {
                hideTyping();
                addMessage('bot', `⚠️ Error: ${error.message}`); // Mensaje de error en texto plano
                console.error('Error en sendMessage:', error);
            } finally {
                sendButton.disabled = false;
                userInput.focus();
            }
        }

        // Event listeners
        sendButton.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Mensaje inicial con Markdown
        window.onload = () => {
            userInput.focus();
            addMessage('bot', '¡Hola! Soy tu asistente **MecaBot**. \n\n¿En qué puedo ayudarte hoy?');
        };
    </script>
</body>

</html>