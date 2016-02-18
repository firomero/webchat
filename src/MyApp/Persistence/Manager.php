<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 14/02/2016
 * Time: 11:10
 */

namespace MyApp\Persistence;


class Manager {
protected $client;
    public function __construct(){
        $this->client=new \MongoClient();
    }

    public function insert(Model $model)
    {
        $admin = $this->client->chatroom;
        $collection = $admin->{$model->Collection()};
        $inderted = $collection->insert($model->toArray());
        return $inderted;
    }

    public function update(Model $model,array $criteria){
        $admin = $this->client->chatroom;
        $collection = $admin->{$model->Collection()};
       $updated = $collection->update($criteria,$model->toArray());
        return $updated;
    }
} 