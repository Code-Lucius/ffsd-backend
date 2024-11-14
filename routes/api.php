<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //Route to create a post
    Route::post('/create_post', [PostController::class, 'create']);
    // Route to update a post
    Route::put('/posts/{id}', [PostController::class, 'update']);

    // Route to delete a post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});


Route::get('/posts', [PostController::class, 'index']);


Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])
     ->middleware(['signed', 'throttle:6,1'])
     ->name('verification.verify');