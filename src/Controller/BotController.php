<?php
// TrytonBot/src/Controller/BotController.php
namespace App\Controller;

use App\Service\BotManService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    private $botManService;
    private $logger;

    public function __construct(BotManService $botManService, LoggerInterface $logger)
    {
        $this->botManService = $botManService;
        $this->logger = $logger;
    }

    /**
     * @Route("/bot", name="bot")
     */
    public function index(Request $request): JsonResponse
    {
        $this->logger->info("Solicitud recibida en /bot", ['method' => $request->getMethod()]);
    
        if ($request->isMethod('POST')) {
            $content = json_decode($request->getContent(), true);
            $this->logger->debug("Contenido recibido", ['content' => $content]);
    
            if (isset($content['text']) && !empty(trim($content['text']))) {
                // Establecer 'message' y 'driver' en la solicitud
                $request->request->set('message', $content['text']);
                $this->logger->info("Mensaje establecido en la solicitud", ['message' => $content['text']]);
    
                $request->request->set('driver', 'web');
                $this->logger->info("Driver establecido en la solicitud", ['driver' => 'web']);
    
                // Exponer los datos de la solicitud a las variables globales
                $_REQUEST = array_merge($_REQUEST, $request->request->all());
    
                $responseMessages = $this->botManService->handleRequest();
                $this->logger->info("Respuesta generada", ['responseMessages' => $responseMessages]);
    
                return new JsonResponse(['status' => 200, 'messages' => $responseMessages]);
            } else {
                $this->logger->warning("Mensaje vacío o no válido recibido en 'text'");
                return new JsonResponse(['status' => 400, 'message' => 'Mensaje no válido o vacío']);
            }
        }
    
        $this->logger->notice("La solicitud no es de tipo POST");
    
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
    
}
