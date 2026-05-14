<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/device-name', function (Request $request) {

    session([
        'device_name' => $request->device_name
    ]);

    return response()->json([
        'success' => true
    ]);
});

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
