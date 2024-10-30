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
        $botman->hears('hello|hola|hi', function (BotMan $bot) {
            $this->logger->info("Bot captured a greeting message");
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });
    }
}
