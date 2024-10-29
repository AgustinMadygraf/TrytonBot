// TrutonBot/script.js
function sendMessage() {
    const input = document.getElementById("user-input");
    const messages = document.getElementById("messages");
    
    if (input.value.trim() !== "") {
        const userMessage = document.createElement("div");
        userMessage.textContent = "Tú: " + input.value;
        messages.appendChild(userMessage);

        // Aquí puedes agregar una lógica para enviar el mensaje al servidor de backend
        // e implementar la respuesta del chatbot

        input.value = "";  // Limpiar el campo de entrada
        messages.scrollTop = messages.scrollHeight;  // Auto-scroll
    }
}
