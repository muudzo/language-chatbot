<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shona Language Chatbot</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .chat-container {
            max-height: 500px;
            overflow-y: auto;
        }
        .user-message {
            background-color: #e2f5fe;
            border-radius: 15px 15px 0 15px;
            padding: 10px 15px;
            margin: 5px 0;
            max-width: 70%;
            align-self: flex-end;
        }
        .bot-message {
            background-color: #f0f0f0;
            border-radius: 15px 15px 15px 0;
            padding: 10px 15px;
            margin: 5px 0;
            max-width: 70%;
            align-self: flex-start;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Shona Language Chatbot</h1>
            
            <div class="mb-4">
                <label for="language" class="block text-gray-700 mb-2">Select Language:</label>
                <select id="language" class="w-full p-2 border rounded">
                    <option value="shona" selected>Shona</option>
                    <option value="ndebele">Ndebele</option>
                </select>
            </div>
            
            <div class="chat-container mb-4 flex flex-col" id="chat-messages">
                <div class="bot-message">
                    Hello! I'm your Shona language assistant. Ask me how to say something in Shona or teach me new phrases.
                </div>
            </div>
            
            <div class="flex">
                <input type="text" id="user-message" class="flex-grow p-2 border rounded-l" placeholder="Type your message...">
                <button id="send-button" class="bg-blue-500 text-white px-4 py-2 rounded-r">Send</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chat-messages');
            const userInput = document.getElementById('user-message');
            const sendButton = document.getElementById('send-button');
            const languageSelect = document.getElementById('language');
            
            // Set CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Handle sending messages
            function sendMessage() {
                const message = userInput.value.trim();
                if (message === '') return;
                
                // Add user message to chat
                const userMessageDiv = document.createElement('div');
                userMessageDiv.className = 'user-message self-end';
                userMessageDiv.textContent = message;
                messagesContainer.appendChild(userMessageDiv);
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Clear input
                userInput.value = '';
                
                // Get selected language
                const language = languageSelect.value;
                
                // Send to server
                fetch('{{ route("chatbot.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: message,
                        language: language
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Add bot response to chat
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.className = 'bot-message self-start';
                    botMessageDiv.textContent = data.response;
                    messagesContainer.appendChild(botMessageDiv);
                    
                    // Scroll to bottom
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bot-message self-start text-red-500';
                    errorDiv.textContent = 'Sorry, there was an error processing your request.';
                    messagesContainer.appendChild(errorDiv);
                });
            }
            
            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
            
            // Handle language switching
            languageSelect.addEventListener('change', function() {
                const language = languageSelect.value;
                fetch('{{ route("chatbot.language") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        language: language
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.className = 'bot-message self-start';
                    botMessageDiv.textContent = `Switched to ${data.language} language. How can I help you?`;
                    messagesContainer.appendChild(botMessageDiv);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>