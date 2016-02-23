<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/23/2016
 * Time: 11:48 AM
 */

namespace MyApp\Model;
use Ratchet\ConnectionInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class Event extends \Symfony\Component\EventDispatcher\Event{
    protected $msgObject;
    /**
     * @var ConnectionInterface $connection
     */
    protected $connection;

    /**
     * @var DocumentManager $dm
     */
    protected $dm;

    /**
     * @return DocumentManager
     */
    public function getDm()
    {
        return $this->dm;
    }

    /**
     * @param DocumentManager $dm
     */
    public function setDm($dm)
    {
        $this->dm = $dm;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    function __construct($msgObject, $connection, $dm)
    {
        $this->msgObject = $msgObject;
        $this->connection = $connection;
        $this->dm = $dm;
    }

    /**
     * @return mixed
     */
    public function getMsgObject()
    {
        return $this->msgObject;
    }

    /**
     * @param mixed $msgObject
     */
    public function setMsgObject($msgObject)
    {
        $this->msgObject = $msgObject;
    }

}