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
                w-80
                bg-slate-900/90
                border-r
                border-slate-800
                p-8
                flex
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
        <main class="flex-1 p-10 overflow-y-auto">

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
                            text-5xl
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
                            text-lg
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
                    gap-8
                    mt-12
                "
            ></div>

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
