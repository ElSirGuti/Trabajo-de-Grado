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
            --primary: #4299e1;
            --dark: #1a202c;
            --darker: #2d3748;
            --light: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark);
            color: var(--light);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        .chat-container {
            width: 100%;
            max-width: 800px;
            min-width: 500px;
            background-color: var(--darker);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 80vh;
        }

        .chat-header {
            background-color: var(--primary);
            color: white;
            padding: 15px;
            text-align: center;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.4;
        }

        .user-message {
            align-self: flex-end;
            background-color: var(--primary);
            color: white;
            border-bottom-right-radius: 5px;
        }

        .bot-message {
            align-self: flex-start;
            background-color: rgba(66, 153, 225, 0.2);
            border-bottom-left-radius: 5px;
        }

        .chat-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #user-input {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #4a5568;
            border-radius: 20px;
            background-color: var(--dark);
            color: var(--light);
            outline: none;
        }

        #send-button {
            margin-left: 10px;
            padding: 12px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        #send-button:hover {
            background-color: #3182ce;
        }

        #send-button:disabled {
            background-color: #4a5568;
            cursor: not-allowed;
        }

        .typing-indicator {
            display: flex;
            gap: 5px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #a0aec0;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }

        @keyframes typingAnimation {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-5px);
            }
        }

        /* Añade estos estilos para el Markdown renderizado */
        .bot-message markdown-rendered {
            display: block;
        }

        .bot-message markdown-rendered h1,
        .bot-message markdown-rendered h2,
        .bot-message markdown-rendered h3 {
            color: var(--primary);
            margin-top: 0.5em;
            margin-bottom: 0.3em;
        }

        .bot-message markdown-rendered ul,
        .bot-message markdown-rendered ol {
            padding-left: 20px;
            margin: 0.5em 0;
        }

        .bot-message markdown-rendered li {
            margin-bottom: 0.3em;
        }

        .bot-message markdown-rendered strong {
            color: var(--primary);
        }

        .bot-message markdown-rendered em {
            color: #a0aec0;
        }

        /* Estilos para el Markdown en mensajes del bot */
        .bot-message h1,
        .bot-message h2,
        .bot-message h3 {
            color: rgb(255, 255, 255);
            margin: 0.5em 0;
        }

        .bot-message ul,
        .bot-message ol {
            padding-left: 20px;
            margin: 0.5em 0;
        }

        .bot-message li {
            margin-bottom: 0.3em;
        }

        .bot-message strong {
            color: rgb(255, 255, 255);
        }

        .bot-message em {
            font-style: italic;
            color: #a0aec0;
        }

        .bot-message a {
            color: #63b3ed;
            text-decoration: underline;
        }

        .bot-message code {
            background: #2d3748;
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
        }

        .bot-message pre {
            background: #2d3748;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <main class="main-content"></main>
        <div class="chat-container" style="margin-top: 20px; margin-right: 10px;">
            <div class="chat-header">
                <h2>Chatbot LLaMA 3.2B</h2>
                <p>Modelo ejecutándose localmente</p>
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

            // Función modificada para renderizar Markdown
            function addMessage(role, content) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${role}-message`;

                if (role === 'bot') {
                    // Para mensajes del bot, renderiza Markdown
                    const renderedContent = document.createElement('div');
                    renderedContent.className = 'markdown-rendered';
                    renderedContent.innerHTML = marked.parse(content);
                    messageDiv.appendChild(renderedContent);
                } else {
                    // Para mensajes del usuario, texto normal
                    messageDiv.textContent = content;
                }

                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
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
                addMessage('bot', '¡Hola! Soy tu asistente con **LLaMA 3.2B**. \n\n¿En qué puedo ayudarte hoy?');
            };
        </script>
</body>

</html>