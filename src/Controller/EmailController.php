<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Utils\EmailService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\FOSRestController;

class EmailController extends FOSRestController
{
     /**
     * @Route("/email/{name}/{email}")
     */
    public function index($name,$id,$apikey,$verify,$email,\Swift_Mailer $mailer,LoggerInterface $logger,EmailService $emailService)
    {
       $translator = $this->container->get('translator');
        try{
           $mail = $emailService->sendEmail($name,$email,$mailer);
        }
        catch(\Exception $e){
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
         }
        return $mail;
    }

     /**
     * @Route("/email/restorepass/{email}")
     */
    public function sendResptorePassEmail($name,$id,$apikey,$verify,$email,\Swift_Mailer $mailer,LoggerInterface $logger,EmailService $emailService)
    {
       $translator = $this->container->get('translator');
        try{
           $mail = $emailService->sendResptorePassEmailService($name,$email,$mailer);
        }
        catch(\Exception $e){
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
         }
        return $mail;
    }
}