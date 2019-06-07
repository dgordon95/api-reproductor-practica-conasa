<?php

namespace App\Utils;

use App\Utils\SpotifyService;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;


class EmailService
{

    public function __construct(Container $container,Environment $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
    }

    public function sendEmail($name,$id,$apikey,$verify,$email,$mailer) 
    {
         $message = (new \Swift_Message('Hello Email'))
            ->setFrom('reproductorLibre@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name,'id' => $id,'apiKey' => $apikey,'verify' => $verify]
                ),
                'text/html'
            )   
        ;
        $mailer->send($message);

        return new Response("Mensaje de confirmacion enviado");
    }
}