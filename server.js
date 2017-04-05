var app = require('express')();
var server = require('http').Server(app);
var request = require('request');
var io = require('socket.io')(server); // Websocket Package

var WebSocket = require('ws');
var wss = new WebSocket.Server({ server });

/* Sticky session balancer based on a cluster module */
var cluster = require('cluster'); // Only required if you want the worker id
var sticky = require('sticky-session');
/* Redis Buffer system packages */
var redis = require('redis');

//var laravel_server = "http://localhost:80"; // Live & test server
var laravel_server = "http://localhost:8000"; // Localhost

//var redisClient = redis.createClient();// for subscribing to redis connection & listen to broadcast -> avoids redis queue
//var pub = redis.createClient(); // create client publish connection -> for redis buffer storage

//var connections = []; // new Map();
var connectionCounter = 0;
wss.on('connection', function connection(ws) {
  var IP_address = ws._socket.remoteAddress;
  //connections[connectionCounter++] = ws;
  connectionCounter++; // Get the users connected to the Socket server
  ws.on('message', function incoming(data) {
    if(data.user_id != -1) {
      // client data on state change
      var user = JSON.stringify({
        'user_id': data.user_id,
        'from_state': data.from_state,
        'to_state': data.to_state,
        'cos': data.cos,
        'ip_addr': IP_address,
        'socket_id': ws.upgradeReq.headers['sec-websocket-key'],//connections.indexOf(ws),
      });

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
        'socket_id': ws.upgradeReq.headers['sec-websocket-key'],//connections.indexOf(ws),
      });
    }
        
      pub.rpush('test-channels', user, function(err, reply) { // Push data to the queue
        //console.log("Reply of set ");
        //console.log(reply);
      });

    request(laravel_server + "/api/fire", function (error, response, body) { // load that page for event call
      if (!error && response.statusCode == 200) {
          console.log("Success!! Data stored");
      } else {
        if (response != undefined && response.statusCode != undefined) {
          console.log("Not stored. Error: " + error  + ' ' + response.statusCode.toString());
        } else {
          console.log("Not stored. Error: " + error + " God knows");
        }
      }
      //pub.del('test-channels ' + socket.id);// delete old data
      //pub.lpop('test-channels');// Pop data from test channels - Redis queue
    });
  });

  ws.on('close', function(data){
    connectionCounter--; // users left connected to the server
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
      'socket_id': ws.upgradeReq.headers['sec-websocket-key'],//connections.indexOf(ws),
    });

    pub.rpush('test-channels', user, function(err, reply) { // Push data to the queue
      //console.log("Reply of set ");
      //console.log(reply);
    });

    request(laravel_server + "/api/fire", function (error, response, body) { // load that page for event call
      if (!error && response.statusCode == 200) {
          console.log("Success!! Data stored");
      } else {
        if (response != undefined && response.statusCode != undefined) {
          console.log("Not stored. Error: " + error  + ' ' + response.statusCode.toString());
        } else {
          console.log("Not stored. Error: " + error + " God knows");
        }
      }
      //pub.del('test-channels ' + socket.id);// delete old data
      //pub.lpop('test-channels');// Pop data from test channels - Redis queue
    });

    //console.log(user);
    console.log("disconnected..");
    //console.log(socket.id);
    //redisClient.quit();
  });
});

server.listen(3000, function(){
    console.log('Listening on Port 3000');
});