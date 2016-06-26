<?php

namespace App\Service;

use App\Entity\Produto;
use Doctrine\ORM\EntityManager;

class ProdutoService
{

    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function insert(array $dados)
    {
        $entity = new Produto();
        $entity->setNome($dados['nome']);
        $entity->setDescricao($dados['descricao']);
        $entity->setValor($dados['valor']);
        
        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
    
    public function update($id, array $dados)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Produto', $id);
        $entity->setNome($dados['nome']);
        $entity->setRg($dados['rg']);
        $entity->setCpf($dados['cpf']);
        $entity->setEmail($dados['email']);

        $res = $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function fetchAll($firstResults = 0, $maxResults = 100)
    {
        return $this->em->getRepository("App\\Entity\\Cliente")->findAllPaginator($firstResults, $maxResults);
    }
    
    public function find($id)
    {
        return $this->em->getRepository("App\\Entity\\Cliente")->find($id);
    }
    
    public function delete($id)
    {
        $entity = $this->em->getReference( 'App\\Entity\\Cliente', $id);
        
        $res = $this->em->remove($entity);
        $this->em->flush();
        return true;
    }

    public function search($termo, $firstResults = 0, $maxResults = 100)
    {
        return $this->em->getRepository("App\\Entity\\Cliente")->search($termo, $firstResults, $maxResults);
    }
    
    public function total($termo = null)
    {
        return $this->em->getRepository("App\\Entity\\Cliente")->total($termo);
    }
    
}
