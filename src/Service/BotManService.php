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

    public function __construct(LoggerInterface $logger)
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $this->botman = BotManFactory::create([]);
        $this->logger = $logger;
        $this->initializeBotResponses();
    }

    private function initializeBotResponses()
    {
        // Definir respuestas específicas
        $this->botman->hears('hello|hola|hi', function (BotMan $bot) {
            $this->logger->info("Bot capturó el mensaje 'hello' o similar");
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });

        // Definir respuesta predeterminada
        $this->botman->fallback(function (BotMan $bot) {
            $this->logger->info("Bot capturó un mensaje pero no coincidió con 'hello'");
            $bot->reply('No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?');
        });
    }

    public function handleRequest(array $data): array
    {
        ob_start();
        $this->botman->hears($data['text'], function (BotMan $bot) use ($data) {
            $bot->reply('Mensaje recibido: ' . $data['text']);
        });
        ob_end_clean();

        return ['BotMan está escuchando'];
    }
}
