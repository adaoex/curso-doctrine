<?php 
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

$response = new Response;

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function(){
    $cliente = new App\Entity\Cliente();
    $mapper = new App\Mapper\ClienteMapper( $entityManager );
    return new App\Service\ClienteService($cliente, $mapper);
};

$app->get("/", function() use ($response) {
    $response->setContent("Primeira Rota Silex /");
    return $response;
})->bind("index");

$app->get("/clientes", function() {
    $service = $service = $app['clienteProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("clientes");

/*
$app->get('/cliente/{id}', function ( Silex\Application $app, $id) use ($clientes) {
    if ( !isset($clientes[$id])){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return new JsonResponse ( $clientes[$id] );
})
->convert('id', function ($id) { return (int) $id; })
->assert('id', '\d+')
->value('id', 0) /* default values */;

/* post */
$app->get("/cliente", function() use ($app){
    $dados = ["nome"=>"Cliente 01","email"=>"cli01@gmail.com", "cpf"=> 0123];
    
    /* reduzindo acoplamento */
    /* substituido pelao 'clienteProvider'
    $cliente = new App\Entity\Cliente();
    $mapper = new App\Mapper\ClienteMapper();
    $service = new App\Service\ClienteService($cliente, $mapper);
    */
    
    $service = $app['clienteProvider'];
    $result = $service->insert($dados);
    
    return new JsonResponse($result);
})->bind("cliente");

$app->get('/cliente/detalhe/{id}', function (Silex\Application $app, $id) {
    $service = $service = $app['clienteProvider'];
    $clientes  = $service->fetchAll();
    if ( !isset($clientes[$id])){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app['twig']->render(
        'detalhe.twig', 
        ["cliente" => $clientes[$id]] 
    );
})->bind("cliente_detalhe");

$app->run();