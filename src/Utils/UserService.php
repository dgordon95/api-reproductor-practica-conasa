<?php

namespace App\Utils;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserService
{

    public function __construct(Container $container,EntityManagerInterface $entityManager,SerializerInterface $serializer,ValidatorInterface $validator,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(array $params) : array
    {
            $entityManager = $this->entityManager;
            $user = new User();
            $params['password'] = $this->passwordEncoder->encodePassword($user, $params['password']);
            if(array_key_exists("name",$params))$user->setName($params['name']);
            if(array_key_exists("surname",$params))$user->setSurname($params['surname']);
            if(array_key_exists("email",$params))$user->setEmail($params['email']);
            if(array_key_exists("password",$params))$user->setPassword($params['password']);
            if(array_key_exists("username",$params))$user->setUsername($params['username']);
            $errors = $this->validator->validate($user);
             if (count($errors) > 0) {
                foreach($errors as $error){
                    $obj = new \stdClass;
                    $obj->error = $error->getMessage()."(".$error->GetPropertyPath().")";
                    $errorsMessage[] = $obj;
                }
                return [null,new Response(
                    json_encode($errorsMessage),
                    Response::HTTP_NOT_FOUND,
                    ['Content-type' => 'application/json']
                )];
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return [$user,null];
    }

    public function getUserById(int $id) : Response
    {   
        $translator = $this->container->get('translator');
        if(!is_numeric($id)) return new JsonResponse(['error' => $translator->trans('api.userService.non_numeric').$id],401);   
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->find($id);
        if (!$user) {
            $error = ["message"=> $translator->trans('api.userService.id_unexist').$id];
            return new Response(
                json_encode($error),
                Response::HTTP_NOT_FOUND,
                ['Content-type' => 'application/json']
            );
        }
        return new Response(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function deleteUserById(array $params) : Response
    {
        $translator = $this->container->get('translator');
        $id = $params['id'];
        if(!is_numeric($id)) return new JsonResponse(['error' => $translator->trans('api.userService.non_numeric').$id],401);   
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->find($id);
            if (!$user) {
                $error = ["error" => $translator->trans('api.userService.unexisting_user').$id];
                return new Response(
                json_encode($message),
                Response::HTTP_NOT_FOUND,
                ['Content-type' => 'application/json']
            );    
            }
        $name = $user->getName();
        $user = $entityManager->remove($user);
        $entityManager->flush();
        $message = ["message" => $translator->trans('api.userService.deleted_ok').$name];
        return new Response(
                   json_encode($message),
                    Response::HTTP_OK,
                    ['Content-type' => 'application/json']
                    );
    }
    
    public function changeUserById(array $params) : Response
    {
        $translator = $this->container->get('translator');
        $parameters = ['name','password','email'];
        if(sizeof($params) == 0 or (!isset($params['id'])))
        {
            $error = ["error" => $translator->trans('api.userService.empty_fields').implode(",",$parameters)];
            return new Response(
                json_encode($error),
                Response::HTTP_NOT_FOUND,
                ['Content-type' => 'application/json']
            ); 
        }   
        $id = $params['id'];
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->find($id);
        $error = ["error" => $translator->trans('api.userService.unexisting_user').$id];
        if (!$user) 
            return new Response(
                json_encode($error),
                Response::HTTP_NOT_FOUND,
                ['Content-type' => 'application/json']
        );    
        $this->change($user,$params);
        $message = ["message" => $translator->trans('api.userService.updated_ok')];
        return new Response(      
            json_encode($message),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
    
    public function change(User $user,array $params)
    {       
            if(in_array('password',$params))$params['password'] = $this->passwordEncoder->encodePassword($user, $params['password']);
            if(array_key_exists("name",$params))$user->setName($params['name']);
            if(array_key_exists("surname",$params))$user->setSurname($params['surname']);      
            if(array_key_exists("email",$params))$user->setEmail($params['email']);
            if(array_key_exists("password",$params))$user->setPassword($params['password']);
            $entityManager = $this->entityManager;
            $entityManager->persist($user);
            $entityManager->flush();            
    }
} 