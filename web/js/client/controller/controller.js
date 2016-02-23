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
function chatController($scope,chatConfig,chatMessage,serverMessage){
$scope.chatConfig = chatConfig;
$scope.chatMessage = chatMessage;
$scope.serverMessage = serverMessage;
$scope.single = '';
$scope.welcome = '';
$scope.soporte='ws';

var   connection = new WebSocket('ws://localhost:1919');
    connection.onmessage = function(e) {

        var msgObject = JSON.parse(e.data);
       // $scope.$apply($scope.chatMessage.content.push(msgObject.message));
       //$scope.$apply($scope.welcome = msgObject.from);
       // console.debug(msgObject);
        if (msgObject.id!=undefined) {
            $scope.chatConfig.id = msgObject.id;
            $scope.chatConfig.resource = msgObject.connection
        }
        console.debug(msgObject);
    };

$scope.Connect=function(){

    connection.onopen = function(e){
        console.info('connected');
    } ;
    $scope.chatConfig.connected = true;
    /*
    configurando conexion
     */
    connection.send
    (JSON.stringify(
        {
            username:$scope.chatConfig.username,
            email:$scope.chatConfig.email,
            from:$scope.chatConfig.email,
            connection:$scope.chatConfig.resource,
            id:$scope.chatConfig.id,
            onCreate:true,
            event:'onCreate'

        }
    ));



};

$scope.Send = function(){

    connection.send(JSON.stringify({
        username:$scope.chatConfig.username,
        email:$scope.chatConfig.email,
        from:$scope.chatConfig.email,
        to:'all',
        message:$scope.single,
        onMessage:true,
        event:'onMessage'
    }));
    $scope.chatMessage.content.push($scope.single);
    $scope.single = '';
};

$scope.Cancel = function(){
    connection.close();
    $scope.chatConfig.connected = false;

}


}