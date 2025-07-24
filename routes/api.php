<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradelevelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/search/{id}', [AuthController::class, 'search']);
    Route::get('/user/{id}', [AuthController::class, 'show']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::delete('/user', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/grade_level', [GradelevelController::class, 'store']);
    Route::get('/grade_levels', [GradelevelController::class, 'index']);
    Route::get('/grade_level/{gradelevel}', [GradelevelController::class, 'show']);
    Route::delete('/gradelevel/{id}', [GradelevelController::class, 'destroy']);

    Route::post('/subject', [SubjectController::class, 'store']);
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::delete('/subject/{id}', [SubjectController::class, 'destroy']);


    Route::post('/post', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/post/{id}', [PostController::class, 'show']);
    Route::put('/post/{id}', [PostController::class, 'update']);
    Route::delete('/post/{id}', [PostController::class, 'destroy']);

    Route::post('/comment', [CommentController::class, 'store']);
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comment/{id}', [CommentController::class, 'show']);
    Route::put('/comment/{id}', [CommentController::class, 'update']);
    Route::delete('/comment/{id}', [CommentController::class, 'destroy']);

    Route::post('/message', [MessageController::class, 'store']);
    Route::put('/message/{id}', [MessageController::class, 'update']);
    Route::get('/conversation/{receiverId}', [MessageController::class, 'getConversation']);
    Route::delete('/message/{id}', [MessageController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
});
