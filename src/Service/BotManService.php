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

        // Cargar el driver y verificar
        $this->logger->debug("Cargando driver de WebDriver");
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Configuración e instancia de BotMan
        $config = [];
        $config['request'] = $request;
        $this->logger->debug("Creando instancia de BotMan con la configuración", ['config' => $config]);
        $botman = BotManFactory::create($config);

        // Arreglo para recolectar respuestas
        $replies = [];

        // Agregar middleware para recolectar respuestas
        $this->logger->debug("Añadiendo middleware para recolectar respuestas");
        $botman->middleware->sending(new ReplyCollector($replies));

        // Registrar patrones de respuesta y loguear cada patrón
        $this->logger->info("Registrando patrones de respuesta");
        foreach ($this->responsePatterns as $pattern) {
            $this->logger->debug("Registrando patrón", ['pattern' => $pattern]);
            $pattern->register($botman);
        }

        // Procesar el mensaje y escuchar
        $this->logger->info("Iniciando escucha de mensajes en BotMan");
        $botman->listen();

        $this->logger->info("Escucha finalizada. Respuestas recolectadas", ['replies' => $replies]);

        return $replies;
    }
}
