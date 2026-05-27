<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Game;
use App\Livewire\Leaderboard;

Route::get('/', Game::class);
Route::get('/leaderboard', Leaderboard::class);

