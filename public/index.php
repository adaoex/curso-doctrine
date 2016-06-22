<?php 
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

$response = new Response;

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function() use($entityManager) {
    return new App\Service\ClienteService($entityManager);
};

/* formulários */
$app->get("/", function() use ($app) {
    return $app['twig']->render(
        'index.twig'
    );
})->bind("index");

$app->get("/clientes", function(Request $request) use ($app){
    $service = $app['clienteProvider'];
    $busca = $request->get('s');
    $pag = $request->get('pag');
    $paginacao['pagina_corrente'] = strlen($pag) == 0? 1: (int)$pag;
    $paginacao['registros_por_pagina'] = 5;
    $paginacao['primeiro_registro'] = ($paginacao['pagina_corrente']-1)*$paginacao['registros_por_pagina'];
    if (strlen($busca) > 0){
        $clientes = $service->search($busca, $paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
        $paginacao['total_registros'] = $service->total($busca);
    }else{
        $clientes = $service->fetchAll($paginacao['primeiro_registro'], $paginacao['registros_por_pagina']);
        $paginacao['total_registros'] = $service->total();
    }
    
    
    $paginacao['total_paginas'] = ceil($paginacao['total_registros'] / $paginacao['registros_por_pagina']);
    return $app['twig']->render(
            'cliente/lista.twig', 
            [
                'clientes' => $clientes, 
                'busca' => $busca,
                'paginacao' => $paginacao
            ]
    );
})->bind("lista_clientes");

$app->get("/cliente/novo", function() use ($app){
    $service = $service = $app['clienteProvider'];
    return $app['twig']->render(
            'cliente/form-cliente.twig',
            ['cliente' => new App\Entity\Cliente()]
    );
})->bind("form_novo_cliente");

$app->get("/cliente/editar/{id}", function($id) use ($app) {
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    return $app['twig']->render(
            'cliente/form-cliente.twig',
            ['cliente' => $cliente]
    );
})->bind("form_editar_cliente");


/* API Clientes */
$app->get("/api/clientes", function() use ($app){
    $service = $service = $app['clienteProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("clientes");

$app->post("/api/clientes", function(Request $request) use ($app){
    $dados = [    
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $constraint = new Assert\Collection(array(
        'nome' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3, 'max' => 255))
        ),
        'email' => array(
            new Assert\NotBlank(), 
            new Assert\Email(),
        ),
        'cpf' => array(
            new Assert\NotBlank(), 
            new App\Validator\Cpf(),
        ),
        'rg' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3))
        ),
    ));
    
    $errors = $app['validator']->validate($dados, $constraint);
    if (count($errors) > 0) {
        $arr_erros = [];
        foreach ($errors as $error) {
            $arr_erros[] = $error->getPropertyPath().' '.$error->getMessage();
        }
        return new JsonResponse(array(
            'errors' => $arr_erros
        ));
    } 
    
    $service = $app['clienteProvider'];
    
    try{
        $insert = $service->insert($dados);
        return $app->json(array('success' => true));  
    }catch( Exception $ex ){
        return $app->json(array(
            'success' => true, 
            'errors' => ['Erro durante o cadastro: ' . $ex->getMessage()]
        ));
    }
})->bind("novo_cliente");

$app->get('/api/clientes/{id}', function ($id) use ($app) {
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
    }
    return $app->json($cliente);
})
->bind("cliente")
->convert('id', function ($id) { return (int) $id; })
->assert('id', '\d+')
->value('id', 0); /* default values */


$app->put('/api/clientes/{id}', function ( Request $request, $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
    }
    $dados =  [
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $constraint = new Assert\Collection(array(
        'nome' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3, 'max' => 255))
        ),
        'email' => array(
            new Assert\NotBlank(), 
            new Assert\Email(),
        ),
        'cpf' => array(
            new Assert\NotBlank(), 
            new App\Validator\Cpf(),
        ),
        'rg' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3))
        ),
    ));
    
    $errors = $app['validator']->validate($dados, $constraint);
    if (count($errors) > 0) {
        $arr_erros = [];
        foreach ($errors as $error) {
            $arr_erros[] = $error->getPropertyPath().' '.$error->getMessage();
        }
        return new JsonResponse(array(
            'errors' => $arr_erros
        ));
    } 
    
    $dados['id'] = $id;
    
    try{
        $ret = $service->update( $id, $dados );
        return $app->json(array('success' => true));  
    }catch( Exception $ex ){
        return $app->json(array(
            'success' => true, 
            'errors' => ['Erro durante a edição: ' . $ex->getMessage()]
        ));
    }
})
->bind("cliente_editar")
->assert('id', '\d+')
->method('PUT|POST');

$app->delete('/api/clientes/{id}', function ( $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
    }
    
    try{
        $ret = $service->delete($id);
        return $app->json(array('success' => true));  
    }catch( Exception $ex ){
        return $app->json(array(
            'success' => true, 
            'errors' => ['Erro durante a exclusão: ' . $ex->getMessage()]
        ));
    }
})
->bind("cliente_delete")
->assert('id', '\d+')
->method('DELETE|POST');

$app->run();