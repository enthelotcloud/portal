<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white min-h-screen">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">

            <h1 class="text-4xl font-black tracking-tight">
                FileFlow
            </h1>

            <p class="text-slate-400 mt-2 text-sm">
                Broadcast Transfer System
            </p>

            <div class="mt-10">

                <h2 class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                    System Status
                </h2>

                <div class="space-y-4">

                    <div class="bg-slate-800 rounded-2xl p-4">
                        <p class="text-sm text-slate-400">
                            Socket Server
                        </p>

                        <div class="flex items-center mt-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>

                            <span class="font-semibold">
                                Online
                            </span>
                        </div>
                    </div>

                    <div class="bg-slate-800 rounded-2xl p-4">
                        <p class="text-sm text-slate-400">
                            Transfers Today
                        </p>

                        <p class="text-3xl font-black mt-2">
                            12
                        </p>
                    </div>

                </div>

            </div>

        </aside>

        <!-- MAIN -->
        <main class="flex-1 p-8">

            <!-- TOP -->
            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-3xl font-black">
                        Online Devices
                    </h2>

                    <p class="text-slate-400 mt-1">
                        Live connected systems
                    </p>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3">
                    <span id="device-name" class="font-bold"></span>
                </div>

            </div>

            <!-- DEVICES -->
            <div
                id="users"
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-10"
            >
            </div>

            <!-- RECENT -->
            <div class="mt-14">

                <h2 class="text-2xl font-black">
                    Recent Activity
                </h2>

                <div class="mt-6 space-y-4">

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <p class="font-semibold">
                            TV1 sent morning_news.mp4 to TV2
                        </p>

                        <p class="text-slate-500 text-sm mt-1">
                            2 minutes ago
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <p class="font-semibold">
                            RADIO uploaded intro.wav
                        </p>

                        <p class="text-slate-500 text-sm mt-1">
                            12 minutes ago
                        </p>
                    </div>

                </div>

            </div>

        </main>

    </div>

</body>
</html>
