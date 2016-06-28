<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Service\ProdutoService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ProdutoController
{

    protected $service;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
    }

    public function produtos(Request $request, Application $app)
    {
        $busca = $request->get('s');
        $pag = $request->get('pag');
        $paginacao['pagina_corrente'] = strlen($pag) == 0 ? 1 : (int) $pag;
        $paginacao['registros_por_pagina'] = 5;
        $paginacao['primeiro_registro'] = ($paginacao['pagina_corrente'] - 1) * $paginacao['registros_por_pagina'];

        if (strlen($busca) > 0) {
            $itens = $this->service->search($busca, $paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
            $paginacao['total_registros'] = $this->service->total($busca);
        } else {
            $itens = $this->service->fetchAll($paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
            $paginacao['total_registros'] = $this->service->total();
        }

        $paginacao['total_paginas'] = ceil($paginacao['total_registros'] / $paginacao['registros_por_pagina']);
        return $app['twig']->render(
                        'produto/lista.twig', [
                    'produtos' => $itens,
                    'busca' => $busca,
                    'paginacao' => $paginacao
        ]);
    }

    public function novo(Application $app)
    {
        return $app['twig']->render(
                'produto/form-produto.twig', 
                ['produto' => new Produto()]
            );
    }

    public function editar($id, Application $app)
    {
        $entity = $this->service->find($id);
        return $app['twig']->render(
                'produto/form-produto.twig', 
                ['produto' => $entity]
        );
    }

}
