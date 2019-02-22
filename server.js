var express = require('express'); // Express contains some boilerplate to for routing and such
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http); // Here's where we include socket.io as a node module 
var proxy = require('express-http-proxy');

// Serve the index page 
app.get("/", function(request, response) {
    response.sendFile(__dirname + '/login.html');
});

app.get("/home", function(request, response) {
    response.sendFile(__dirname + '/home.html');
});

app.get("/gameOver", function(request, response) {
    response.sendFile(__dirname + '/gameOver.html');
});

app.get("/register", function(request, response) {
    response.sendFile(__dirname + '/register.html');
});

app.get("/boss", function(request, response) {
    response.sendFile(__dirname + '/boss.html');
});

app.get("/winner", function(request, response) {
    response.sendFile(__dirname + '/winner.html');
});

app.get("/forgot", function(request, response) {
    response.sendFile(__dirname + '/forgotEmail.html');
});

app.get("/reset", function(request, response) {
    response.sendFile(__dirname + '/resetPassword.html');
});
app.get("/full", function(request, response) {
    response.sendFile(__dirname + '/full.html');
});
app.get("/rules", function(request, response) {
    response.sendFile(__dirname + '/gamerules/rules.html');
});
app.use('/proxy', proxy('localhost:8088', {
    proxyReqPathResolver: function(req) {
        return new Promise(function(resolve, reject) {
            setTimeout(function() { // simulate async
                var parts = req.url.split('?');
                var queryString = parts[1];
                var updatedPath = parts[0].replace(/test/, 'tent');
                var resolvedPathValue = updatedPath + (queryString ? '?' + queryString : '');
                resolve(resolvedPathValue);
            }, 200);
        });
    }
}));


// Serve the assets directory
app.use('/assets', express.static('assets'))

// Listen on port 5000
app.set('port', (process.env.PORT || 5000));
http.listen(app.get('port'), function() {
    console.log('listening on port', app.get('port'));
});

var players = {}; //Keeps a table of all players, the key is the socket id
var players2 = {}; //Keeps a table of all players, the key is the socket id
var players3 = {}; //Keeps a table of all players, the key is the socket id

var room1 = io.of('/room1');
var nspSockets_room1 = io.of('/room1').sockets;
var nspSockets_room2 = io.of('/room2').sockets;
var nspSockets_room3 = io.of('/room3').sockets;
// Tell Socket.io to start accepting connections
room1.on('connection', function(socket) {
    // Listen for a new player trying to connect
    room1.emit('count-players', Object.keys(nspSockets_room1).length);
    if (Object.keys(nspSockets_room1).length <= 2) {
        socket.on('new-player', function(obj) {
            players[socket.id] = obj;
            // Broadcast a signal to everyone containing the updated players list
            room1.emit('update-players', players);
        })

        // Listen for a disconnection and update our player table
        socket.on('disconnect', function(state) {
            delete players[socket.id];
            room1.emit('update-players', players);
        })

        // Listen for move events and tell all other clients that something has moved
        socket.on('move-player', function(state) {
            if (players[socket.id] == undefined) return; // Happens if the server restarts and a client is still connected
            players[socket.id] = state;
            room1.emit('update-players', players);
        })
    } else {
        delete players[socket.id];
    }
})

var room2 = io.of('/room2');

// Tell Socket.io to start accepting connections
room2.on('connection', function(socket) {
    room2.emit('count-players', Object.keys(nspSockets_room2).length);

    if (Object.keys(nspSockets_room2).length <= 2) {
        Object.size = function(obj) {
            var size = 0,
                key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };
        // Listen for a new player trying to connect
        socket.on('new-player', function(obj) {
            players2[socket.id] = obj;
            // Broadcast a signal to everyone containing the updated players list
            room2.emit('update-players', players2);
        })

        // Listen for a disconnection and update our player table
        socket.on('disconnect', function(state) {
            delete players2[socket.id];
            room2.emit('update-players', players2);
        })

        // Listen for move events and tell all other clients that something has moved
        socket.on('move-player', function(state) {
            if (players2[socket.id] == undefined) return; // Happens if the server restarts and a client is still connected
            players2[socket.id] = state;
            room2.emit('update-players', players2);
        })
    }
})

var room3 = io.of('/room3');
// Tell Socket.io to start accepting connections
room3.on('connection', function(socket) {
    room3.emit('count-players', Object.keys(nspSockets_room3).length);
    if (Object.keys(nspSockets_room3).length <= 2) {

        // Listen for a new player trying to connect
        socket.on('new-player', function(obj) {
            players3[socket.id] = obj;
            // Broadcast a signal to everyone containing the updated players list
            room3.emit('update-players', players3);
        })

        // Listen for a disconnection and update our player table
        socket.on('disconnect', function(state) {
            delete players3[socket.id];
            room3.emit('update-players', players3);
        })

        // Listen for move events and tell all other clients that something has moved
        socket.on('move-player', function(state) {
            if (players3[socket.id] == undefined) return; // Happens if the server restarts and a client is still connected
            players3[socket.id] = state;
            room3.emit('update-players', players3);
        })
    }
})