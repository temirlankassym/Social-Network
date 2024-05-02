<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConnectController;

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::post('/post', [PostController::class, 'create']);
    Route::delete('/post/{post}', [PostController::class, 'destroy']);
    Route::get('/posts', [PostController::class, 'getAllPosts']);

    Route::post('/follow', [ConnectController::class, 'follow']);
    Route::post('/unfollow', [ConnectController::class, 'unfollow']);
});
