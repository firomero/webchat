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
$scope.roomTitle = 'Login';

var   connection = new WebSocket('ws://localhost:1919');
    connection.onmessage = function(e) {

        $scope.$apply($scope.chatMessage.content.push(e.data));
        console.warn(e.data);
    };
$scope.Connect=function(){

    connection.onopen = function(e){
        alert('Connected');
        console.info('connected');
    } ;


    $scope.chatConfig.connected = true;
    $scope.roomTitle = 'Chat';
};

$scope.Send = function(){

    connection.send($scope.single);
    $scope.chatMessage.content.push($scope.single);
    $scope.single = '';
};

$scope.Cancel = function(){
    connection.close();
    $scope.chatConfig.connected = false;
    $scope.roomTitle = 'Login';
}


}