Simple Chanic Chat Server  
1-Message Component
=======================================================  
Definition for all Message incoming or outgoing
[code]
{
    event: string //[the event name][not null]
    from:string//[id from sender] [not null]
    email:string//[email from sender]
    id: string//[id db  from sender] [not null]
    connection: int//[sender connection resource]
    to: string//[id from destiny]
    toConnection: string//[destiny Connection resource]
    message:string//[message to send]
    options:Array//[extra data]
}
[/code]