<?php
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\DriverChain;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;

/* Doctrine */
$cache = new ArrayCache();
$annotationReader = new AnnotationReader();
$cacheAnnotationReader = new CachedReader($annotationReader, $cache);
$annotationDriver = new AnnotationDriver(
        $cacheAnnotationReader,
        array(__DIR__ . DIRECTORY_SEPARATOR . "src")
);
$driverChain = new DriverChain();
$driverChain->addDriver($annotationDriver, "App");

$config = new Doctrine\ORM\Configuration();
$config->setProxyDir("/tmp");
$config->setProxyNamespace("Proxy");
$config->setAutoGenerateProxyClasses(true);
$config->setMetadataDriverImpl($driverChain);
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

AnnotationRegistry::registerFile(
        __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'doctrine'
        . DIRECTORY_SEPARATOR . 'orm' . DIRECTORY_SEPARATOR . 'lib'
        . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'ORM'
        . DIRECTORY_SEPARATOR . 'Mapping' . DIRECTORY_SEPARATOR . 'Driver'
        . DIRECTORY_SEPARATOR . 'DoctrineAnnotations.php'
);

$eventManager = new Doctrine\Common\EventManager();
$entityManager = \Doctrine\ORM\EntityManager::create(
    [
        'driver'    => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ],
    $config,
    $eventManager
);


/* App */
$app = new Application();
$app['debug'] = true;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

$app->register(new Silex\Provider\AssetServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\ServiceControllerServiceProvider());