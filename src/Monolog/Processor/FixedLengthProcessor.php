<?php

// src/Monolog/Processor/FixedLengthProcessor.php

namespace App\Monolog\Processor;

class FixedLengthProcessor
{
    public function __invoke(array $record): array
    {
        // Formatear el índice con un espacio inicial centrado
        $record['extra']['index'] = str_pad($record['extra']['index'], 2, ' ', STR_PAD_RIGHT);

        // Formatear el campo channel.level_name con 12 caracteres
        $channelLevel = $record['channel'] . '.' . $record['level_name'];
        $record['extra']['channel_level'] = str_pad($channelLevel, 12, ' ', STR_PAD_RIGHT);

        // Formatear el campo file_line con un ancho fijo de 22 caracteres
        if (isset($record['extra']['file']) && isset($record['extra']['line'])) {
            $fileLine = sprintf('%s:%s', $record['extra']['file'], $record['extra']['line']);
            $record['extra']['file_line'] = str_pad(substr($fileLine, 0, 22), 22, ' ', STR_PAD_RIGHT);
        }

        // Ajustar el mensaje y el contexto a sus respectivos anchos fijos
        $record['message'] = str_pad(substr($record['message'], 0, 60), 60, ' ', STR_PAD_RIGHT);
        $record['context'] = str_pad(substr(json_encode($record['context']), 0, 80), 80, ' ', STR_PAD_RIGHT);

        return $record;
    }
}
