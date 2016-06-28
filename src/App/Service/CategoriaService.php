<?php

namespace App\Service;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManager;

class CategoriaService
{

    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function insert(array $dados)
    {
        $entity = new Categoria($dados);
        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
    
    public function update($id, array $dados)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Categoria', $id);
        $entity->setNome($dados['nome']);
        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function fetchAll($firstResults = 0, $maxResults = 100)
    {
        return $this->em
                    ->getRepository("App\\Entity\\Categoria")
                    ->findAllPaginator($firstResults, $maxResults);
        }
    
    public function find($id)
    {
        return $this->em->getRepository("App\\Entity\\Categoria")->find($id);
    }
    
    public function delete($id)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Categoria', $id);
        
        $res = $this->em->remove($entity);
        $this->em->flush();
        return true;
    }

    public function search($termo, $firstResults = 0, $maxResults = 100)
    {
        return $this->em
                    ->getRepository("App\\Entity\\Categoria")
                    ->search($termo, $firstResults, $maxResults);
    }
    
    public function total($termo = null)
    {
        return $this->em->getRepository("App\\Entity\\Categoria")->total($termo);
    }
    
    public function findAllArray()
    {
        return $this->em
                ->getRepository("App\\Entity\\Categoria")
                ->findAllArray();
    }
}
