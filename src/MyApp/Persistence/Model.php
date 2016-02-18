<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 14/02/2016
 * Time: 11:05
 */

namespace MyApp\Persistence;


interface Model {
public function toArray();
public function Collection();
public function getId();
}