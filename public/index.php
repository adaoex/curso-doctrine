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
$app['categoria.service'] = function() use ($entityManager) {
    return new App\Service\CategoriaService($entityManager);
};
$app['tag.service'] = function() use ($entityManager) {
    return new App\Service\TagService($entityManager);
};

$app['cliente.controller'] = function() use ($app) {
    return new App\Controller\ClienteController($app['cliente.service']);
};
$app['produto.controller'] = function() use ($app) {
    return new App\Controller\ProdutoController($app['produto.service']);
};
$app['categoria.controller'] = function() use ($app) {
    return new App\Controller\CategoriaController($app['categoria.service']);
};
$app['tag.controller'] = function() use ($app) {
    return new App\Controller\TagController($app['tag.service']);
};

$app['cliente.api'] = function() use ($app) {
    return new App\Api\ClienteApi($app['cliente.service']);
};
$app['produto.api'] = function() use ($app) {
    return new App\Api\ProdutoApi($app['produto.service']);
};
$app['tag.api'] = function() use ($app) {
    return new App\Api\TagApi($app['tag.service']);
};
$app['categoria.api'] = function() use ($app) {
    return new App\Api\CategoriaApi($app['categoria.service']);
};

/* CRUD clientes */
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

/* CRUD tag */
$app->get("/tags", "tag.controller:tags")
        ->bind("lista_tags");

$app->get("/tag/novo", "tag.controller:novo")
        ->bind("form_novo_tag");

$app->get("/tag/editar/{id}", "tag.controller:editar")
        ->bind("form_editar_tag");

/* CRUD categoria */
$app->get("/categorias", "categoria.controller:categorias")
        ->bind("lista_categorias");

$app->get("/categoria/novo", "categoria.controller:novo")
        ->bind("form_novo_categoria");

$app->get("/categoria/editar/{id}", "categoria.controller:editar")
        ->bind("form_editar_categoria");

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

/* API Tags */
$app->post("/api/tags", "tag.api:novo")
        ->bind("novo_tag");

$app->get('/api/tags/{id}', "tag.api:buscaPorId")
        ->bind("tag")
        ->assert('id', '\d+')
        ->value('id', 0);

$app->put('/api/tags/{id}', "tag.api:editar")
        ->bind("tag_editar")
        ->assert('id', '\d+')
        ->method('PUT|POST');

$app->delete('/api/tags/{id}', "tag.api:apagar")
        ->bind("tag_delete")
        ->assert('id', '\d+')
        ->method('DELETE|POST');

/* API Categoria */
$app->post("/api/categorias", "categoria.api:novo")
        ->bind("novo_categoria");

$app->get('/api/categorias/{id}', "categoria.api:buscaPorId")
        ->bind("categoria")
        ->assert('id', '\d+')
        ->value('id', 0);

$app->put('/api/categorias/{id}', "categoria.api:editar")
        ->bind("categoria_editar")
        ->assert('id', '\d+')
        ->method('PUT|POST');

$app->delete('/api/categorias/{id}', "categoria.api:apagar")
        ->bind("categoria_delete")
        ->assert('id', '\d+')
        ->method('DELETE|POST');

$app->run();