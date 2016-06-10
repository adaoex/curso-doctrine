<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ClienteRepository
 *
 * @author Adao
 */
class ClienteRepository extends EntityRepository
{

    /**
     * @return array Entity\Cliente
     */
    public function obterClientesOrdenados()
    {
        return $this
                ->createQueryBuilder('c')
                ->orderBy('c.nome', 'asc')
                ->getQuery()
                ->getResult();
    }

    /**
     * @return array Entity\Cliente
     */
    public function obterClientesDesc()
    {
        $dql = "SELECT c FROM App\Entity\Cliente c"
                . " order by c.nome desc";
        
        return $this->getEntityManager()
                ->createQuery($dql)
                ->getResult();
    }

}
