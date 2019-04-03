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
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

class UserController extends FOSRestController
{
  
    /**
     * Crear usuario
     *
     * Este método permite crear usuarios, con los campos requeridos.
     *
     * @Rest\Post("/api/user/create", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra un mensaje 'Se a guardado el nuevo usuario'",
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Nombre del usuario"
     * ),
     * @SWG\Parameter(
     *     name="surname",
     *     in="formData",
     *     type="string",
     *     description="Apellido del usuario"
     * ),
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="Email del usuario"
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function create(Request $request,UserService $userService,LoggerInterface $logger)
    {
        $translator = $this->container->get('translator');
        try{
            $requiredParameters = ["name","surname","password"];
            foreach($requiredParameters as $parameter){
                if(!$request->request->get($parameter)) return new JsonResponse(['error' => $translator->trans('api.user.required_field').$parameter],400);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $email = $request->request->get("email");
            $user = $this->getDoctrine()->getRepository(User::class)->findBy(["email" => $email]);     
            // if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return new JsonResponse(['error' => $translator->trans('api.user.nonvalid_format').$email],400);
            if(count($user) != 0) return new JsonResponse(['error' => $translator->trans('api.user.existing_email').$email],400);
            list($user,$error) = $userService->create($request->request->all());
            if(!is_null($error))return $error;
        } catch(\Exception $e){
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
            }
        return new Response($translator->trans('api.user.created_ok').$user->getName());
    }

    /**
     * Obtener usuario
     *
     * Este método permite obtener datos de usuarios, con los campos requeridos.
     *
     * @Rest\Get("/api/user/{id}"), methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve un objeto con todos los datos del usuario'",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Id del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function getUserAction($id,LoggerInterface $logger,UserService $userService)
    {
        $translator = $this->container->get('translator');
        try{
           $user = $userService->getUserById($id);
        }
        catch(\Exception $e){
            $logger->error($translator->trans('api.user.catch_error'));
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
         }
        return $user;
    }

    /**
     * Eliminar usuario
     *
     * Este método permite eliminar usuarios, con el id del usuario.
     *
     * @Rest\Delete("/api/user/delete", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra un mensaje 'Se a eliminado al usuario'",
     * )
    * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     type="integer",
     *     description="Id del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function deleteUserAction(Request $request,LoggerInterface $logger,UserService $userService)
    {
        $translator = $this->container->get('translator');
        try{
           $user = $userService->deleteUserById($request->request->all());
     }
        catch(\Exception $e){
            $logger->error($translator->trans('api.user.catch_error'));
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
     }
        return $user;
    }

    /**
     * Modificar usuario
     *
     * Este método permite modificar datos de los usuarios, con los campos que se quieren modificar.
     *
     * @Rest\Put("/api/user/changeUser", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra un mensaje 'El perfil se a actualizado correctamente.'",
     * )
    * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     type="integer",
     *     description="Id del usuario"
     * ),
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Nombre del usuario"
     * ),
     * @SWG\Parameter(
     *     name="surname",
     *     in="formData",
     *     type="string",
     *     description="Apellido del usuario"
     * ),
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="Email del usuario"
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function changePassUserAction(Request $request,LoggerInterface $logger,UserService $userService)
    {
        $translator = $this->container->get('translator');
       try
       {
           $user = $userService->changeUserById($request->request->all());
       }
       catch(\Exception $e)
       {
            $logger->error($translator->trans('api.user.catch_error'));
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
       }
        return $user;
    }
} 