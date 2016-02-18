<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 14/02/2016
 * Time: 11:21
 */

namespace MyApp\Persistence;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Log implements Model{

    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    protected $logMesssage;

    /**
     * @return mixed
     */
    public function getLogMesssage()
    {
        return $this->logMesssage;
    }

    /**
     * @param mixed $logMesssage
     */
    public function setLogMesssage($logMesssage)
    {
        $this->logMesssage = $logMesssage;
    }

    /**
     * @return mixed
     */
    public function getLogTime()
    {
        return $this->logTime;
    }

    /**
     * @param mixed $logTime
     */
    public function setLogTime($logTime)
    {
        $this->logTime = $logTime;
    }

    /** @ODM\Field(type="date") */
    protected $logTime;

    public function toArray()
    {
        return array(
            'logMessage'=>$this->logMesssage,
            'logTime'=>$this->logTime,
        );
    }

    public function Collection()
    {
       return 'chatLog';
    }

    public function getId()
    {
       return $this->id;
    }
}