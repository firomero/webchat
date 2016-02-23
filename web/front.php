<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 2:56 PM
 */
////todo esta clase forma parte del framework orignal chanic
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$routes = require dirname(__DIR__) . '/app/routes.php';
$sc = require dirname(__DIR__) . '/app/container.php';


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
/**
 * @var ContainerBuilder $sc
 * @var Response $response
 */
$response = $sc->get('core')->handle($request);
$response->send();