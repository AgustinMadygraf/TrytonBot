<?php
// TrytonBot/src/Service/ResponsePatternInterface.php
namespace App\Service;

use BotMan\BotMan\BotMan;

interface ResponsePatternInterface
{
    public function register(BotMan $botman): void;
}
