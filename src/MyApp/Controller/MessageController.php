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
class MessageController {

    public function indexAction(Request $request){
        return new JsonResponse(array('text'));
    }
}