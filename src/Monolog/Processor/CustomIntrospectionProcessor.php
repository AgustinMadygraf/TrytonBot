<?php
// TrytonBot/src/Monolog/Processor/CustomIntrospectionProcessor.php
namespace App\Monolog\Processor;

use Monolog\Processor\IntrospectionProcessor;

class CustomIntrospectionProcessor extends IntrospectionProcessor
{
    public function __invoke(array $record): array
    {
        $record = parent::__invoke($record);

        if (isset($record['extra']['file'])) {
            // Solo tomar el nombre del archivo en lugar de la ruta completa
            $record['extra']['file'] = basename($record['extra']['file']);
        }

        return $record;
    }
}
