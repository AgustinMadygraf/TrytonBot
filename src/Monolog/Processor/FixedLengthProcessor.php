<?php

// src/Monolog/Processor/FixedLengthProcessor.php

namespace App\Monolog\Processor;

class FixedLengthProcessor
{
    public function __invoke(array $record): array
    {
        // Aseguramos que el campo channel.level_name tenga exactamente 15 caracteres
        $channelLevel = $record['channel'] . '.' . $record['level_name'];
        $record['extra']['channel_level'] = str_pad($channelLevel, 15, ' ', STR_PAD_RIGHT);

        return $record;
    }
}
