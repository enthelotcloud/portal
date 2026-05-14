<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    @if(session('device_name'))

        <meta
            name="device-name"
            content="{{ session('device_name') }}"
        >

    @endif

    <title>FileFlow</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-slate-950 text-white min-h-screen">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->

    <aside
        class="
            w-full
            lg:w-80
            bg-slate-900
            border-r
            border-slate-800
            flex
            flex-col
        "
    >

        <!-- LOGO -->

        <div
            class="
                p-6
                border-b
                border-slate-800
            "
        >

            <div class="flex items-center gap-4">

                <div
                    class="
                        w-14
                        h-14
                        rounded-2xl
                        bg-blue-600
                        flex
                        items-center
                        justify-center
                        text-2xl
                        font-black
                    "
                >
                    F
                </div>

                <div>

                    <h1
                        class="
                            text-3xl
                            font-black
                        "
                    >
                        FileFlow
                    </h1>

                    <p
                        class="
                            text-slate-400
                            text-sm
                            mt-1
                        "
                    >
                        Broadcast Transfer System
                    </p>

                </div>

            </div>

        </div>

        <!-- DEVICE -->

        <div class="p-6 border-b border-slate-800">

            @if(session('device_name'))

                <div
                    class="
                        bg-slate-800
                        rounded-3xl
                        p-5
                    "
                >

                    <p class="text-slate-400 text-sm">
                        Connected As
                    </p>

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            mt-3
                        "
                    >

                        <h2
                            class="
                                text-2xl
                                font-black
                            "
                        >
                            {{ session('device_name') }}
                        </h2>

                        <div
                            class="
                                w-3
                                h-3
                                rounded-full
                                bg-green-500
                            "
                        ></div>

                    </div>

                </div>

            @else

                <form
                    id="device-form"
                    class="space-y-4"
                >

                    @csrf

                    <input
                        type="text"
                        id="device-input"
                        placeholder="TV1"
                        class="
                            w-full
                            bg-slate-800
                            border
                            border-slate-700
                            rounded-2xl
                            px-5
                            py-4
                            outline-none
                        "
                    >

                    <button
                        class="
                            w-full
                            bg-blue-600
                            hover:bg-blue-500
                            rounded-2xl
                            py-4
                            font-bold
                            transition-all
                        "
                    >
                        Connect Device
                    </button>

                </form>

            @endif

        </div>

        <!-- USERS -->

        <div
            class="
                flex-1
                overflow-y-auto
                p-6
            "
        >

            <div
                class="
                    flex
                    items-center
                    justify-between
                    mb-6
                "
            >

                <h2
                    class="
                        text-xl
                        font-black
                    "
                >
                    Online Devices
                </h2>

                <div
                    class="
                        w-3
                        h-3
                        rounded-full
                        bg-green-500
                    "
                ></div>

            </div>

            <div
                id="users"
                class="space-y-4"
            ></div>

        </div>

    </aside>

    <!-- MAIN -->

    <main
        class="
            flex-1
            p-5
            md:p-10
        "
    >

        <!-- SELECTED -->

        <div
            class="
                bg-slate-900
                border
                border-slate-800
                rounded-3xl
                p-6
            "
        >

            <p
                class="
                    text-slate-400
                    text-sm
                "
            >
                Selected Receiver
            </p>

            <div
                class="
                    flex
                    items-center
                    justify-between
                    mt-4
                "
            >

                <div>

                    <h2
                        id="selected-user"
                        class="
                            text-3xl
                            font-black
                        "
                    >
                        No Device Selected
                    </h2>

                    <p
                        class="
                            text-slate-500
                            mt-2
                        "
                    >
                        Select a device from the sidebar
                    </p>

                </div>

                <div
                    id="receiver-indicator"
                    class="
                        w-4
                        h-4
                        rounded-full
                        bg-slate-700
                    "
                ></div>

            </div>

        </div>

        <!-- TRANSFER PANEL -->

        <div
            class="
                mt-8
                bg-slate-900
                border
                border-slate-800
                rounded-3xl
                p-6
            "
        >

            <div>

                <h2
                    class="
                        text-3xl
                        font-black
                    "
                >
                    Send Files
                </h2>

                <p
                    class="
                        text-slate-400
                        mt-2
                    "
                >
                    Drag & drop files instantly
                </p>

            </div>

            <!-- DROPZONE -->

            <label
                id="drop-zone"
                class="
                    mt-8
                    h-[250px]
                    border-2
                    border-dashed
                    border-slate-700
                    hover:border-blue-500
                    transition-all
                    rounded-3xl
                    flex
                    flex-col
                    items-center
                    justify-center
                    text-center
                    cursor-pointer
                    bg-slate-950/40
                "
            >

                <input
                    type="file"
                    id="file-input"
                    class="hidden"
                >

                <div class="text-6xl">
                    📂
                </div>

                <h2
                    class="
                        text-2xl
                        font-black
                        mt-5
                    "
                >
                    Drop Files Here
                </h2>

                <p
                    class="
                        text-slate-400
                        mt-3
                    "
                >
                    or click to browse
                </p>

            </label>

            <!-- FILE -->

            <div
                class="
                    mt-6
                    bg-slate-950
                    border
                    border-slate-800
                    rounded-2xl
                    p-5
                "
            >

                <p
                    class="
                        text-slate-400
                        text-sm
                    "
                >
                    Selected File
                </p>

                <p
                    id="selected-file"
                    class="
                        mt-2
                        font-semibold
                        break-all
                    "
                >
                    No file selected
                </p>

            </div>

            <!-- PROGRESS -->

            <div class="mt-8">

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        mb-3
                    "
                >

                    <span class="font-semibold">
                        Upload Progress
                    </span>

                    <span
                        id="progress-text"
                        class="text-slate-400"
                    >
                        0%
                    </span>

                </div>

                <div
                    class="
                        w-full
                        h-5
                        bg-slate-800
                        rounded-full
                        overflow-hidden
                    "
                >

                    <div
                        id="progress-bar"
                        class="
                            h-full
                            bg-blue-600
                            rounded-full
                            transition-all
                        "
                        style="width:0%"
                    ></div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>
