<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/19/2016
 * Time: 3:04 PM
 */

namespace MyApp\Subscribers;
//todo esta clase forma parte del framework orignal chanic

use MyApp\Controller\MainController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ContainerListener implements EventSubscriberInterface{

    protected $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event){
        $controllerBag = $event->getController();
        $controller = current($controllerBag);
        /**
         * @var MainController $controller
         */
        $controller->setContainer($this->container);
        $controllerBag[0]=$controller;
        $event->setController($controllerBag);
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}