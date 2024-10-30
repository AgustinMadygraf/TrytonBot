<?php
// src/Service/GreetingPattern.php

namespace App\Service;

use BotMan\BotMan\BotMan;
use Psr\Log\LoggerInterface;

class GreetingPattern implements ResponsePatternInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register(BotMan $botman): void
    {
        $this->logger->info("Registrando el patrón de saludo en BotMan");

        // Ajusta el patrón para coincidir correctamente
        $botman->hears('hello|hola|hi', function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $this->logger->info("Patrón de saludo activado", ['message' => $message]);

            $reply = '¡Hola! ¿En qué puedo ayudarte?';
            $this->logger->debug("Enviando respuesta de saludo", ['reply' => $reply]);

            $bot->reply($reply);
        });
    }
}
