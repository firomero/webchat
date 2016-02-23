<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 4:00 PM
 */

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$sc = new ContainerBuilder();
$sc->register('context', 'Symfony\Component\Routing\RequestContext');
$sc->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
    ->setArguments(array($routes, new Reference('context')))
;
$sc->register('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');

$sc->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments(array(new Reference('matcher')))
;
$sc->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(array('UTF-8'))
;
$sc->register('listener.controller', 'MyApp\Subscribers\ContainerListener')
    ->setArguments(array($sc));
;

$sc->register('connection', 'Doctrine\MongoDB\Connection')

;

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.controller')))

;

$sc->register('configuration', 'Doctrine\ODM\MongoDB\Configuration')
->addMethodCall('setProxyDir',array(__DIR__ . '/Proxies'))
->addMethodCall('setHydratorDir',array(__DIR__ . '/Hydrators'))
->addMethodCall('setHydratorNamespace',array('Hydrators'))
->addMethodCall('setProxyNamespace',array('Proxies'))
->addMethodCall('setDefaultDB',array('chat_room'))
;

$sc->register('core', 'MyApp\Core')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;



return $sc;