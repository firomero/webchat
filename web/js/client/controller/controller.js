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
function chatController($scope,chatConfig,chatMessage,chatEvents,eventDispatcher,Normalizer){
$scope.chatConfig = chatConfig;
$scope.chatMessage = chatMessage;
$scope.users = [];
$scope.currentUser = undefined;
$scope.single = '';
$scope.welcome = '';
$scope.soporte='ws';

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
        updateEvent(event);
        console.log(e.data);
    };

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

        event.to = $scope.currentUser.email;
        event.toConnection = $scope.currentUser.connection;
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
        event.config.connection = connection;
        var chatStoreHandler = chatStore(eventDispatcher);
        chatStoreHandler.dispatch(event)
}

    /**
     * Do a properly actions for incoming data
     * @param event
     */
function updateEvent(event){

    if (event.event=='onCreate')
    {
        $scope.chatConfig.id=event.id;
        $scope.chatConfig.resource=event.connection;
    }

    if (event.event=='onRetrieve')
    {
        $scope.$apply($scope.users = event.options.users);
        $scope.chatConfig.email = event.email;
    }

    if (event.event=='onMessage')
    {
        $scope.chatMessage.content.push(event.message);
    }
}


}