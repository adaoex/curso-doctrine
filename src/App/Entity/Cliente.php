<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * $ORM\Table("clientes")
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cnpj;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cpf;
    
    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    function getNome()
    {
        return $this->nome;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getCnpj()
    {
        return $this->cnpj;
    }

    function getCpf()
    {
        return $this->cpf;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }


    
}
