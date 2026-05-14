import { io } from "socket.io-client";

const socket = io("http://localhost:3001");

const username = prompt("Enter Device Name");

socket.on("connect", () => {

    socket.emit("register", username);

    console.log("Connected");
});

socket.on("online-users", (users) => {

    const usersDiv = document.getElementById('users');

    if (!usersDiv) return;

    usersDiv.innerHTML = '';

    users.forEach(user => {

        if (user !== username) {

            const button = document.createElement('button');

            button.innerText = `Send File To ${user}`;

            button.style.display = 'block';
            button.style.margin = '10px';
            button.style.padding = '15px';

            button.onclick = async () => {

                const input = document.createElement('input');

                input.type = 'file';

                input.onchange = async (e) => {

                    const file = e.target.files[0];

                    const formData = new FormData();

                    formData.append('file', file);

                    alert('Uploading file...');

                    const response = await fetch(
                        'http://localhost:3001/upload',
                        {
                            method: 'POST',
                            body: formData
                        }
                    );

                    const result = await response.json();

                    socket.emit('send-transfer', {
                        from: username,
                        to: user,
                        fileName: result.fileName,
                        downloadUrl: result.downloadUrl
                    });

                    alert('Transfer sent');
                };

                input.click();
            };

            usersDiv.appendChild(button);
        }
    });
});

socket.on("incoming-transfer", (data) => {

    const confirmDownload = confirm(
        `Incoming File From ${data.from}\n\n${data.fileName}\n\nDownload?`
    );

    if (confirmDownload) {

        window.open(data.downloadUrl, '_blank');
    }
});
