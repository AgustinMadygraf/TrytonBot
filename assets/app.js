// Tryton/assets/app.js
import './styles/app.scss';
import './bootstrap';

// Define la función sendMessage y asígnala al objeto global
function sendMessage() {
    // Tu lógica aquí
    console.log("Mensaje enviado");
}

// Asegura que sendMessage esté disponible globalmente
window.sendMessage = sendMessage;
