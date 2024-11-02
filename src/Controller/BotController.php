<?php
// TrytonBot/src/Controller/BotController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BotManService;
use Psr\Log\LoggerInterface;

class BotController
{
    private $botManService;
    private $logger;

    public function __construct(BotManService $botManService, LoggerInterface $logger)
    {
        $this->botManService = $botManService;
        $this->logger = $logger;
    }

    /**
     * @Route("/bot", methods={"POST"})
     */
    public function index()
    {
        try {
            $replies = $this->botManService->handleRequest();

            return new JsonResponse([
                'status' => 200,
                'messages' => $replies,
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Error en BotController: " . $e->getMessage());

            return new JsonResponse([
                'status' => 500,
                'message' => 'Ocurrió un error al procesar la solicitud',
            ]);
        }
    }
}
