<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name) 
    { 
        if(ctype_alpha($name)){
            $this->name = $name;
            return $this;
        }
        else{
            $message = ["message"=>"El nombre solo puede contener caracteres alfabeticos"];
            return new Response(
                    json_encode($message),
                    Response::HTTP_OK,
                    ['Content-type' => 'application/json']
                );
        }   
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname)
    {
        if(ctype_alpha($surname)){
            $this->surname = $surname;
            return $this;
        }
        else{
            $message = ["message"=>"El nombre solo puede contener caracteres alfabeticos"];
            return new Response(
                    json_encode($message),
                    Response::HTTP_OK,
                    ['Content-type' => 'application/json']
                );
        }  
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email) 
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->email = $email;
            return $this;
        }
        else{
            $message = ["message"=>"El nombre solo puede contener caracteres alfabeticos"];
            return new Response(
                    json_encode($message),
                    Response::HTTP_OK,
                    ['Content-type' => 'application/json']
                );
        }
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        if(strlen($password) > 8 && ctype_alnum($password)){
            $this->password =$password;
            return $this; 
        }
        else{
            $message = ["message"=>"El nombre solo puede contener caracteres alfabeticos"];
            return new Response(
                    json_encode($message),
                    Response::HTTP_OK,
                    ['Content-type' => 'application/json']
                );
        }
    }
}
