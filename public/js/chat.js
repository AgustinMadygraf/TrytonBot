// public/js/chat.js

document.getElementById('send-btn').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent the form from submitting
    let userMessage = document.getElementById('user-input').value;
    console.log('Mensaje del usuario:', userMessage); // Debug
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
            console.log('Respuesta del servidor:', responseMessages); // Debug
            if (responseMessages && responseMessages.length > 0) {
                displayMessage('Bot', responseMessages[0]); // Muestra el primer mensaje del array
            } else {
                displayMessage('Bot', 'No se recibió respuesta del servidor.');
            }
        })
        .catch(error => console.error('Error en handleUserMessage:', error));
}

// Función que se encarga solo de la comunicación con el servidor
function sendMessageToServer(message) {
    console.log('Mensaje a enviar al servidor:', message); // Debug
    return fetch('/bot', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: message })
    })
    .then(response => {
        console.log('Estado de la respuesta:', response.status); // Debug del estado HTTP
        return response.text(); // Obtener texto sin procesar
    })
    .then(text => {
        try {
            const data = JSON.parse(text); // Intenta parsear JSON
            console.log('Datos parseados:', data); // Debug
            return data.messages; // Retorna los mensajes si el parseo fue exitoso
        } catch (error) {
            console.error('Error al parsear JSON:', error, 'Respuesta recibida:', text); // Debug detallado
            throw new Error('La respuesta no es JSON válido'); // Lanzar error si el JSON no es válido
        }
    });
}
