<?php
// src/Controller/ChatController.php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // Log de acceso a la página de inicio
        $this->logger->info('Acceso a la página principal del chat');

        return $this->render('chat/index.html.twig');
    }
}
