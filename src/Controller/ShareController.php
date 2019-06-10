<?php
// src/Controller/LuckyController.php
namespace App\Controller;


use App\Utils\ShareService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Utils\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ShareController extends FOSRestController
{
    /**
     * @Route("api/share/twitter", methods={"GET"})
     */
    public function shareTwitter(ShareService $shareService)
    {
        $twitter = $shareService->shareTwitterService();

        return $twitter;
    }
} 