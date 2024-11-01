<?php

// src/Monolog/Processor/FixedLengthProcessor.php

namespace App\Monolog\Processor;

class FixedLengthProcessor
{
    public function __invoke(array $record): array
    {
        // Formatear cada campo con una longitud fija
        $record['extra']['index'] = str_pad($record['extra']['index'], 3, ' ', STR_PAD_RIGHT);
        
        $channelLevel = $record['channel'] . '.' . $record['level_name'];
        $record['extra']['channel_level'] = str_pad($channelLevel, 12, ' ', STR_PAD_RIGHT);

        if (isset($record['extra']['file']) && isset($record['extra']['line'])) {
            $fileLine = sprintf('%s:%s', $record['extra']['file'], $record['extra']['line']);
            $record['extra']['file_line'] = str_pad(substr($fileLine, 0, 22), 22, ' ', STR_PAD_RIGHT);
        }

        $record['message'] = str_pad(substr($record['message'], 0, 60), 60, ' ', STR_PAD_RIGHT);
        $record['context'] = str_pad(substr(json_encode($record['context']), 0, 80), 80, ' ', STR_PAD_RIGHT);

        return $record;
    }
}
