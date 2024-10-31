<?php
// TrytonBot/src/Service/FallbackPattern.php
namespace App\Service;

use BotMan\BotMan\BotMan;
use Psr\Log\LoggerInterface;

class FallbackPattern implements ResponsePatternInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register(BotMan $botman): void
    {
        $botman->fallback(function (BotMan $bot) {
            $messageText = $bot->getMessage()->getText();
    
            // Log que se activó el fallback con el mensaje recibido
            $this->logger->info("Fallback activado: no se encontró patrón para el mensaje recibido", [
                'mensaje' => $messageText,
            ]);
    
            // Solo llamar a `deberiaCoincidirConPatron` si `$messageText` no es null
            if ($messageText !== null && $this->deberiaCoincidirConPatron($messageText)) {
                $this->logger->warning("El mensaje recibido debería haber coincidido con un patrón existente", [
                    'mensaje' => $messageText,
                ]);
            }
    
            // Respuesta al usuario
            $bot->reply('No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?');
        });
    }   

    /**
     * Analiza si un mensaje debería haber coincidido con algún patrón.
     *
     * @param string $mensaje
     * @return bool
     */
    private function deberiaCoincidirConPatron(string $mensaje): bool
    {
        // Aquí puedes añadir lógica para verificar si el mensaje debería haber coincidido con un patrón específico.
        // Por ejemplo, podrías analizar palabras clave o hacer consultas a patrones predefinidos.
        // Retorna true si debería haber coincidido; false en caso contrario.
        
        // Ejemplo básico (personaliza según los patrones específicos):
        return preg_match('/(palabra clave 1|palabra clave 2|frase esperada)/i', $mensaje) > 0;
    }
}
