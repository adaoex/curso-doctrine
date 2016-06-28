<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    /**
     * @return array Entity\Cliente
     */
    public function findAllArray()
    {
        $dql = "SELECT c FROM App\Entity\Cliente c ";
        
        return $this->getEntityManager()
                ->createQuery($dql)
                ->getArrayResult();
    }
}
