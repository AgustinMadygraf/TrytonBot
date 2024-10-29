# Proyecto TrytonBot - Tareas Pendientes

### 1. Crear una Rama de Desarrollo
   - [x] Crear la rama `dev` para trabajar en el entorno de desarrollo.
     - Ejecuta `git checkout -b dev` para crear y cambiar a la nueva rama `dev`.

### 2. Instalar BotMan mediante Composer
   - [x] Instalar BotMan en el proyecto usando Composer:
     - Ejecuta `composer require botman/botman`
     - Ejecuta `composer require botman/driver-web`

### 3. Crear Controlador para el Bot
   - [x] Crear un controlador en Symfony para manejar las solicitudes del bot.
   - [x] Definir un endpoint específico (por ejemplo, `/bot`) en el controlador para procesar los mensajes.

### 4. Configurar Rutas en Symfony
   - [x] Definir la ruta del bot en el archivo de configuración de rutas (`config/routes.yaml`) o usando anotaciones en el controlador.
   - [ ] Asegurarse de que la ruta esté configurada para responder a las solicitudes de BotMan.

### 5. Configurar Middleware Opcionales para NLP
   - [ ] Si es necesario, instalar y configurar middleware para un servicio externo de NLP (como Dialogflow, wit.ai, etc.).
   - [ ] Configurar tokens y claves necesarias en el archivo `.env`.

### 6. Crear las Lógicas de Respuesta de BotMan
   - [ ] Definir los patrones de escucha de BotMan (intenciones y respuestas).
   - [ ] Definir respuestas a comandos y preguntas comunes para la interacción básica del bot.

### 7. Crear Vistas para el Chat
   - [x] Crear archivo de plantilla Twig en `templates/bot/index.html.twig` para la interfaz del chat.
   - [ ] Agregar contenedor de mensajes y campo de entrada para la interacción del usuario.

### 8. Configurar Webpack Encore para Estilos y Scripts de BotMan
   - [ ] Verificar inclusión de estilos y scripts de BotMan en Webpack.
   - [ ] Confirmar que las dependencias necesarias (Bootstrap, estilos personalizados) estén incluidas.

### 9. Instalar y Configurar NGROK para Pruebas Públicas
   - [ ] Instalar y ejecutar **NGROK** para exponer el servidor local.
   - [ ] Ejecutar `ngrok http <puerto_de_tu_servidor>` y obtener la URL pública.
   - [ ] Configurar la URL en servicios externos (si se usa con integraciones como Facebook Messenger o Telegram).

### 10. Pruebas y Ajustes Finales
   - [ ] Probar el bot en el entorno de desarrollo y verificar el funcionamiento correcto de respuestas e integraciones.
   - [ ] Mergear los cambios de la rama `dev` a `main` y preparar para el despliegue en producción.

