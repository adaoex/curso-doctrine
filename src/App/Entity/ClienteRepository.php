<?php

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
    
    /**
     * @return array Entity\Cliente
     */
    public function search($term, $firstResults = 0, $maxResults = 100)
    {
        $dql = "SELECT c FROM App\Entity\Cliente c "
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
        $dql = "SELECT c FROM App\Entity\Cliente c ";
        
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
        $dql = "SELECT COUNT(c.id) FROM App\Entity\Cliente c ";
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

}
