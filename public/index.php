<?php 
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
require_once __DIR__.'/../bootstrap.php';

$app['cliente.service'] = function() use ($entityManager) {
    return new App\Service\ClienteService($entityManager);
};
$app['produto.service'] = function() use ($entityManager) {
    return new App\Service\ProdutoService($entityManager);
};

$app['cliente.controller'] = function() use ($app) {
    return new App\Controller\ClienteController($app['cliente.service']);
};
$app['produto.controller'] = function() use ($app) {
    return new App\Controller\ProdutoController($app['produto.service']);
};

$app['cliente.api'] = function() use ($app) {
    return new App\Api\ClienteApi($app['cliente.service']);
};

$app['produto.api'] = function() use ($app) {
    return new App\Api\ProdutoApi($app['produto.service']);
};

/* CRUP clientes */
$app->get("/", function() use ($app) {
    return $app['twig']->render('index.twig');
})->bind("index");

$app->get("/clientes", "cliente.controller:clientes")
        ->bind("lista_clientes");

$app->get("/cliente/novo", "cliente.controller:novo")
        ->bind("form_novo_cliente");

$app->get("/cliente/editar/{id}", "cliente.controller:editar")
        ->bind("form_editar_cliente");

/* CRUP produtos */
$app->get("/produtos", "produto.controller:produtos")
        ->bind("lista_produtos");

$app->get("/produto/novo", "produto.controller:novo")
        ->bind("form_novo_produto");

$app->get("/produto/editar/{id}", "produto.controller:editar")
        ->bind("form_editar_produto");

/* API Clientes */
$app->get("/api/clientes", "cliente.api:listar")
        ->bind("clientes");

$app->post("/api/clientes", "cliente.api:novo")
        ->bind("novo_cliente");

$app->get('/api/clientes/{id}', "cliente.api:buscaPorId")
        ->bind("cliente")
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

/* API Produtos */
$app->get("/api/produtos", "produto.api:listar")
        ->bind("produtos");

$app->post("/api/produtos", "produto.api:novo")
        ->bind("novo_produto");

$app->get('/api/produtos/{id}', "produto.api:buscaPorId")
        ->bind("produto")
        ->assert('id', '\d+')
        ->value('id', 0);

$app->put('/api/produtos/{id}', "produto.api:editar")
        ->bind("produto_editar")
        ->assert('id', '\d+')
        ->method('PUT|POST');

$app->delete('/api/produtos/{id}', "produto.api:apagar")
        ->bind("produto_delete")
        ->assert('id', '\d+')
        ->method('DELETE|POST');

$app->run();