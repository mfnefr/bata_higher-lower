<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Game;

Route::get('/game', Game::class);

Route::get('/', function () {
    return view('welcome');
});
