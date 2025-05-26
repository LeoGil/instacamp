<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [PostController::class, 'index'])->name('home');
    Route::resource('posts', PostController::class);
    Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);
    Route::resource('posts.comments', CommentController::class)->only(['store', 'destroy']);
    Route::resource('likes', LikeController::class)->only(['store', 'destroy']);
});
