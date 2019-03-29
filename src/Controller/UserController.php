<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Utils\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;

class UserController extends FOSRestController
{

    /**
     * Creates an User resource
     * @Rest\Post("/user/create")
     */
    public function create(Request $request,UserService $userService)
    {
        
        try{
            $requiredParameters = ["name","email","surname","email","password"];
            foreach($requiredParameters as $parameter){
                if(!$request->request->get($parameter)) return new JsonResponse(['error' => "El campo '".$parameter."' es obligatorio"],400);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $email = $request->request->get("email");
            $user = $this->getDoctrine()->getRepository(User::class)->findBy(["email" => $email]);     
            if(count($user) != 0) return new JsonResponse(['error' => "Ya existe un usuario con el email:".$email],400);

            $user = $userService->create($request->request->all());
            
    
        } catch(\Exception $e){
            return new JsonResponse(['error' => "Se he producido un error contacte con el administrador"],400);
        }

        return new Response('Se a guardado el nuevo usuario '.$user->getName());
        
    }

      /**
     * @Rest\Get("/user/{id}")
     */
    public function getUserAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'Usuario no encontrado con id '.$id
            );
        }
        return new Response('El id señalado pertenece al usuario '.$user->getName());
    }




   
} 