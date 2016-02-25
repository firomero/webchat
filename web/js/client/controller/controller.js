/**
 * Created by firomero on 10/02/2016.
 */
'use strict';
/*
DEFINITIONS
 */
chatClient.controller('chatController',chatController);
/*
IMPLEMENTATIONS
 */
function chatController($scope,chatConfig,chatMessage,chatEvents,eventDispatcher,Normalizer,$q){
/*
Global variables
 */
$scope.chatConfig = chatConfig;
$scope.chatMessage = chatMessage;
$scope.users = [];
$scope.localClient = {};
$scope.currentUser = undefined;
$scope.single = '';
$scope.welcome = '';
$scope.soporte='ws';

/*
    Local vaiables
     */

    // Keep all pending requests here until they get responses
    var callbacks = {};
    // Create a unique callback ID to map requests to responses
    var currentCallbackId = 0;
    var receivers = {};

//BODY

try{


    var   connection = new WebSocket('ws://localhost:1919');
    connection.onopen =
        function(e)
        {
            console.info('connected');
        } ;
    connection.onmessage =
        function(e)
        {
            var event = JSON.parse(e.data);

            // If an object exists with callback_id in our callbacks object, resolve it
            if(!receivers.hasOwnProperty(event.callback_id)||event.callback_id==-1)
            {
                //console.log(callbacks[event.callback_id]);
                //$scope.$apply(callbacks[event.callback_id].cb.resolve(event));
                updateEvent(event);
                receivers[event.callback_id]=event;
                console.log(event);
            }
            else
            {
                delete receivers[event.callback_id];
            }




        };

}
    catch (err)
    {
        console.warn(err);
    }

//Object functions
$scope.updateUser=function(user){
        $scope.currentUser = user;
    console.debug(user);
    }    ;

//Sender Functions
$scope.Connect=function(){

    $scope.chatConfig.connected = true;

    var event = chatEvents.onCreate({
        username:$scope.chatConfig.username,
        email:$scope.chatConfig.email,
        from:$scope.chatConfig.email,
        connection:$scope.chatConfig.resource,
        id:$scope.chatConfig.id
    });

    doAction(event,eventDispatcher,connection);

};

$scope.Send = function(){


    var event = chatEvents.onMessage({
        username:$scope.chatConfig.username,
        email:$scope.chatConfig.email,
        from:$scope.chatConfig.email,
        id:$scope.chatConfig.id,
        to:'all',
        toConnection:-1,
        message:$scope.single
    });

    if ($scope.currentUser!=undefined) {

        event.item.to = $scope.currentUser.email;
        event.item.toConnection = $scope.currentUser.connection;
    }
    $scope.chatMessage.content.push($scope.single);
    $scope.single = '';
    doAction(event,eventDispatcher,connection);

};

$scope.Cancel = function(){

    var event = chatEvents.onClose({
        username:$scope.chatConfig.username,
        email:$scope.chatConfig.email,
        from:$scope.chatConfig.email,
        id:$scope.chatConfig.id
    });
    doAction(event,eventDispatcher,connection);
    connection.close();
    $scope.chatConfig.connected = false;

};

    /**
     * Executes the action planned
      * @param event
     * @param eventDispatcher
     *
     * @param connection
     */
function doAction(event,eventDispatcher,connection)
    {
        var defer = $q.defer();
        var callbackId = getCallbackId();
        callbacks[callbackId] = {
            time: new Date(),
            cb:defer
        };
        event.item.callback_id = callbackId;
        event.callback_id = callbackId;
        event.config.connection = connection;
        var chatStoreHandler = chatStore(eventDispatcher);
        chatStoreHandler.dispatch(event);
        return defer.promise;
}

    /**
     * Do a properly actions for incoming data
     * @param event
     */
function updateEvent(event){

    if (event.event=='onCreate')
    {
        $scope.chatConfig.id=event.id;
        $scope.chatConfig.resource=event.connection

    }

    if (event.event=='onRetrieve')
    {
        $scope.$apply($scope.users = event.options.users);
        $scope.$apply( $scope.chatConfig.email = event.email);


    }

    if (event.event=='onMessage')
    {

        $scope.$apply( $scope.chatMessage.content.push(event.message));
        $scope.$apply( $scope.welcome = event.email);


    }

    if (event.event=='onBroadCast')
    {

        $scope.$apply($scope.users = event.options.users);

    }
}

    function getCallbackId(){
        currentCallbackId += 1;
        if(currentCallbackId > 10000) {
            currentCallbackId = 0;
        }
        return currentCallbackId;
    }


}