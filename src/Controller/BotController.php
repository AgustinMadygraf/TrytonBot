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

        $botman->hears('hello', function (BotMan $bot) {
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Bot capturó el mensaje 'hello'\n", FILE_APPEND);
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });

        // Manejo de la solicitud de entrada
        if ($request->isMethod('POST')) {
            // Capturar el contenido de la solicitud y convertir `message` a `text` si es necesario
            $content = json_decode($request->getContent(), true);

            if (isset($content['message'])) {
                $content['text'] = $content['message'];
                unset($content['message']);
            }

            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Contenido recibido (ajustado): " . json_encode($content) . "\n", FILE_APPEND);

            // Reemplazar el contenido ajustado en la solicitud
            $request->request->replace($content);

            // Ejecutar BotMan para escuchar los mensajes
            $botman->listen();
            return new JsonResponse(['status' => 200, 'messages' => ['BotMan está escuchando']]);
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
