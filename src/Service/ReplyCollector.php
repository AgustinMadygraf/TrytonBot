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

            // Log para confirmar el contenido y tipo de respuesta
            $this->logger->info("Recopilando respuesta", [
                'contenido' => $messageText,
                'tipo' => get_class($payload),
            ]);

            // Añadir respuesta al arreglo y verificar duplicados
            if (!in_array($messageText, $this->replies, true)) {
                $this->replies[] = $messageText;
                $this->logger->info("Respuesta añadida al arreglo 'replies'", [
                    'respuesta_agregada' => $messageText,
                ]);
            } else {
                $this->logger->warning("Respuesta duplicada detectada en 'replies'", [
                    'respuesta_duplicada' => $messageText,
                ]);
            }

            // Log para verificación del estado de 'replies'
            $this->logger->info("Estado actual del arreglo 'replies'", [
                'contenido_arreglo' => $this->replies,
            ]);
        }
        
        return $next($payload);
    }
}
