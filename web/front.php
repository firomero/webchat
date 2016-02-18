<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 2:56 PM
 */

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$routes = require dirname(__DIR__) . '/app/routes.php';
$sc = require dirname(__DIR__) . '/app/container.php';


use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
/**
 * @var \Doctrine\MongoDB\Connection $connection
 */
$connection =$sc->get('connection');

/**
 * @var Doctrine\ODM\MongoDB\Configuration $config
 */
$config = $sc->get('configuration');
$config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/src/MyApp/Persistence'));
AnnotationDriver::registerAnnotationClasses();
$dm = DocumentManager::create($connection, $config);

/**
 * @var ContainerBuilder $sc
 * @var Response $response
 */
$response = $sc->get('core')->handle($request);
$response->send();