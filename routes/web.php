<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [GameController::class, 'index']);
Route::post('/play', [GameController::class, 'play']);
Route::get('/game/leaderboard', [GameController::class, 'getLeaderboard']);
Route::post('/game/reset', [GameController::class, 'resetSession']);

