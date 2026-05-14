import { io } from "socket.io-client";

const socket = io("https://portal.nyumbaiitutv.co.ke", {
    transports: ["websocket", "polling"]
});

window.socket = socket;

socket.on("connect", () => {

    console.log("Connected:", socket.id);

    const deviceName =
        document
            .querySelector('meta[name="device-name"]')
            ?.content;

    if (deviceName) {

        socket.emit(
            "register",
            deviceName
        );
    }
});

socket.on("online-users", (users) => {

    const usersDiv =
        document.getElementById("users");

    if (!usersDiv) return;

    usersDiv.innerHTML = "";

    const currentDevice =
        document
            .querySelector('meta[name="device-name"]')
            ?.content;

    users.forEach((user) => {

        if (user === currentDevice) return;

        const card =
            document.createElement("div");

        card.className =
            `
            bg-slate-900
            border
            border-slate-800
            rounded-3xl
            p-6
            flex
            flex-col
            gap-5
            `;

        card.innerHTML = `
            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-2xl font-black">
                        ${user}
                    </h3>

                    <p class="text-slate-400 mt-1">
                        Ready to receive files
                    </p>

                </div>

                <div
                    class="
                        w-3
                        h-3
                        rounded-full
                        bg-green-500
                    "
                ></div>

            </div>

            <button
                class="
                    send-btn
                    bg-blue-600
                    hover:bg-blue-500
                    transition-all
                    rounded-2xl
                    py-4
                    font-bold
                "
                data-user="${user}"
            >
                Send File
            </button>
        `;

        usersDiv.appendChild(card);
    });

    attachSendEvents();
});

function attachSendEvents() {

    document
        .querySelectorAll(".send-btn")
        .forEach((button) => {

            button.onclick = async () => {

                const to =
                    button.dataset.user;

                const fileInput =
                    document.getElementById(
                        "file-input"
                    );

                const file =
                    fileInput.files[0];

                if (!file) {

                    alert(
                        "Select a file first"
                    );

                    return;
                }

                const formData =
                    new FormData();

                formData.append(
                    "file",
                    file
                );

                const progressBar =
                    document.getElementById(
                        "progress-bar"
                    );

                const progressText =
                    document.getElementById(
                        "progress-text"
                    );

                const xhr =
                    new XMLHttpRequest();

                xhr.upload.onprogress = (
                    event
                ) => {

                    if (
                        event.lengthComputable
                    ) {

                        const percent =
                            Math.round(
                                (
                                    event.loaded /
                                    event.total
                                ) * 100
                            );

                        progressBar.style.width =
                            percent + "%";

                        progressText.innerText =
                            percent + "%";
                    }
                };

                xhr.onload = () => {

                    const response =
                        JSON.parse(
                            xhr.responseText
                        );

                    socket.emit(
                        "send-transfer",
                        {
                            to,
                            from:
                                document
                                    .querySelector(
                                        'meta[name="device-name"]'
                                    )
                                    ?.content,
                            fileName:
                                response.fileName,
                            downloadUrl:
                                response.downloadUrl
                        }
                    );

                    progressText.innerText =
                        "Completed";

                    alert(
                        "File sent successfully"
                    );
                };

                xhr.open(
                    "POST",
                    "https://portal.nyumbaiitutv.co.ke/upload"
                );

                xhr.send(formData);
            };
        });
}

socket.on(
    "incoming-transfer",
    (data) => {

        const autoDownload =
            confirm(
                `${data.from} sent ${data.fileName}\n\nDownload now?`
            );

        if (autoDownload) {

            const a =
                document.createElement("a");

            a.href =
                data.downloadUrl;

            a.download =
                data.fileName;

            document.body.appendChild(a);

            a.click();

            a.remove();
        }
    }
);

/*
|--------------------------------------------------------------------------
| DRAG & DROP
|--------------------------------------------------------------------------
*/

const dropZone =
    document.getElementById(
        "drop-zone"
    );

const fileInput =
    document.getElementById(
        "file-input"
    );

const selectedFile =
    document.getElementById(
        "selected-file"
    );

if (dropZone && fileInput) {

    dropZone.addEventListener(
        "dragover",
        (e) => {

            e.preventDefault();

            dropZone.classList.add(
                "border-blue-500"
            );
        }
    );

    dropZone.addEventListener(
        "dragleave",
        () => {

            dropZone.classList.remove(
                "border-blue-500"
            );
        }
    );

    dropZone.addEventListener(
        "drop",
        (e) => {

            e.preventDefault();

            dropZone.classList.remove(
                "border-blue-500"
            );

            fileInput.files =
                e.dataTransfer.files;

            updateFileName();
        }
    );

    fileInput.addEventListener(
        "change",
        updateFileName
    );
}

function updateFileName() {

    if (fileInput.files.length > 0) {

        selectedFile.innerText =
            fileInput.files[0].name;
    }
}
