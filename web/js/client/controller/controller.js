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

var   connection = new WebSocket('ws://192.168.1.22:1919');
    connection.onmessage = function(e) {

        var msgObject = JSON.parse(e.data);
        $scope.$apply($scope.chatMessage.content.push(msgObject.message));
       $scope.$apply($scope.welcome = msgObject.from);
        console.debug(msgObject);
    };

$scope.Connect=function(){

    connection.onopen = function(e){
        console.info('connected');
    } ;


    $scope.chatConfig.connected = true;

};

$scope.Send = function(){

    connection.send(JSON.stringify({
        username:$scope.chatConfig.username,
        email:$scope.chatConfig.email,
        from:$scope.chatConfig.email,
        to:'all',
        message:$scope.single
    }));
    $scope.chatMessage.content.push($scope.single);
    $scope.single = '';
};

$scope.Cancel = function(){
    connection.close();
    $scope.chatConfig.connected = false;

}


}