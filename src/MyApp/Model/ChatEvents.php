<?php
/**
 * Created by PhpStorm.
 * User: felito
 * Date: 2/23/2016
 * Time: 11:32 AM
 */

namespace MyApp\Model;


class ChatEvents {

    /*
     * On new Connection Creeated
     */
    const onCreate = 'onCreate';


    /*
     * on Close connection
     */
    const onClose = 'onClose';


    /*
     * on Message
     */
    const onMessage='onMessage';


    /*
     * on Error
     */
    const onError = 'onError';


    /*
     * on retrieve data
     */

    const onRetrieve = 'onRetrieve';
}