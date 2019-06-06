<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
     /**
     * @Route("/email/{name}/{email}")
     */
    public function index($name,$email,\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('reproductorLibre@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->render(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )   
        ;
        $mailer->send($message);

        return new Response("Mensaje de confirmacion enviado");
    }
}