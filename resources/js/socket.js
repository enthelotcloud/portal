import { io } from "socket.io-client";

const usernameMeta = document.querySelector(
    'meta[name="device-name"]'
);

const username = usernameMeta
    ? usernameMeta.content
    : null;

if (username) {

    const socket = io("http://localhost:3001");

    socket.on("connect", () => {

        socket.emit("register", username);
    });

    socket.on("online-users", (users) => {

        const usersDiv = document.getElementById('users');

        if (!usersDiv) return;

        usersDiv.innerHTML = '';

        users.forEach(user => {

            if (user !== username) {

                const card = document.createElement('div');

                card.className = `
                    bg-slate-900
                    border
                    border-slate-800
                    rounded-3xl
                    p-6
                `;

                card.innerHTML = `
                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-3xl font-black">
                                ${user}
                            </h2>

                            <div class="flex items-center mt-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>

                                <span class="text-slate-400">
                                    Online
                                </span>
                            </div>

                        </div>

                    </div>

                    <button
                        class="
                            send-btn
                            mt-8
                            w-full
                            bg-blue-600
                            hover:bg-blue-500
                            transition-all
                            rounded-2xl
                            py-4
                            font-bold
                            text-lg
                        "
                    >
                        Send File
                    </button>
                `;

                const button = card.querySelector('.send-btn');

                button.onclick = async () => {

                    const input = document.createElement('input');

                    input.type = 'file';

                    input.onchange = async (e) => {

                        const file = e.target.files[0];

                        const formData = new FormData();

                        formData.append('file', file);

                        button.innerText = 'Uploading...';

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

                        button.innerText = 'Transfer Sent';
                    };

                    input.click();
                };

                usersDiv.appendChild(card);
            }
        });
    });

    socket.on("incoming-transfer", (data) => {

        const accept = confirm(
            `Incoming File\n\n${data.fileName}\n\nFrom ${data.from}`
        );

        if (accept) {

            window.open(data.downloadUrl, '_blank');
        }
    });
}

const form = document.getElementById('device-form');

if (form) {

    form.addEventListener('submit', async (e) => {

        e.preventDefault();

        const value = document
            .getElementById('device-input')
            .value;

        await fetch('/device-name', {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },

            body: JSON.stringify({
                device_name: value
            })
        });

        location.reload();
    });
}
