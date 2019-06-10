<?php

namespace App\Utils;

use GuzzleHttp\TransferStats;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Log\LoggerInterface;
use App\Utils\SpotifyService;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Twig\Environment;


class ShareService
{

    public function __construct(Container $container,Environment $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
    }

    public function shareTwitterService() 
    {
        $cadena = 'Estoy escuchando musica con el reproductor de Daniel Gordon';
       $q = str_replace(' ', '%20', $cadena);
         
        $url='https://twitter.com/intent/tweet?text='.$q;
           
        
        return new JsonResponse($url);
    }
}