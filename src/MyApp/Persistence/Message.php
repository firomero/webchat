<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 14/02/2016
 * Time: 11:02
 */

namespace MyApp\Persistence;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Message implements Model{

    /** @ODM\Id */
    private $id;
    /** @ODM\Field(type="string") */
   protected $message;
    /** @ODM\Field(type="string") */
  protected $from;
    /** @ODM\Field(type="string") */
  protected $to;
    /** @ODM\Field(type="date") */
  protected $datetime;

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    public function toArray()
    {
        return array(
            'from'=>$this->from,
            'to'=>$this->to,
            'message'=>$this->message,
            'datetime'=>$this->datetime
        );
    }
    public function Collection(){
        return 'chatMessage';
    }

    public function getId(){
        return  $this->id;
    }
}