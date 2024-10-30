<?php
// TrytonBot/src/Service/FallbackPattern.php
namespace App\Service;

use BotMan\BotMan\BotMan;
use Psr\Log\LoggerInterface;

class FallbackPattern implements ResponsePatternInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register(BotMan $botman): void
    {
        $botman->fallback(function (BotMan $bot) {
            $this->logger->info("Bot capturó un mensaje pero no coincidió con 'hello'");
            $bot->reply('No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?');
        });
    }
}
