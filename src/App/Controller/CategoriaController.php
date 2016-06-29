<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Service\CategoriaService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CategoriaController
{

    protected $service;

    public function __construct(CategoriaService $service)
    {
        $this->service = $service;
    }

    public function categorias(Request $request, Application $app)
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
                        'categoria/lista.twig', [
                    'categorias' => $itens,
                    'busca' => $busca,
                    'paginacao' => $paginacao
        ]);
    }

    public function novo(Application $app)
    {
        return $app['twig']->render(
                'categoria/form-categoria.twig', 
                ['categoria' => new Categoria()]
            );
    }

    public function editar($id, Application $app)
    {
        $entity = $this->service->find($id);
        return $app['twig']->render(
                'categoria/form-categoria.twig', 
                ['categoria' => $entity]
        );
    }

}
