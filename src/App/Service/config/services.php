<?php

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
$app['uploader.service'] = function() {
    return new \App\Service\FileUploader( __DIR__. '/../web/image/');
};

/* controllers */
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