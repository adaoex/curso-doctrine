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
        return ['success' => true];
    }

    public function fetchAll()
    {
        # $this->em->getRepository($entityName)->findAll();
        return $clientes;
    }

}
