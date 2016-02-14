/**
 * Created by firomero on 10/02/2016.
 */
'use strict';

/*
DEFINITIONS
 */
chatClient.factory('chatConfig',chatConfig);
chatClient.factory('chatMessage',chatMessage);
chatClient.factory('serverMessage',serverMessage);

function chatConfig(){
    return {
      host:'localhost',
      port:'1919',
      connected:false,
      username:'Jon',
      email:'doe@jon.com',
      resource:-1

    };
}

function chatMessage(){
    return {
        content:[],
        time:new Date()
    }
}

function serverMessage(){
    return{
        error:'',
        info:'',
        warn:''
    }
}