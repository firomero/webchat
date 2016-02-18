<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 3:48 PM
 */
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$routes = new Routing\RouteCollection();
$routes->add('/message', new Routing\Route('/message', array(
    '_controller' =>  'MyApp\\Controller\\MessageController::indexAction',
)));

$routes->add('/user', new Routing\Route('/user', array(
    '_controller' =>  'MyApp\\Controller\\UserController::indexAction',
)));

$routes->add('/log', new Routing\Route('/log', array(
    '_controller' =>  'MyApp\\Controller\\LogController::indexAction',
)));

return $routes;