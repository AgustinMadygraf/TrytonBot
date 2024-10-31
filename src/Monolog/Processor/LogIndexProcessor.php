<?php
// TrytonBot/src/Monolog/Processor/LogIndexProcessor.php
namespace App\Monolog\Processor;

class LogIndexProcessor
{
    private static $index = 0;

    public function __invoke(array $record)
    {
        self::$index++;
        $record['extra']['index'] = self::$index;

        return $record;
    }
}
