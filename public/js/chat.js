// public/js/chat.js

document.getElementById('send-btn').addEventListener('click', function () {
    let userMessage = document.getElementById('user-input').value;
    displayMessage('Usuario', userMessage);
    handleUserMessage(userMessage);
    document.getElementById('user-input').value = '';
});

// Función que maneja la visualización del mensaje en el chat
function displayMessage(sender, message) {
    let chat = document.getElementById('chat');
    chat.innerHTML += `<p><strong>${sender}:</strong> ${message}</p>`;
    chat.scrollTop = chat.scrollHeight;
}

// Nueva función que maneja el envío del mensaje al servidor
function handleUserMessage(message) {
    sendMessageToServer(message)
        .then(responseMessages => {
            if (responseMessages && responseMessages.length > 0) {
                displayMessage('Bot', responseMessages[0]); // Muestra el primer mensaje del array
            } else {
                displayMessage('Bot', 'No se recibió respuesta del servidor.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función que se encarga solo de la comunicación con el servidor
function sendMessageToServer(message) {
    return fetch('/bot', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: message }) // Cambiado a 'text' en lugar de 'message'
    })
    .then(response => response.json())
    .then(data => data.messages)
}
