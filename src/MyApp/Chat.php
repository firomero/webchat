<?php
namespace MyApp;
use Doctrine\ODM\MongoDB\DocumentManager;
use MyApp\Exception\EventNotAllowedException;
use MyApp\Model\ChatEvents;
use MyApp\Model\Event;
use MyApp\Persistence\Log;
use MyApp\Persistence\User;
use MyApp\Subscribers\ChatSubscribers;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $dm;
    protected $eventDispatcher;

    public function __construct(DocumentManager $dm) {
        $this->clients = new \SplObjectStorage;
        $this->dm = $dm;
        $this->eventDispatcher = new EventDispatcher();
        $this->eventDispatcher->addSubscriber(new ChatSubscribers());
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
    	 // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $log = new Log();
        $log->setLogMesssage("New connection! ({$conn->resourceId})\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $user = new User();
        $user->setConnection($conn->resourceId);
        $this->dm->persist($user);
        $this->dm->persist($log);
        $this->dm->flush();
        $conn->send(json_encode(array_merge($user->toArray(),array('event'=>ChatEvents::onCreate))));
        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     * @throws EventNotAllowedException
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
    	 $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $log = new Log();
        $log->setLogMesssage(sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'));
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $this->dm->persist($log);
        $this->dm->flush();

        /**
         * msgObject is the object containing
         * fields(from,to,message,toConnection,chat, available)
         */
        $msgObject = json_decode($msg,true);
        /*
         * Always identify the user is making a request
         */

        if (array_key_exists('event',$msgObject))
        {
            /*
             * dispatch events
             */
            $this->eventDispatcher->dispatch($msgObject['event'],new Event($msgObject,$from,$this->dm));

            /*
             * actions to do
             */
            if ($msgObject['event']==ChatEvents::onCreate)
            {
                $this->retrieveServerInfo($from);
            }

            if ($msgObject['event']==ChatEvents::onMessage)
            {
                $this->doChat($from,$msgObject);
            }



        }
        else
        {
            throw new EventNotAllowedException();

        }

    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {

    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
    	echo "An error has occurred: {$e->getMessage()}\n";
        $log = new Log();
        $log->setLogMesssage("An error has occurred: {$e->getMessage()}\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $this->dm->persist($log);
        $this->dm->flush();

        $conn->close();
    }

    /*
     * MEMBERS
     */


    /**
     * Sends user Message
     * @param ConnectionInterface $from
     * @param array $msg (fromConnection[id connection], toConnection[id connection], message[message to send])
     */
    protected function doChat(ConnectionInterface $from, array $msg){

        foreach ($this->clients as $client) {
            if (($from !== $client && $client->resourceId===intval($msg['toConnection']))|| $msg['toConnection']==-1) {
                $client->send(json_encode($msg));
                break;

            }
        }
    }

    /**
     * Return connected users
     * @param ConnectionInterface $from
     */
    protected function retrieveServerInfo(ConnectionInterface $from){

        $connections = array();
        foreach ($this->clients as $client) {
            if ($client!==$from) {
                $connections[]=$client->resourceId;
            }
        }

        /*
         * listing online users
         */
        $data = array_filter($this->dm->getRepository('MyApp\\Persistence\\User')->findAll(),function(User $user)use($connections){
            return in_array($user->getConnection(),$connections);
        });

        /**
         * @var User $user
         */
        $user = current(
            array_filter($this->dm->getRepository('MyApp\\Persistence\\User')->findAll(),function(User $user)use($from){
                return $user->getConnection()==$from->resourceId;
            })
        );



        $from->send(
            json_encode(
           array(
               'options'=> array_merge(array(
                   'users'=>array_map(function(User $user){
                       return array(
                           'id'=>$user->getId(),
                           'username'=>$user->getUsername(),
                           'email'=>$user->getEmail(),
                           'connection'=>$user->getConnection(),
                       );
                   },$data)
               ),
                   array(
                       'usersCollection'=>count($data)
                   )),
               'event'=>ChatEvents::onRetrieve,
               'from'=>$from->resourceId,
               'email'=>$user->getEmail(),
               'id'=>$user->getId()

           )
        )
        );

    }


    /**
     * Update Connection
     * @param array $msg(username|email|id)
     */
    protected function updateConnection(array $msg){
        /**
         * @var User $user;
         */
        $user =  $this->dm->getRepository('MyApp\\Persistence\\User')->find($msg['id']);
        $user->setUsername($msg['username']);
        $this->dm->persist($user);
        $this->dm->flush();
    }
}