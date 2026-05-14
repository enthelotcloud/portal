import { io } from "socket.io-client";

const socket = io("http://localhost:3001");

const username = prompt("Enter Device Name");

socket.on("connect", () => {

    console.log("Connected:", socket.id);

    socket.emit("register", username);
});

socket.on("online-users", (users) => {

    console.log("Online Users:", users);

    const usersDiv = document.getElementById('users');

    if (usersDiv) {

        usersDiv.innerHTML = '';

        users.forEach(user => {

            if (user !== username) {

                usersDiv.innerHTML += `
                    <button
                        onclick="sendTransfer('${user}')"
                        style="
                            display:block;
                            margin:10px;
                            padding:15px;
                            width:250px;
                            background:#111827;
                            color:white;
                            border:none;
                            border-radius:10px;
                            cursor:pointer;
                        "
                    >
                        Send File To ${user}
                    </button>
                `;
            }
        });
    }
});

window.sendTransfer = function(receiver) {

    const fileName = prompt("Enter file name");

    socket.emit("send-transfer", {
        from: username,
        to: receiver,
        fileName: fileName
    });
}

socket.on("incoming-transfer", (data) => {

    alert(
        `Incoming file from ${data.from}\nFile: ${data.fileName}`
    );

    console.log(data);
});
