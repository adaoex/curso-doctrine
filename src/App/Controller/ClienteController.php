<?php

namespace App\Controller;

use App\Service\ClienteService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ClienteController
{

    protected $service;

    public function __construct(ClienteService $service)
    {
        $this->service = $service;
    }

    public function clientes(Request $request, Application $app)
    {
        $busca = $request->get('s');
        $pag = $request->get('pag');
        $paginacao['pagina_corrente'] = strlen($pag) == 0 ? 1 : (int) $pag;
        $paginacao['registros_por_pagina'] = 5;
        $paginacao['primeiro_registro'] = ($paginacao['pagina_corrente'] - 1) * $paginacao['registros_por_pagina'];

        if (strlen($busca) > 0) {
            $clientes = $this->service->search($busca, $paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
            $paginacao['total_registros'] = $this->service->total($busca);
        } else {
            $clientes = $this->service->fetchAll($paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
            $paginacao['total_registros'] = $this->service->total();
        }

        $paginacao['total_paginas'] = ceil($paginacao['total_registros'] / $paginacao['registros_por_pagina']);
        return $app['twig']->render(
                        'cliente/lista.twig', [
                    'clientes' => $clientes,
                    'busca' => $busca,
                    'paginacao' => $paginacao
        ]);
    }

    public function novo(Application $app)
    {
        return $app['twig']->render(
                        'cliente/form-cliente.twig', ['cliente' => new \App\Entity\Cliente()]
        );
    }

    public function editar($id, Application $app)
    {
        $cliente = $this->service->find($id);
        return $app['twig']->render(
                'cliente/form-cliente.twig', 
                ['cliente' => $cliente]
        );
    }

}
