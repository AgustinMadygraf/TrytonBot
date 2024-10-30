<?php
// TrytonBot/src/Controller/BotController.php
namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    /**
     * @Route("/bot", name="bot")
     */
    public function index(Request $request): Response
    {
        // Configuración de BotMan
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $config = [];
        $botman = BotManFactory::create($config);

        // Define la lógica de respuesta
        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hola! ¿En qué puedo ayudarte?');
        });

        $botman->hears('.*ayuda.*', function (BotMan $bot) {
            $bot->reply('Claro, ¿sobre qué tema necesitas ayuda?');
        });

        // Manejo de la solicitud de entrada
        if ($request->isMethod('POST')) {
            $botman->listen();
            return new Response('{}'); // Agrega una respuesta JSON vacía para evitar renderizado vacío
        }

        // Renderiza la vista del bot para solicitudes GET
        return $this->render('bot/index.html.twig', [
            'controller_name' => 'BotController',
        ]);
    }
}
