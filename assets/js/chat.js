// assets/js/chat.js

document.getElementById('send-btn').addEventListener('click', function (event) {
    event.preventDefault();
    const userMessage = document.getElementById('user-input').value;
    displayMessage('Usuario', userMessage);
    handleUserMessage(userMessage);
    document.getElementById('user-input').value = '';
});

function displayMessage(sender, message) {
    const chat = document.getElementById('chat');
    chat.innerHTML += `<p><strong>${sender}:</strong> ${message}</p>`;
    chat.scrollTop = chat.scrollHeight;
}

async function handleUserMessage(message) {
    try {
        const response = await fetch('/bot', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: message })
        });
        const data = await response.json();
        const responseMessage = data.messages ? data.messages[0] : 'No se recibió respuesta del servidor.';
        displayMessage('Bot', responseMessage);
    } catch (error) {
        console.error('Error al enviar el mensaje:', error);
        displayMessage('Bot', 'Ocurrió un error al procesar tu mensaje.');
    }
}
