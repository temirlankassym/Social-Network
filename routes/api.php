<?php

use App\Http\Controllers\AccountManagerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConnectController;
use App\Http\Controllers\InteractionController;

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::get('/profile/{username}', [ProfileController::class, 'profile']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::get('/profile', [ProfileController::class, 'show']);

    Route::post('/post', [PostController::class, 'create']);
    Route::get('/posts', [PostController::class, 'getAllPosts']);
    Route::delete('/post/{post}', [PostController::class, 'destroy']);
    Route::get('/post/{post}', [PostController::class, 'show']);

    Route::post('/follow', [ConnectController::class, 'follow']);
    Route::post('/unfollow', [ConnectController::class, 'unfollow']);

    Route::post('/like/{post}', [InteractionController::class, 'like']);
    Route::post('/unlike/{post}', [InteractionController::class, 'unlike']);

    Route::post('/comment', [InteractionController::class, 'comment']);
    Route::post('/uncomment', [InteractionController::class, 'uncomment']);

    Route::get('/direct', [MessageController::class, 'index']);
    Route::get('/direct/{username}', [MessageController::class, 'show']);
    Route::post('/direct/{username}/message', [ChatController::class, 'postMessage']);

    Route::post('/repost', [InteractionController::class, 'repost']);

    Route::post('/subscribe', [ProfileController::class, 'subscribe']);
    Route::post('/unsubscribe', [ProfileController::class, 'unsubscribe']);

    Route::get('/subscribers', [ProfileController::class, 'subscribers']);

    // not implemented
    Route::get('/feed', [FeedController::class, 'getFeed']);
    Route::get('/account/private', [AccountManagerController::class, 'makePrivate']);
    Route::get('/account/public', [AccountManagerController::class, 'makePublic']);
});
