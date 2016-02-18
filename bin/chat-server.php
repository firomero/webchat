<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$connection = new Connection();
$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('chat_room');
$config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/src/MyApp/Persistence'));
AnnotationDriver::registerAnnotationClasses();
$dm = DocumentManager::create($connection, $config);
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat($dm)
        )
    ),
    1919
);
$server->run();