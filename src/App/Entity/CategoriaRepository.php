<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class CategoriaRepository extends EntityRepository
{
    /**
     * @return array Entity\Cliente
     */
    public function search($term, $firstResults = 0, $maxResults = 100)
    {
        $dql = "SELECT c FROM App\Entity\Categoria c "
                . "WHERE c.nome LIKE :nome "
                . "order by c.nome desc";
        
        return $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('nome', "%$term%")
                ->setFirstResult($firstResults)
                ->setMaxResults($maxResults)
                ->getResult();
    }
    
    /**
     * @return array Entity\Cliente
     */
    public function findAllPaginator($firstResults = 0, $maxResults = 100)
    {
        $dql = "SELECT c FROM App\Entity\Categoria c ";
        
        return $this->getEntityManager()
                ->createQuery($dql)
                ->setFirstResult($firstResults)
                ->setMaxResults($maxResults)
                ->getResult();
    }
    
    /**
     * @return array Entity\Cliente
     */
    public function total($busca = null)
    {
        $dql = "SELECT COUNT(c.id) FROM App\Entity\Categoria c ";
        if ( ! is_null($busca) ){
            $dql .= "WHERE c.nome LIKE :nome ";
        }
        $query = $this->getEntityManager()
                ->createQuery($dql); 
        
        if ( ! is_null($busca) ){
            $query->setParameter('nome', "%$busca%");
        }
        
        return $query->getSingleScalarResult();
    }
    
    /**
     * @return array Entity\Cliente
     */
    public function findAllArray()
    {
        $dql = "SELECT c FROM App\Entity\Categoria c ";
        
        return $this->getEntityManager()
                ->createQuery($dql)
                ->getArrayResult();
    }
    
}
