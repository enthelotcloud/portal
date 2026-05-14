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
                hidden
                lg:flex
                w-80
                bg-slate-900/90
                border-r
                border-slate-800
                p-8
                flex-col
                justify-between
            "
        >

            <div>

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
                                text-4xl
                                font-black
                                tracking-tight
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

                <!-- DEVICE FORM -->
                <div class="mt-12">

                    @if(session('device_name'))

                        <div
                            class="
                                bg-slate-800
                                border
                                border-slate-700
                                rounded-3xl
                                p-5
                            "
                        >

                            <p class="text-slate-400 text-sm">
                                Current Device
                            </p>

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    mt-3
                                "
                            >

                                <h2 class="text-2xl font-black">
                                    {{ session('device_name') }}
                                </h2>

                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-2
                                        text-green-400
                                    "
                                >

                                    <div
                                        class="
                                            w-3
                                            h-3
                                            rounded-full
                                            bg-green-500
                                        "
                                    ></div>

                                    <span class="font-semibold">
                                        Online
                                    </span>

                                </div>

                            </div>

                        </div>

                    @else

                        <form
                            id="device-form"
                            class="
                                bg-slate-800
                                border
                                border-slate-700
                                rounded-3xl
                                p-6
                                space-y-4
                            "
                        >

                            @csrf

                            <div>

                                <label
                                    class="
                                        text-sm
                                        text-slate-400
                                        block
                                        mb-2
                                    "
                                >
                                    Device Name
                                </label>

                                <input
                                    type="text"
                                    id="device-input"
                                    placeholder="TV1"
                                    class="
                                        w-full
                                        bg-slate-900
                                        border
                                        border-slate-700
                                        rounded-2xl
                                        px-5
                                        py-4
                                        outline-none
                                        focus:border-blue-500
                                    "
                                >

                            </div>

                            <button
                                class="
                                    w-full
                                    bg-blue-600
                                    hover:bg-blue-500
                                    transition-all
                                    rounded-2xl
                                    py-4
                                    font-bold
                                "
                            >
                                Connect Device
                            </button>

                        </form>

                    @endif

                </div>

            </div>

            <!-- STATUS -->
            <div
                class="
                    bg-slate-800
                    border
                    border-slate-700
                    rounded-3xl
                    p-5
                "
            >

                <p class="text-slate-400 text-sm">
                    Transfer System
                </p>

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        mt-4
                    "
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="
                                w-3
                                h-3
                                rounded-full
                                bg-green-500
                            "
                        ></div>

                        <span class="font-semibold">
                            Active
                        </span>

                    </div>

                    <span class="text-slate-500 text-sm">
                        Internal Network
                    </span>

                </div>

            </div>

        </aside>

        <!-- MAIN -->
        <main class="flex-1 p-5 md:p-8 lg:p-10 overflow-y-auto">

            <!-- MOBILE LOGO -->
            <div class="lg:hidden mb-8">

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

                        <h1 class="text-4xl font-black">
                            FileFlow
                        </h1>

                        <p class="text-slate-400 text-sm mt-1">
                            Broadcast Transfer System
                        </p>

                    </div>

                </div>

            </div>

            <!-- HEADER -->
            <div
                class="
                    flex
                    items-center
                    justify-between
                "
            >

                <div>

                    <h2
                        class="
                            text-3xl
                            md:text-5xl
                            font-black
                            tracking-tight
                        "
                    >
                        Online Devices
                    </h2>

                    <p
                        class="
                            text-slate-400
                            mt-3
                            text-base
                            md:text-lg
                        "
                    >
                        Live broadcast systems connected to FileFlow
                    </p>

                </div>

            </div>

            <!-- USERS -->
            <div
                id="users"
                class="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    xl:grid-cols-3
                    gap-6
                    mt-12
                "
            ></div>

            <!-- FILE TRANSFER PANEL -->

            <div
                class="
                    mt-12
                    bg-slate-900
                    border
                    border-slate-800
                    rounded-3xl
                    p-5
                    md:p-8
                "
            >

                <div
                    class="
                        flex
                        flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-6
                    "
                >

                    <div>

                        <h2
                            class="
                                text-2xl
                                md:text-3xl
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
                            Drag files or select files to transfer instantly
                        </p>

                    </div>

                </div>

                <!-- DROP ZONE -->

                <label
                    id="drop-zone"
                    class="
                        mt-8
                        border-2
                        border-dashed
                        border-slate-700
                        hover:border-blue-500
                        transition-all
                        rounded-3xl
                        p-8
                        md:p-16
                        flex
                        flex-col
                        items-center
                        justify-center
                        text-center
                        cursor-pointer
                        bg-slate-950/50
                    "
                >

                    <input
                        type="file"
                        id="file-input"
                        class="hidden"
                    >

                    <div class="text-6xl md:text-7xl">
                        📂
                    </div>

                    <h3
                        class="
                            text-xl
                            md:text-2xl
                            font-black
                            mt-6
                        "
                    >
                        Drag & Drop Files
                    </h3>

                    <p
                        class="
                            text-slate-400
                            mt-3
                            text-sm
                            md:text-base
                        "
                    >
                        or click here to browse files
                    </p>

                </label>

                <!-- FILE NAME -->

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
                            Transfer Progress
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
                                duration-300
                            "
                            style="width:0%"
                        ></div>

                    </div>

                </div>

            </div>

            <!-- ACTIVITY -->
            <div class="mt-16">

                <div
                    class="
                        flex
                        items-center
                        justify-between
                    "
                >

                    <h2
                        class="
                            text-3xl
                            font-black
                        "
                    >
                        Recent Activity
                    </h2>

                </div>

                <div class="space-y-5 mt-8">

                    <div
                        class="
                            bg-slate-900
                            border
                            border-slate-800
                            rounded-3xl
                            p-6
                        "
                    >

                        <p class="font-semibold text-lg">
                            TV1 transferred morning_news.mp4
                        </p>

                        <p class="text-slate-500 mt-2">
                            4.2 GB • 2 mins ago
                        </p>

                    </div>

                    <div
                        class="
                            bg-slate-900
                            border
                            border-slate-800
                            rounded-3xl
                            p-6
                        "
                    >

                        <p class="font-semibold text-lg">
                            RADIO uploaded intro.wav
                        </p>

                        <p class="text-slate-500 mt-2">
                            120 MB • 18 mins ago
                        </p>

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>
</html>
