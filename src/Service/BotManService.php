<?php
// TrytonBot/src/Service/BotManService.php

namespace App\Service;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Psr\Log\LoggerInterface;

class BotManService
{
    private $logger;
    private $responsePatterns;

    public function __construct(array $responsePatterns, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->responsePatterns = $responsePatterns;

        // Verificar la inicialización y contenido de los patrones de respuesta
        $this->logger->info("BotManService inicializado con patrones de respuesta.");
        if (!empty($responsePatterns)) {
            foreach ($responsePatterns as $index => $pattern) {
                $this->logger->debug("Patrón de respuesta inyectado", [
                    'index' => $index,
                    'pattern_class' => get_class($pattern),
                    'pattern_data' => $pattern
                ]);
            }
        } else {
            $this->logger->warning("No se han inyectado patrones de respuesta en BotManService");
        }
    }

    public function handleRequest(): array
    {
        $this->logger->info("Iniciando el manejo de la solicitud en BotManService");

        // Confirmar el contenido de la solicitud
        $this->logger->debug("Contenido de la solicitud", ['request' => $_REQUEST]);

        // Verificar el driver y el mensaje
        $driver = $_REQUEST['driver'] ?? null;
        $message = $_REQUEST['message'] ?? null;
        $this->logger->info("Valores de driver y message", ['driver' => $driver, 'message' => $message]);

        // Cargar el driver de WebDriver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Verificar los drivers activos
        $this->logger->info("Drivers activos", ['drivers' => DriverManager::getAvailableDrivers()]);

        // Crear instancia de BotMan sin pasar el objeto Request
        $this->logger->debug("Creando instancia de BotMan");
        $botman = BotManFactory::create([]);

        // **Deshabilitar el envío automático de respuestas**
        $botman->setShouldSendResponse(false);

        // Arreglo para recolectar respuestas
        $replies = [];

        // Agregar middleware para recolectar respuestas
        $this->logger->debug("Añadiendo middleware para recolectar respuestas");
        $botman->middleware->sending(new ReplyCollector($replies, $this->logger));

        // Registrar patrones de respuesta
        $this->logger->info("Registrando patrones de respuesta");
        foreach ($this->responsePatterns as $pattern) {
            $pattern->register($botman);
        }

        // Escuchar el mensaje recibido
        $this->logger->info("Iniciando escucha de mensajes en BotMan");
        $botman->listen();

        // Logs después de botman->listen()
        $this->logger->info("Escucha finalizada. Respuestas recolectadas", ['replies' => $replies]);

        return $replies;
    }
}
