// Require the packages we will use:
const http = require("http"),
    fs = require("fs");

const port = 3456;
const file = "home.html";
// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html, on port 3456:
const server = http.createServer(function (req, res) {
    // This callback runs when a new connection is made to our HTTP server.

    fs.readFile(file, function (err, data) {
        // This callback runs when the client.html file has been read from the filesystem.

        if (err) return res.writeHead(500);
        res.writeHead(200);
        res.end(data);
    });
});
server.listen(port);

// Import Socket.IO and pass our HTTP server object to it.
const socketio = require("socket.io")(http, {
    wsEngine: 'ws'
});

// Attach our Socket.IO server to our HTTP server to listen
const io = socketio.listen(server);
var usernames = {};
var rooms = {};
io.sockets.on("connection", function (socket) {
    // This callback runs when a new Socket.IO connection is established.
    //console.log(JSON.stringify(rooms, null, 2));
    socket.on('message_to_server', function (data) {
        // This callback runs when the server receives a new message from the client.

        console.log("message: " + data["message"] + " in room " + data['room']); // log it to the Node.JS output
        io.sockets.in(socket.room).emit("message_to_client", { message: socket.nickname + ": " + data["message"] }) // broadcast the message to other users
    });
    socket.on('create_user', function (data) {
        let nickname = data['name'];
        usernames[nickname] = socket.id;
        socket.nickname = nickname;
        // socket.room = "default";
        // socket.join(socket.room);
        io.sockets.in(socket.id).emit("user_added", { name: socket.nickname, room: socket.room });
    });
    socket.on('create_room', function (data) {
        //console.log("in create room: " + JSON.stringify(data, null, 2));
        // socket.leave(socket.room);
        // socket.join(data['name']);
        rooms[data['name']] = data;
        socket.room = data['name'];
        //TODO should this be all sockets?
        io.sockets.in(socket.id).emit("room_created", { 
            name: data["name"],
            owner: data["owner"],
            blockedUsers: data["blockedUsers"],
            activeUsers:data["activeUsers"],
            password: data["password"],
            isPasswordProtect: data["isPasswordProtect"]
         })        
         //console.log("Rooms after create room: " + JSON.stringify(rooms, null, 2));

    });
    socket.on('add_blocked_user', function (data) {
        try {
            rooms[socket.room]["blockedUsers"].push(data["blockedUsername"]);
            io.emit("user_blocked", {"blockedUser" : data["blockedUsername"]});
            console.log("blocking user: " + rooms[socket.room]["blockedUsers"]);
        } catch(e) {
            e; // => ReferenceError
            io.sockets.in(socket.id).emit("error", {
                message: "Error: " + e
            });
            console.log('error: ' + e);
        }
    });
    socket.on('join_room', function (data) {
        // console.log("join_room data: " + JSON.stringify(data, null, 2));
        // console.log("rooms: " + JSON.stringify(rooms, null, 2));
        try {

            if((data['enteredPassword'] == rooms[data['roomName']]['password'] || !rooms[data['roomName']]['isPasswordProtect'])
                    && !rooms[data['roomName']]['blockedUsers'].includes(socket.nickname)) {
                // if(data['enteredPassword'] == rooms[data['roomName']]['password']) {
                    //Remove user from active users list of previous room
                    rooms[data['roomName']]['activeUsers'] = rooms[data['roomName']]['activeUsers'].filter(function(value, index, arr){ 
                        return value != socket.nickname;
                    });
                    //leave previous room
                    socket.leave(socket.room);

                    //join new room
                    socket.join(data['roomName']);
                    socket.room = data['roomName'];
                    //add user to activeUsers list of new room
                    rooms[data['roomName']]['activeUsers'].push(socket.nickname);
                    //TODO pass in room object here.
                    io.sockets.in(socket.room).emit("room_entry", rooms[socket.room]);
                    //TODO Also emit an event to the previous room to update active user list
            } 
            else {
                io.sockets.in(socket.id).emit("bad_room_entry",{
                    message: "wrong password or user is blocked!"
                });

                console.log("bad room_entry request");
            }
        } catch(e) {
            e; // => ReferenceError
            io.sockets.in(socket.id).emit("bad_room_entry", {
                message: "room doesn't exist!"
            });
            console.log('error: ' + e);
        }
    });
    //kicks user
    socket.on("kick_me", function (data){
        console.log(socket.nickname +" is getting kicked from "+ socket.room);
        socket.leave(socket.room);
        socket.room="null";
        io.sockets.in(socket.id).emit("room_kicked", {name:data['name']})
    });
    //tells user it is about to get kicked
    socket.on("kick_user", function (data) {
        io.sockets.in(usernames[data['user']]).emit("getting_kicked",{name:socket.nickname});
    });
    socket.on("send_dm", function (data) {
        io.sockets.in(socket.id).in(usernames[data['user']]).emit("message_to_client", { message:  socket.nickname + ": DM: "+ data["message"] })
    });
})