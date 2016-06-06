<?php

namespace App\Mapper;

use App\Entity\Cliente;
use Doctrine\ORM\EntityManager;

/**
 * Mapper ClienteMapper
 *
 * @author adao.goncalves
 */
class ClienteMapper
{
    /**
     * @em Doctrine\ORM\EntityManager 
     */
    private $em;
    
    public function __construct( EntityManager $em )
    {
        $this->em = $em;
    }
    
    public function insert(Cliente $cliente)
    {
        $this->em->persist($cliente);
        $this->em->flush();
    }

    public function update($id, array $dados)
    {
        $cliente = $this->em->find('App\\Entity\\Cliente', $id);
        $cliente->setNome($dados['nome']);
        $cliente->setRg($dados['rg']);
        $cliente->setCpf($dados['cpf']);
        $cliente->setEmail($dados['email']);
        $this->em->flush();
    }

    public function fetchAll()
    {
        return $this->em->getRepository('App\\Entity\\Cliente')->findAll();
    }

    public function find($id)
    {
        return $this->em->find('App\\Entity\\Cliente', $id);
    }
    
    public function delete($id)
    {
        $cliente = $this->em->find('App\\Entity\\Cliente', $id);
        $this->em->remove($cliente);
        $this->em->flush();
    }

}
