<?php
// TrytonBot/src/Service/ReplyCollector.php
namespace App\Service;

use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

class ReplyCollector implements Sending
{
    private $replies;

    public function __construct(array &$replies)
    {
        $this->replies = &$replies;
    }

    public function sending($payload, $next, BotMan $bot)
    {
        if ($payload instanceof OutgoingMessage) {
            $this->replies[] = $payload->getText();
        }
        return $next($payload);
    }
}
