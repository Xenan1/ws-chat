<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);

    });
});

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::prefix('chat')->group(function () {
        Route::post('messages', [ChatController::class, 'createMessage']);
        Route::get('dialog', [ChatController::class, 'getDialog']);
    });

    Route::prefix('feed')->group(function () {
        Route::get('/', [FeedController::class, 'getFeed']);
        Route::post('like', [FeedController::class, 'likePost']);
    });
});
