<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Mapper\ClienteMapper;

/**
 * Description of ClienteService
 *
 * @author adao.goncalves
 */
class ClienteService
{

    private $cliente;
    private $mapper;

    public function __construct(Cliente $cliente, ClienteMapper $mapper)
    {
        $this->cliente = $cliente;
        $this->mapper = $mapper;
    }

    public function insert(array $dados)
    {
        $this->cliente->setNome($dados['nome']);
        $this->cliente->setRg($dados['rg']);
        $this->cliente->setCpf($dados['cpf']);
        $this->cliente->setEmail($dados['email']);

        $res = $this->mapper->insert($this->cliente);

        return ['success' => true];
    }
    
    public function update($id, array $dados)
    {
        $res = $this->mapper->update($id, $dados);
        return ['success' => true];
    }

    public function fetchAll()
    {
        return $this->mapper->fetchAll();
    }
    
    public function find($id)
    {
        return $this->mapper->find($id);
    }
    
    public function delete($id)
    {
        $this->mapper->delete($id);
        return ['success' => true];
    }

}
