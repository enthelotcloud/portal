import io from "socket.io-client";

/*
|--------------------------------------------------------------------------
| SOCKET CONNECTION
|--------------------------------------------------------------------------
*/

const socket = io(
    "http://178.104.181.9:3001"
);

window.socket = socket;

/*
|--------------------------------------------------------------------------
| CURRENT DEVICE
|--------------------------------------------------------------------------
*/

const currentDevice =
    document
        .querySelector(
            'meta[name="device-name"]'
        )
        ?.getAttribute("content");

/*
/*
|--------------------------------------------------------------------------
| REGISTER DEVICE
|--------------------------------------------------------------------------
*/

socket.on("connect", () => {

    console.log(
        "Socket connected:",
        socket.id
    );

    if (currentDevice) {

        socket.emit(
            "register",
            currentDevice
        );
    }
});

/*
|--------------------------------------------------------------------------
| DEVICE FORM
|--------------------------------------------------------------------------
*/

const deviceForm =
    document.getElementById(
        "device-form"
    );

if (deviceForm) {

    deviceForm.addEventListener(
        "submit",
        async (e) => {

            e.preventDefault();

            const input =
                document.getElementById(
                    "device-input"
                );

            const name =
                input.value.trim();

            if (!name) {

                alert(
                    "Please enter a device name."
                );

                return;
            }

            await fetch(
                "/device-name",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json",

                        "X-CSRF-TOKEN":
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .content
                    },

                    body: JSON.stringify({
                        device_name: name
                    })
                }
            );

            location.reload();
        }
    );
}

/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/

let selectedReceiver = null;

socket.on(
    "users",
    (users) => {

        const usersContainer =
            document.getElementById(
                "users"
            );

        if (!usersContainer) return;

        usersContainer.innerHTML = "";

        users.forEach((user) => {

            if (
                user === currentDevice
            ) return;

            const card =
                document.createElement(
                    "div"
                );

            card.className =
                `
                bg-slate-800
                border
                border-slate-700
                rounded-3xl
                p-5
                cursor-pointer
                hover:border-blue-500
                transition-all
                `;

            card.innerHTML = `
                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-2xl font-black">
                            ${user}
                        </h2>

                        <p class="text-slate-400 mt-2 text-sm">
                            Online Device
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
            `;

            card.addEventListener(
                "click",
                () => {

                    selectedReceiver =
                        user;

                    document.getElementById(
                        "selected-user"
                    ).innerText =
                        user;

                    const indicator =
                        document.getElementById(
                            "receiver-indicator"
                        );

                    indicator.classList.remove(
                        "bg-slate-700"
                    );

                    indicator.classList.add(
                        "bg-green-500"
                    );

                    document
                        .querySelectorAll(
                            "#users > div"
                        )
                        .forEach((el) => {

                            el.classList.remove(
                                "border-blue-500"
                            );
                        });

                    card.classList.add(
                        "border-blue-500"
                    );
                }
            );

            usersContainer.appendChild(
                card
            );
        });
    }
);

/*
|--------------------------------------------------------------------------
| FILE ELEMENTS
|--------------------------------------------------------------------------
*/

const fileInput =
    document.getElementById(
        "file-input"
    );

const dropZone =
    document.getElementById(
        "drop-zone"
    );

const selectedFileText =
    document.getElementById(
        "selected-file"
    );

const progressBar =
    document.getElementById(
        "progress-bar"
    );

const progressText =
    document.getElementById(
        "progress-text"
    );

/*
|--------------------------------------------------------------------------
| FILE UPLOAD
|--------------------------------------------------------------------------
*/

function uploadFile(file) {

    if (!selectedReceiver) {

        alert(
            "Please select a receiver first."
        );

        return;
    }

    const formData =
        new FormData();

    formData.append(
        "file",
        file
    );

    const xhr =
        new XMLHttpRequest();

    xhr.open(
        "POST",
        "https://portal.nyumbaiitutv.co.ke/upload"
    );

    xhr.upload.addEventListener(
        "progress",
        (e) => {

            if (
                e.lengthComputable
            ) {

                const percent =
                    Math.round(
                        (
                            e.loaded /
                            e.total
                        ) * 100
                    );

                progressBar.style.width =
                    percent + "%";

                progressText.innerText =
                    percent + "%";
            }
        }
    );

    xhr.onload = () => {

        const response =
            JSON.parse(
                xhr.responseText
            );

        socket.emit(
            "send-transfer",
            {
                from:
                    currentDevice,

                to:
                    selectedReceiver,

                fileName:
                    response.fileName,

                downloadUrl:
                    response.downloadUrl
            }
        );

        progressBar.style.width =
            "100%";

        progressText.innerText =
            "100%";

        alert(
            "File sent successfully."
        );
    };

    xhr.send(formData);
}

/*
|--------------------------------------------------------------------------
| FILE INPUT
|--------------------------------------------------------------------------
*/

if (fileInput) {

    fileInput.addEventListener(
        "change",
        (e) => {

            const file =
                e.target.files[0];

            if (!file) return;

            selectedFileText.innerText =
                file.name;

            uploadFile(file);
        }
    );
}

/*
|--------------------------------------------------------------------------
| DRAG & DROP
|--------------------------------------------------------------------------
*/

if (dropZone) {

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

            const file =
                e.dataTransfer
                    .files[0];

            if (!file) return;

            selectedFileText.innerText =
                file.name;

            uploadFile(file);
        }
    );
}

/*
|--------------------------------------------------------------------------
| RECEIVE FILE
|--------------------------------------------------------------------------
*/

socket.on(
    "incoming-transfer",
    (data) => {

        const link =
            document.createElement(
                "a"
            );

        link.href =
            data.downloadUrl;

        link.download =
            data.fileName;

        document.body.appendChild(
            link
        );

        link.click();

        link.remove();

        alert(
            `${data.from} sent ${data.fileName}`
        );
    }
);
