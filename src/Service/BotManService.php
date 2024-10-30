<?php
// TrytonBot/src/Service/BotManService.php
namespace App\Service;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Psr\Log\LoggerInterface;

class BotManService
{
    private $botman;
    private $logger;
    private $responseMessages = [];

    public function __construct(LoggerInterface $logger)
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $this->botman = BotManFactory::create([]);
        $this->logger = $logger;
        $this->initializeBotResponses();
    }

    private function initializeBotResponses()
    {
        // Define responses for specific messages
        $this->botman->hears('hello|hola|hi', function (BotMan $bot) {
            $this->logger->info("Bot capturó el mensaje 'hello' o similar");
            $this->responseMessages[] = 'Hola! ¿En qué puedo ayudarte?';
        });

        // Fallback response for unrecognized messages
        $this->botman->fallback(function (BotMan $bot) {
            $this->logger->info("Bot capturó un mensaje pero no coincidió con 'hello'");
            $this->responseMessages[] = 'No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?';
        });
    }

    public function handleRequest(array $data): array
    {
        ob_start();
        $this->botman->hears($data['text'], function (BotMan $bot) use ($data) {
            $this->responseMessages[] = 'Mensaje recibido: ' . $data['text'];
        });
        $this->botman->listen();
        ob_clean(); // Limpia cualquier salida generada en el buffer

        return $this->responseMessages;
    }
}
