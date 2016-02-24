<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/23/2016
 * Time: 11:35 AM
 */

namespace MyApp\Subscribers;


use MyApp\Model\ChatEvents;
use MyApp\Model\Event;
use MyApp\Persistence\Log;
use MyApp\Persistence\Message;
use MyApp\Persistence\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChatSubscribers implements EventSubscriberInterface{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            ChatEvents::onClose=>'onClose',
            ChatEvents::onCreate=>'onCreate',
            ChatEvents::onMessage=>'onMessage',
        );
    }

    /**
     * Dispatch this event when user log out
     * @param Event $event
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function onClose(Event $event)
    {
        $dm = $event->getDm();
        $msgObject = $event->getMsgObject();
        $connection = $event->getConnection();
        $remitent = $dm->getRepository('MyApp\\Persistence\\User')->find($msgObject['id']);
        $log = new Log();
        $log->setLogMesssage("Connection {$connection->resourceId} has disconnected\n");
        $log->setLogTime(date_format(new \DateTime(),'d-m-Y H:i:s'));
        /**
         *@var User $remitent
         */
        $remitent->setConnection(-1);
        $dm->persist($remitent);
        $dm->persist($log);
        $dm->flush();
    }

    /**
     * @param Event $event
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function onCreate(Event $event)
    {
        $dm = $event->getDm();
        $msgObject = $event->getMsgObject();
        $remitent = $dm->getRepository('MyApp\\Persistence\\User')->find($msgObject['id']);
        $remitent->setUsername($msgObject['from']);
        $remitent->setEmail($msgObject['from']);
        $dm->persist($remitent);
        $dm->flush();

    }


    /**
     * @param Event $event
     */
    public function onMessage(Event $event)
    {
        $dm = $event->getDm();
        $msgObject = $event->getMsgObject();
        $message = new Message();
        $message->setFrom($msgObject['from']);
        $message->setTo($msgObject['to']);
        $message->setDatetime(new \DateTime('now'));
        $message->setMessage($msgObject['message']);
        $dm->persist($message);
        $dm->flush();
    }
}