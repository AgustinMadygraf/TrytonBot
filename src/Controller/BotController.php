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
        if ($request->isMethod('POST')) {
            $content = json_decode($request->getContent(), true);

            // Ensure 'text' key exists
            if (isset($content['message'])) {
                $content['text'] = $content['message'];
                unset($content['message']);
            }

            // Handle request with BotMan service
            $responseMessages = $this->botManService->handleRequest($content);

            return new JsonResponse(['status' => 200, 'messages' => $responseMessages]);
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
