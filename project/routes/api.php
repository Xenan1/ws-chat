<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialController;
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
        Route::get('me', [AuthController::class, 'me'])->name('me');

    });
});

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::prefix('chat')->group(function () {
        Route::post('messages', [ChatController::class, 'createMessage'])->name('newMessage');
        Route::get('dialog', [ChatController::class, 'getDialog'])->name('dialog');
        Route::get('members', [ChatController::class, 'getChats'])->name('chats');
    });

    Route::prefix('feed')->group(function () {
        Route::get('/', [FeedController::class, 'getFeed']);

        Route::prefix('like')->group(function () {
            Route::post('/', [FeedController::class, 'likePost']);
            Route::delete('/', [FeedController::class, 'unlikePost']);
        });

        Route::post('posts', [PostController::class, 'createPost']);
    });

    Route::prefix('profile')->group(function () {
        Route::post('avatar', [ProfileController::class, 'uploadAvatar']);
    });
});

Route::get('weather', [SpecialController::class, 'getWeather']);
