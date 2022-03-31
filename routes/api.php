<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BoardgameController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware(['auth:sanctum'])->group(function() {
   Route::get('users', [UserController::class, 'index']);

   Route::post('logout', [AuthController::class, 'logout']);
   Route::apiResource('games', GameController::class)->only(['index', 'show']);
   Route::apiResource('boardgames', BoardgameController::class)->only(['index', 'show']);

   Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function() {
    Route::apiResource('boardgames', BoardgameController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('games', GameController::class)->only(['store', 'update', 'destroy']);
});


