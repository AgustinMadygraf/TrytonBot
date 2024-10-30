<?php
// TrytonBot/src/Service/ReplyCollector.php

namespace App\Service;

use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Psr\Log\LoggerInterface;

class ReplyCollector implements Sending
{
    private $replies;
    private $logger;

    public function __construct(array &$replies, LoggerInterface $logger)
    {
        $this->replies = &$replies;
        $this->logger = $logger;
    }

    public function sending($payload, $next, BotMan $bot)
    {
        if ($payload instanceof OutgoingMessage) {
            $messageText = $payload->getText();
            $this->replies[] = $messageText;

            // Loggeamos el mensaje que se está recopilando
            $this->logger->info("Recopilando respuesta en ReplyCollector", ['message' => $messageText]);
        }
        return $next($payload);
    }
}
