<?php
// TrytonBot/src/Controller/BotController.php
namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class BotController extends AbstractController
{
    /**
     * @Route("/bot", name="bot")
     */
    public function index(Request $request): JsonResponse
    {
        // Registrar la solicitud en el log
        file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Solicitud recibida en /bot\n", FILE_APPEND);

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create([]);

        // Variable para almacenar la respuesta de BotMan
        $responseMessages = [];

        // Escuchar mensajes específicos
        $botman->hears('hello|hola|hi', function (BotMan $bot) use (&$responseMessages) {
            $responseMessages[] = 'Hola! ¿En qué puedo ayudarte?';
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Bot capturó el mensaje 'hello' o similar\n", FILE_APPEND);
        });

        // Escuchar cualquier mensaje como respuesta predeterminada
        $botman->fallback(function (BotMan $bot) use (&$responseMessages) {
            $responseMessages[] = 'No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?';
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Bot capturó un mensaje pero no coincidió con 'hello'\n", FILE_APPEND);
        });

        if ($request->isMethod('POST')) {
            $content = json_decode($request->getContent(), true);

            if (isset($content['message'])) {
                $content['text'] = $content['message'];
                unset($content['message']);
            }

            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Contenido recibido (ajustado): " . json_encode($content) . "\n", FILE_APPEND);

            $request->request->replace($content);

            // Ejecutar BotMan para escuchar los mensajes
            ob_start(); // Iniciar el buffer de salida
            $botman->listen();
            ob_clean(); // Limpiar cualquier salida generada por BotMan

            // Retornar solo la respuesta generada por BotMan
            return new JsonResponse(['status' => 200, 'messages' => $responseMessages]);
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
