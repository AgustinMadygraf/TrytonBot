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
        // Forzar la escritura de un log en una ubicación alternativa
        file_put_contents("C:/Users/tu_usuario/Desktop/bot_log.txt", "Solicitud recibida en /bot\n", FILE_APPEND);

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create([]);

        $botman->hears('hello', function (BotMan $bot) {
            file_put_contents("C:/Users/tu_usuario/Desktop/bot_log.txt", "Bot capturó el mensaje 'hello'\n", FILE_APPEND);
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });

        if ($request->isMethod('POST')) {
            $content = $request->getContent();
            file_put_contents("C:/Users/tu_usuario/Desktop/bot_log.txt", "Contenido recibido: $content\n", FILE_APPEND);

            $botman->listen();
            return new JsonResponse(['status' => 200, 'messages' => ['BotMan está escuchando']]);
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
