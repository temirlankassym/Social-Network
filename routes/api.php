<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConnectController;
use App\Http\Controllers\InteractionController;

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::post('/post', [PostController::class, 'create']);
    Route::delete('/post/{post}', [PostController::class, 'destroy']);
    Route::get('/posts', [PostController::class, 'getAllPosts']);

    Route::post('/follow', [ConnectController::class, 'follow']);
    Route::post('/unfollow', [ConnectController::class, 'unfollow']);

    Route::post('/like/{post}', [InteractionController::class, 'like']);
    Route::post('/unlike/{post}', [InteractionController::class, 'unlike']);
});
