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
Route::get('check-auth', [AuthController::class, 'checkAuth']);
Route::get('php-info', [UserController::class, 'php_info']);

Route::apiResource('users', UserController::class)->except(['update']);
Route::apiResource('clubs', ClubController::class)->only('index');
Route::get('users-stats', [UserController::class, 'allUsersWithStats']);
Route::apiResource('games', GameController::class)->only(['index', 'show']);
Route::apiResource('boardgames', BoardgameController::class)->only(['index', 'show']);
Route::apiResource('candy-crush', CandyCrushController::class)->only(['index', 'show', 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class)->only(['update']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
    Route::apiResource('boardgames', BoardgameController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('games', GameController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('clubs', ClubController::class)->only(['store', 'update'. 'destroy']);
});


