<?php
// TrytonBot/src/Service/GreetingPattern.php
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

        // Incluir delimitadores y modificador 'i' para coincidencia insensible a mayúsculas
        $botman->hears('/hello|hola|hi/i', function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $this->logger->info("Patrón de saludo activado", ['message' => $message]);

            // Mensaje de respuesta
            $reply = '¡Hola! ¿En qué puedo ayudarte?';
            $this->logger->debug("Enviando respuesta de saludo", ['reply' => $reply]);

            $bot->reply($reply);
        });
    }
}
