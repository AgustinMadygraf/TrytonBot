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
     * @Route("/bot", name="bot", methods={"POST"})
     */
    public function index(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
    
        if (!isset($content['text']) || empty(trim($content['text']))) {
            return new JsonResponse(['status' => 400, 'message' => 'Mensaje no válido o vacío']);
        }
    
        $request->request->set('message', $content['text']);
        $request->request->set('driver', 'web');
        
        $_REQUEST = array_merge($_REQUEST, $request->request->all());
        
        try {
            $responseMessages = $this->botManService->handleRequest();
            
            // Formato JSON limpio y consistente
            return new JsonResponse([
                'status' => 200,
                'messages' => array_map(function($msg) {
                    return [
                        'type' => 'text',
                        'text' => $msg
                    ];
                }, $responseMessages)
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 500,
                'message' => 'Ocurrió un error al procesar la solicitud'
            ]);
        }
    }
    }