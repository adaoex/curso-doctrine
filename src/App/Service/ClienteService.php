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
        $this->cliente->setCnpj(isset($dados['cnpj']) ? $dados['cnpj'] : null);
        $this->cliente->setCpf(isset($dados['cpf']) ? $dados['cpf'] : null);
        $this->cliente->setEmail($dados['email']);

        $res = $this->mapper->insert($this->cliente);

        return $res;
    }

    public function fetchAll()
    {
        return $this->mapper->fetchAll();
    }

}
