<?php

namespace App\Service;

use App\Entity\Tag;
use Doctrine\ORM\EntityManager;

class TagService
{

    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function insert(array $dados)
    {
        $entity = new Tag($dados);
        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
    
    public function update($id, array $dados)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Tag', $id);
        $entity->setNome($dados['nome']);
        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function fetchAll($firstResults = 0, $maxResults = 100)
    {
        return $this->em
                    ->getRepository("App\\Entity\\Tag")
                    ->findAllPaginator($firstResults, $maxResults);
        }
    
    public function find($id)
    {
        return $this->em->getRepository("App\\Entity\\Tag")->find($id);
    }
    
    public function delete($id)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Tag', $id);
        
        $res = $this->em->remove($entity);
        $this->em->flush();
        return true;
    }

    public function search($termo, $firstResults = 0, $maxResults = 100)
    {
        return $this->em
                    ->getRepository("App\\Entity\\Tag")
                    ->search($termo, $firstResults, $maxResults);
    }
    
    public function total($termo = null)
    {
        return $this->em->getRepository("App\\Entity\\Tag")->total($termo);
    }
    
    public function findAllArray()
    {
        return $this->em
                ->getRepository("App\\Entity\\Tag")
                ->findAllArray();
    }
}
