<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 3:53 PM
 */

namespace MyApp\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
class MessageController extends MainController{

    public function indexAction(Request $request){

        /**
         * @var \Doctrine\MongoDB\Connection $connection
         */
        $connection =$this->container->get('connection');
        $config = $this->container->get('configuration');
        $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/src/MyApp/Persistence'));
        AnnotationDriver::registerAnnotationClasses();
        $dm = DocumentManager::create($connection, $config);

        return new JsonResponse(array('text'));
    }
}