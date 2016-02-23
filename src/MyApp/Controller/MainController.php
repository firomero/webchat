<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/19/2016
 * Time: 3:19 PM
 */

namespace MyApp\Controller;

//todo esta clase forma parte del framework orignal chanic
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MainController {

  /**
   * @var ContainerBuilder
   */
   protected $container;

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

}