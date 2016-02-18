<?php
namespace MyApp;
use Doctrine\ODM\MongoDB\DocumentManager;
use MyApp\Persistence\Log;
use MyApp\Persistence\Message;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $dm;

    public function __construct(DocumentManager $dm) {
        $this->clients = new \SplObjectStorage;
        $this->dm = $dm;
    }

    public function onOpen(ConnectionInterface $conn) {
    	 // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $log = new Log();
        $log->setLogMesssage("New connection! ({$conn->resourceId})\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $this->dm->persist($log);
        $this->dm->flush();
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    	 $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $log = new Log();
        $log->setLogMesssage(sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'));
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $msgObject = json_decode($msg,true);
        $message = new Message();
        $message->setFrom($msgObject['from']);
        $message->setTo($msgObject['to']);
        $message->setDatetime(new \DateTime('now'));
        $message->setMessage($msgObject['message']);

        $this->dm->persist($log);
        $this->dm->persist($message);
        $this->dm->flush();

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected

                $client->send($msg);


            }
        }




    }

    public function onClose(ConnectionInterface $conn) {
    	 // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $log = new Log();
        $log->setLogMesssage("Connection {$conn->resourceId} has disconnected\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $this->dm->persist($log);
        $this->dm->flush();

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    	echo "An error has occurred: {$e->getMessage()}\n";
        $log = new Log();
        $log->setLogMesssage("An error has occurred: {$e->getMessage()}\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        $this->dm->persist($log);
        $this->dm->flush();

        $conn->close();
    }
}