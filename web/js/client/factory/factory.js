/**
 * Created by firomero on 10/02/2016.
 */
'use strict';

/*
DEFINITIONS
 */
chatClient.factory('chatConfig',chatConfig);
chatClient.factory('chatMessage',chatMessage);
chatClient.factory('chatEvents',chatEvents);
chatClient.factory('eventDispatcher',eventDispatcher);
chatClient.factory('chatStore',chatStore);


/**
 * Default chat configuration
 * @returns {{host: string, port: string, connected: boolean, username: string, email: string, resource: number, id: number}}
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

/**
 * Text Container
 * @returns {{content: Array, time: Date}}
 */
function chatMessage(){
    return {
        content:[],
        time:new Date()
    }
}



/**
 * chat events interface
 * @returns {{onCreate: Function, onMessage: Function, onClose: Function, onRetrieve: Function}}
 */
function chatEvents(){
    return {
        onCreate:function(item){
            item.event = 'onCreate';
            return{
                event:'onCreate',
                item:item,
                config:{}

            }
        },
        onMessage:function(item){
            item.event = 'onMessage';
            return{
                event:'onMessage',
                item:item,
                config:{}
            }
        },
        onClose:function(item){
            item.event = 'onClose';
            return {
                event:'onClose',
                item:item,
                config:{}
            }
        },
        onRetrieve:function(item){
            item.event = 'onRetrieve';
            return {
                event:'onRetrieve',
                item:item,
                config:{}
            }
        }

    }
}
/**
 * The event dispatcher simple
 * @returns {{listeners: Array, addListener: Function, dispatch: Function}}
 */
function eventDispatcher(){
    return {
        listeners:[],
        addListener:function(listener){
            this.listeners.push(listener);
        },
        dispatch:function(event){
            this.listeners.forEach(function(el){
                el(event);
            });
        }
    }
}

/**
 * The chatStore
 * @param dispatcher
 */
function chatStore(dispatcher){

    dispatcher.addListener(function(event){
        if (event.config.connection!=undefined)
        {
            var socket = event.config.connection;

           try{
               socket.send(JSON.stringify(event.item));
           }
            catch (err){
                console.warn(err);
            }
        }
    });
    //public
    return {
        dispatch:function(event){
            dispatcher.dispatch(event);
        }
    }



}