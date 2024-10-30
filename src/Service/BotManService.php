<?php
// TrytonBot/src/Service/BotManService.php

namespace App\Service;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class BotManService
{
    private $logger;
    private $responsePatterns;

    public function __construct(array $responsePatterns, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->responsePatterns = $responsePatterns;
        $this->logger->info("BotManService inicializado con patrones de respuesta", ['patterns' => $responsePatterns]);
    }

    public function handleRequest(Request $request): array
    {
        $this->logger->info("Iniciando el manejo de la solicitud en BotManService");

        // Cargar el driver de WebDriver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Crear instancia de BotMan sin configuración personalizada
        $this->logger->debug("Creando instancia de BotMan");
        $botman = BotManFactory::create([]);

        // Arreglo para recolectar respuestas
        $replies = [];

        // Agregar middleware para recolectar respuestas
        $this->logger->debug("Añadiendo middleware para recolectar respuestas");
        $botman->middleware->sending(new ReplyCollector($replies));

        // Registrar patrones de respuesta
        $this->logger->info("Registrando patrones de respuesta");
        foreach ($this->responsePatterns as $pattern) {
            $pattern->register($botman);
        }

        // Escuchar el mensaje recibido
        $this->logger->info("Iniciando escucha de mensajes en BotMan");
        $botman->listen();

        $this->logger->info("Escucha finalizada. Respuestas recolectadas", ['replies' => $replies]);

        return $replies;
    }
}
