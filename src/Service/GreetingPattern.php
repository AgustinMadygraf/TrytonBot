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

        // Modificar el patrón para permitir coincidencias parciales
        $botman->hears('hello|hola|hi', function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $this->logger->info("Mensaje recibido para coincidencia de saludo", ['message' => $message]);

            // Verificar si la coincidencia fue exitosa
            if (preg_match('/hello|hola|hi/i', $message)) {
                $this->logger->info("Patrón de saludo activado", ['message' => $message]);

                // Mensaje de respuesta
                $reply = 'Hola! ¿En qué puedo ayudarte?';
                $this->logger->debug("Enviando respuesta de saludo", ['reply' => $reply]);

                $bot->reply($reply);
            } else {
                $this->logger->warning("El mensaje no coincide con el patrón de saludo", ['message' => $message]);
            }
        });
    }
}
