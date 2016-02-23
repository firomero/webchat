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


/*
Default chat configuration
 */
function chatConfig(){
    return {
      host:'localhost',
      port:'1919',
      connected:false,
      username:'Jon',
      email:'doe@jon.com',
      resource:-1,
      id:-1

    };
}

/*
default text container
 */
function chatMessage(){
    return {
        content:[],
        time:new Date()
    }
}

/*
intercomunication object
 */
function serverMessage(){
    return{
        event: 'onCreate',
        from:'',
        email:'',
        id: '',
        connection: -1,
        to: -1,
        toConnection: -1,
        message:'',
        options:[]
    }
}