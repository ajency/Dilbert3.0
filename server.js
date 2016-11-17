var app = require('express')();
var server = require('http').Server(app);
var request = require('request');
var io = require('socket.io')(server);
/*var Redis = require('ioredis');
var redis = new Redis();*/
var redis = require('redis');

var laravel_server = "http://localhost:80";

io.on('connection', function (socket) {
 
  /*console.log("new client connected");*/
  
  //var address = socket.handshake.address; // get IP address of those (different) systems
  //console.log("IP address : " + address);

  var IP_address = socket.handshake.address;
  var idx = IP_address.lastIndexOf(':');
  
  if (~idx && ~IP_address.indexOf('.'))
    IP_address = IP_address.slice(idx + 1);

  //console.log(socket);
  var redisClient = redis.createClient();// for subscribing to redis connection & listen to broadcast -> avoids redis queue
  var pub = redis.createClient(); // create client publish connection -> for redis buffer storage

  /*console.log("Socket engine");
  console.log(socket.id);*/// display client socket ID
  
  socket.on('my_log', function (data) { // data from chrome app/client
    /*console.log("Data from chrome");
    console.log(data.user_id);*/

    if(data.user_id != -1) {
      // client data on state change
      var user = JSON.stringify({
        'user_id': data.user_id,
        'from_state': data.from_state,
        'to_state': data.to_state,
        'cos': data.cos,
        'ip_addr': IP_address,
        'socket_id': socket.id,
      });

      /*console.log(user);
      console.log(data.api_token);*/
      /*while(pub.llen('test-channels') > 0){
        pub.lpop('test-channels');
        console.log("clearing");
      }*/

      var options = {
        url: laravel_server + "/api/fire",
        headers: {
          //'User-Agent': 'request'
          'X-API-KEY': data.api_token
        }
      }
    } else {
      var user = JSON.stringify({
        'user_id': 0,
        'from_state': "active",
        'to_state': "offline",
        'cos': data.cos,
        'ip_addr': IP_address,
        'socket_id': socket.id,
      });

      var options = {
        url: laravel_server + "/api/fire"
      }
    }
    
    pub.rpush('test-channels', user, function(err, reply) {
      /*console.log("Reply of set ");
      console.log(reply);
      console.log("set replied");*/
    });
    
    request(options, function (error, response, body) { // load that page
      if (!error && response.statusCode == 200) {
          console.log("fire");
       } else {
        if (response) {
          console.log("not fired " + response.statusCode.toString());
        }
        console.log("not fired " + error );//+ response.statusCode.toString());
       }
    });
  });

  // broadcast from Laravel -> contains reponse
  redisClient.subscribe('test-channel', function(err, count) { // channel & key word to connect/link to -> Subscribes the client to the specified channels
    console.log(err + " subscribe - node " + count)
  });

  redisClient.on("message", function(channel, message) { // from laravel to node & client
    //console.log("new message in queue "+ message + " channel");
    message = JSON.parse(message);
    //console.log(message);
    if(message.data.data != undefined) {  
      if(message.data.data.socket_status != undefined && message.data.data.socket_status != "org_id_deleted" && message.data.data.socket_status != "no_socket_id" && message.data.data.socket_id != "error"){ //(or) obj.hasOwnProperty("key")
        console.log('test-channels ' + message.data.data.socket_id);
        //console.log(message.data.data.socket_status);
        //pub.del('test-channels ' + message.data.data.socket_id);// delete the content from queue after sending Response to client
        
        if(message.data.data.socket_status == "return") {
          console.log("Returning back to Chrome app");
          io.to(message.data.data.socket_id).emit(channel + ':' + message.event, message.data);// send the response to specific client, using socket ID
          //io.emit(channel + ':' + message.event, message.data);
        } else if(message.data.data.socket_status == "close")
            console.log("Socket ID " + message.data.data.socket_id + " successfully closed..");
        pub.lpop('test-channels');
        console.log("Received from Laravel");
      } else if(message.data.data.socket_status == "org_id_deleted") {
        io.emit(channel + ':' + message.event, message.data);
      } else if(message.data.data.socket_status == "invalid_auth") {
        console.log("Invalid auth");
        io.to(message.data.data.socket_id).emit(channel + ':' + message.event, message.data);
        pub.lpop('test-channels');
      }else {
        console.log("Some issue with Client request or details");
      }
    }
  });
 
  socket.on('disconnect', function() { // when client closes the chrome app
    var t = new Date(); // for now -> get current time
    if(t.getHours() < 10)
        var hr = '0' + t.getHours().toString();
    else
        var hr = t.getHours().toString();

    if(t.getMinutes() < 10)
        var min = '0' + t.getMinutes().toString();
    else
        var min = t.getMinutes().toString();
    var time = hr + ':' + min;


    // user id is '0' as no data is received from client, so using Socket-ID, retrieve user id, then update log, & lpop will be executed in laravel
    var user = JSON.stringify({
      'user_id': 0,
      'from_state': 'active',
      'to_state': 'offline',
      'cos': time,
      'ip_addr': IP_address,
      'socket_id': socket.id,
    });

    pub.rpush('test-channels', user, function(err, reply) {
      console.log("Reply of set ");
      console.log(reply);
    });

    request(laravel_server + "/api/fire", function (error, response, body) { // load that page for event call
      if (!error && response.statusCode == 200) {
          console.log("fire");
       } else {
        if (response != undefined && response.statusCode != undefined) {
          console.log("not fired " + error + response.statusCode.toString());
        } else {
          console.log("not fired " + error + "God knows");
        }
       }
      //pub.del('test-channels ' + socket.id);// delete old data
      pub.lpop('test-channels');
    });

    console.log(user);
    
    console.log("disconnected..");
    console.log(socket.id);
    redisClient.quit();
  });// end of socket.on('disconnect')
});

server.listen(3000, function(){
    console.log('Listening on Port 3000');
});