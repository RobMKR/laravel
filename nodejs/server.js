var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var Crypto = require('crypto');
var users = {};

// Creating Hashes of "user","role","admin","superadmin","author" using key:A2888mTnk874MB
var user_hash = Crypto.createHmac('sha1', 'A2888mTnk874MB').update('user').digest('hex');
var role_hash = Crypto.createHmac('sha1', 'A2888mTnk874MB').update('role').digest('hex');
var author_hash = Crypto.createHmac('sha1', 'A2888mTnk874MB').update('author').digest('hex');
var admin_hash = Crypto.createHmac('sha1', 'A2888mTnk874MB').update('admin').digest('hex');
var superadmin_hash = Crypto.createHmac('sha1', 'A2888mTnk874MB').update('superadmin').digest('hex');


server.listen(8890);
console.log('-server-started-')
io.on('connection', function (socket) {
	users[socket.handshake.query[user_hash]] = { 
		socket_id : socket.client.conn.id , 
		role : socket.handshake.query[role_hash]
	};
    var redisClient = redis.createClient();
	
    redisClient.subscribe('message');
  
    redisClient.on("message", function(channel, data) {
		var params = JSON.parse(data);
		console.log(params.to);
		console.log(users);
		console.log("mew message add in queue channel");
		console.log(typeof users[params.to]);
		if(typeof users[params.to] !== 'undefined'){
			socket.emit(channel, data);
		}
    });
 
    socket.on('disconnect', function() {
		delete users[socket.handshake.query[user_hash]]; 
		redisClient.quit();
    });
 
});