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

        // Escuchar mensajes específicos
        $botman->hears('hello', function (BotMan $bot) {
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Bot capturó el mensaje 'hello'\n", FILE_APPEND);
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });

        // Escuchar cualquier mensaje como respuesta predeterminada
        $botman->fallback(function (BotMan $bot) {
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Bot capturó un mensaje pero no coincidió con 'hello'\n", FILE_APPEND);
            $bot->reply('No estoy seguro de cómo responder a eso. ¿Puedes reformularlo?');
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
            $botman->listen();
            return new JsonResponse(['status' => 200, 'messages' => ['BotMan está escuchando']]);
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
