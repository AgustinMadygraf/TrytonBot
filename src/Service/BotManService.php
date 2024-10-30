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
        // Respuesta para mensajes específicos
        $this->botman->hears('hello|hola|hi', function (BotMan $bot) {
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
            $this->logger->info("Respuesta de saludo activada");
        });

        // Respuesta de fallback para mensajes no reconocidos
        $this->botman->fallback(function (BotMan $bot) {
            $bot->reply('No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?');
            $this->logger->info("Respuesta de fallback activada");
        });
    }

    public function handleRequest(array $data): array
    {
        $this->botman->hears($data['text'], function (BotMan $bot) use ($data) {
            $bot->reply('Mensaje recibido: ' . $data['text']);
        });
        $this->botman->listen();

        return ['BotMan está escuchando'];
    }
}
