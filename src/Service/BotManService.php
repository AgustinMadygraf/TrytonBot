<?php
// TrytonBot/src/Service/BotManService.php
namespace App\Service;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class BotManService
{
    private $logger;
    private $responsePatterns;

    public function __construct(array $responsePatterns, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->responsePatterns = $responsePatterns;
    }

    public function handleRequest(Request $request): array
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Instantiate BotMan with the request
        $botman = BotManFactory::create([], $request);

        // Collect replies in an array
        $replies = [];

        // Add middleware to collect replies
        $botman->middleware->sending(new ReplyCollector($replies));

        // Register response patterns
        foreach ($this->responsePatterns as $pattern) {
            $pattern->register($botman);
        }

        // Process the message
        $botman->listen();

        return $replies;
    }
}
