<?php 
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
require_once __DIR__.'/../bootstrap.php';

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function() use($entityManager) {
    return new App\Service\ClienteService($entityManager);
};

$app['cliente.service'] = function() use ($entityManager) {
    return new App\Service\ClienteService($entityManager);
};

$app['cliente.controller'] = function() use ($app) {
    return new App\Controller\ClienteController($app['cliente.service']);
};

$app['cliente.api'] = function() use ($app) {
    return new App\Api\ClienteApi($app['cliente.service']);
};

/* formulários */
$app->get("/", function() use ($app) {
    return $app['twig']->render('index.twig');
})->bind("index");

$app->get("/clientes", "cliente.controller:clientes")
        ->bind("lista_clientes");

$app->get("/cliente/novo", "cliente.controller:novo")
        ->bind("form_novo_cliente");

$app->get("/cliente/editar/{id}", "cliente.controller:editar")
        ->bind("form_editar_cliente");


/* API Clientes */
$app->get("/api/clientes", "cliente.api:clintes")
        ->bind("clientes");

$app->post("/api/clientes", "cliente.api:novo")
        ->bind("novo_cliente");

$app->get('/api/clientes/{id}', "cliente.api:buscaPorId")
        ->bind("cliente")
        ->convert('id', function ($id) { return (int) $id; })
        ->assert('id', '\d+')
        ->value('id', 0);

$app->put('/api/clientes/{id}', "cliente.api:editar")
        ->bind("cliente_editar")
        ->assert('id', '\d+')
        ->method('PUT|POST');

$app->delete('/api/clientes/{id}', "cliente.api:apagar")
        ->bind("cliente_delete")
        ->assert('id', '\d+')
        ->method('DELETE|POST');

$app->run();