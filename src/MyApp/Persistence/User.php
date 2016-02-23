<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 14/02/2016
 * Time: 11:04
 */

namespace MyApp\Persistence;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User implements Model{

    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    protected $connection;

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /** @ODM\Field(type="string") */
    protected $username;
    /** @ODM\Field(type="string") */
    protected $email;

    /**
     * Returns normalized object
     * @return array
     */
    public function toArray()
    {
        return array(
            'username'=>$this->username,
            'email'=>$this->email,
            'connection'=>$this->connection,
            'id'=>$this->id
        );
    }

    public function Collection(){
        return 'chatUser';
    }

    /**
     * @return mixed
     */
    public function getId(){
        return  $this->id;
    }
}