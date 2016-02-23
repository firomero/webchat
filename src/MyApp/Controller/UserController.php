<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/18/2016
 * Time: 3:55 PM
 */

namespace MyApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
class UserController extends MainController{
    public function indexAction(Request $request){
        return new JsonResponse(array('text'));
    }
}