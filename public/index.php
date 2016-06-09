<?php 
#error_reporting(E_ALL);
#ini_set('display_errors', 1);
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$response = new Response;

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function() use($entityManager) {
    return new App\Service\ClienteService($entityManager);
};

/* formulários */
$app->get("/", function() use ($response, $app) {
    return $app['twig']->render(
        'index.twig'
    );
})->bind("index");

$app->get("/clientes", function() use ($app){
    $service = $app['clienteProvider'];
    return $app['twig']->render(
            'cliente/lista.twig', 
            ['clientes' => $service->fetchAll()]
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

$app->get('/api/clientes/{id}', function ($id) use ($app){
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if ( is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app->json($cliente);
})
->bind("cliente")
->convert('id', function ($id) { return (int) $id; })
->assert('id', '\d+')
->value('id', 0);

$app->post("/api/clientes", function(Request $request) use ($app){
    $dados = [    
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $service = $app['clienteProvider'];
    try{
        $result = $service->insert($dados);
        $return = ['success' => true];
    }  catch (\Exception $ex){
        $return = ['success' => false];
    }
    return new JsonResponse($return);
})->bind("novo_cliente");

$app->put('/api/clientes/{id}', function ( Request $request, $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $dados =  [
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    
    try{
        $result = $service->update($id, $dados);
        $return = ['success' => true];
    }  catch (\Exception $ex){
        $return = ['success' => false];
    }
    return new JsonResponse($return);
})
->bind("cliente_editar")
->method('PUT|POST');


$app->delete('/api/clientes/{id}/del', function ( $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $ret = $service->delete($id);
    return $app->json($ret);
})
->bind("cliente_delete")
->method('DELETE|POST');

$app->run();