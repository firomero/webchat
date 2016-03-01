<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/24/2016
 * Time: 3:45 PM
 */

namespace MyApp\Subscribers;


use MyApp\Model\ChatEvents;
use MyApp\Model\Event;
use MyApp\Persistence\User;
use Ratchet\ConnectionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GlobalSubscribers implements EventSubscriberInterface{

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

            ChatEvents::onBroadCast=>'onBroadCast',
            ChatEvents::onClose=>'onBroadCast',

        );
    }

    /**
     * Notify all connected clients
     * @param Event $event
     */
    public function onBroadCast(Event $event)
    {
        $dm = $event->getDm();
        $clients = $event->getMsgObject();

        //retrieving connected list
        $connections = array();
        foreach ($clients as $client) {

                $connections[]=$client->resourceId;

        }

        /*
         * listing online users
         */
        $data = array_filter($dm->getRepository('MyApp\\Persistence\\User')->findAll(),function(User $user)use($connections){
            return in_array($user->getConnection(),$connections);
        });

        foreach ($clients as $client) {
            if (!is_object($client)) {
                continue;
            }
            /**
             * @var ConnectionInterface $client
             */
            $client->send(
                json_encode(
                    array(
                        'options'=> array_merge(array(
                            'users'=>array_merge(
                                array_filter(array_map(function(User $user){
                                    return array(
                                        'id'=>$user->getId(),
                                        'username'=>$user->getUsername(),
                                        'email'=>$user->getEmail(),
                                        'connection'=>$user->getConnection(),
                                    );
                                },$data),function($item){
                                    return $item['email']!=''&&$item['username']!='';
                                }
                                )
                            )
                        ),
                            array(
                                'usersCollection'=>count($data)
                            )),
                        'event'=>ChatEvents::onBroadCast,
                        'callback_id'=>-1


                    )
                )
            );
        }


    }
}