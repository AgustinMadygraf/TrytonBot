<?php
// TrytonBot/src/Controller/BotController.php
namespace App\Controller;

use App\Service\BotManService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    private $botManService;

    public function __construct(BotManService $botManService)
    {
        $this->botManService = $botManService;
    }

    /**
     * @Route("/bot", name="bot")
     */
    public function index(Request $request): JsonResponse
    {
        // Log the request received
        file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Solicitud recibida en /bot\n", FILE_APPEND);
        file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Método de solicitud: " . $request->getMethod() . "\n", FILE_APPEND);

        if ($request->isMethod('POST')) {
            $content = json_decode($request->getContent(), true);
            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Contenido recibido: " . json_encode($content) . "\n", FILE_APPEND);

            // Ensure 'message' key exists
            if (isset($content['text'])) {
                // Set the 'message' parameter in the request
                $request->request->set('message', $content['text']);
                file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Mensaje establecido en la solicitud: " . $content['text'] . "\n", FILE_APPEND);
            } else {
                file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "La clave 'text' no existe en el contenido recibido\n", FILE_APPEND);
            }

            // Handle request with BotMan service
            $responseMessages = $this->botManService->handleRequest($request);

            file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "Respuesta generada: " . json_encode($responseMessages) . "\n", FILE_APPEND);

            return new JsonResponse(['status' => 200, 'messages' => $responseMessages]);
        }

        // Log if the request is not a POST method
        file_put_contents("C:/AppServ/www/TrytonBot/var/log/dev.log", "La solicitud no es de tipo POST\n", FILE_APPEND);

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
