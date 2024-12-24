<?php

use Illuminate\Support\Facades\Route;

Route::name('web.')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('login', function () {
        return view('login');
    })->name('login');

    Route::get('chat', function () {
        return view('chat');
    })->name('chat');
});
