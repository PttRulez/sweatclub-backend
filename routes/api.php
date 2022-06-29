<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BoardgameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandyCrushController;
use App\Http\Controllers\ClubController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware(['auth:sanctum'])->group(function() {
   Route::apiResource('users', UserController::class)->except('destroy');
   Route::apiResource('clubs', ClubController::class);
   Route::get('users-stats', [UserController::class, 'allUsersWithStats']);
   Route::apiResource('games', GameController::class)->only(['index', 'show']);
   Route::apiResource('boardgames', BoardgameController::class)->only(['index', 'show', 'update']);
   Route::apiResource('candy-crush', CandyCrushController::class)->only(['index', 'show', 'store']);
   Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function() {
    Route::apiResource('boardgames', BoardgameController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('games', GameController::class)->only(['store', 'update', 'destroy']);
});


