### 1. Crear una Rama de Desarrollo
   - Antes de realizar cualquier cambio, crea una rama llamada `dev` para trabajar en el entorno de desarrollo.
     - Ejecuta `git checkout -b dev` para crear y cambiar a la nueva rama `dev`.

### 2. Instalar BotMan mediante Composer
   - Usa Composer para instalar BotMan en el proyecto:
     - `composer require botman/botman`
     - `composer require botman/driver-web`

### 3. Crear Controlador para el Bot
   - Dentro de Symfony, crea un controlador dedicado a manejar las solicitudes del bot.
   - Define un endpoint específico (por ejemplo, `/botman`) para recibir y procesar los mensajes.

### 4. Configurar Rutas en Symfony
   - Define la ruta del bot en el archivo de configuración de rutas (`config/routes.yaml`) o directamente en el controlador usando anotaciones.
   - Asegúrate de que la ruta esté configurada para responder a las solicitudes de BotMan.

### 5. Configurar Middleware Opcionales para NLP
   - Si planeas usar un servicio externo de NLP (como Dialogflow, wit.ai, o uno local), instala y configura el middleware correspondiente.
   - Configura los tokens y claves necesarias en el archivo `.env`.

### 6. Crear las Lógicas de Respuesta de BotMan
   - Define los patrones de escucha de BotMan, como las intenciones y respuestas.
   - Define respuestas a los comandos y preguntas más comunes, para que el bot pueda interactuar con los usuarios de manera básica.

### 7. Crear Vistas para el Chat
   - Crea un archivo de plantilla Twig en `templates/` para la interfaz de usuario del chat.
   - Incluye un contenedor para mostrar los mensajes y un campo de entrada para que el usuario envíe mensajes.

### 8. Configurar Webpack Encore para Estilos y Scripts de BotMan
   - Si usas Webpack Encore, asegúrate de que los estilos y scripts de BotMan se incluyan en el build de Webpack.
   - Asegúrate de que las dependencias necesarias (como Bootstrap o estilos personalizados) estén en el archivo principal.

### 9. Instalar y Configurar NGROK para Pruebas Públicas
   - Si necesitas pruebas públicas, instala y ejecuta **NGROK** para exponer tu servidor local.
   - Ejecuta `ngrok http <puerto_de_tu_servidor>` y obtén la URL pública.
   - Configura la URL en los servicios externos si usas integraciones como Facebook Messenger o Telegram.

### 10. Pruebas y Ajustes Finales
   - Prueba el bot en el entorno de desarrollo y asegúrate de que todas las respuestas e integraciones funcionan correctamente.
   - Cuando todo esté listo, mergea los cambios de la rama `dev` a `main` y despliega en producción.
