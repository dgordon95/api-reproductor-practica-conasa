<?php

namespace App\Utils;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    public function __construct(Container $container,EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function create(array $params) : User
    {
            $entityManager = $this->entityManager;
            $user = new User();
            $user->setName($params['name']);
            $user->setSurname($params['surname']);
            $user->setEmail($params['email']);
            $user->setPassword($params['password']);
            $entityManager->persist($user);
            $entityManager->flush();
            return $user;

    }

 
} 