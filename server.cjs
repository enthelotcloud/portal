const { Server } = require("socket.io");

const io = new Server(3001, {
    cors: {
        origin: "*"
    }
});

let users = {};

io.on("connection", (socket) => {

    console.log("User connected:", socket.id);

    socket.on("register", (name) => {
        users[name] = socket.id;

        io.emit("online-users", Object.keys(users));

        console.log(users);
    });

    socket.on("send-transfer", (data) => {

        const receiverSocket = users[data.to];

        if (receiverSocket) {
            io.to(receiverSocket).emit("incoming-transfer", {
                from: data.from,
                fileName: data.fileName
            });
        }
    });

    socket.on("disconnect", () => {

        for (let name in users) {
            if (users[name] === socket.id) {
                delete users[name];
            }
        }

        io.emit("online-users", Object.keys(users));

        console.log("Disconnected:", socket.id);
    });
});
