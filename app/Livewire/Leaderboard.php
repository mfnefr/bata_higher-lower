<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Leaderboard extends Component{

    public $leaderboard = [];

    public function mount(){
        $this->allTime();
    }

    public function allTime(){
        $this->leaderboard = Player::orderBy('score', 'desc')->take(10)->get();
    }

    public function thisYear(){
        $this->leaderboard = Player::whereYear('updated_at', now()->year)
            ->orderByDesc('score')
            ->take(10)
            ->get();
    }

    public function thisMonth(){
        $this->leaderboard = Player::whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->orderByDesc('score')
            ->take(10)
            ->get();
    }

    public function render(){
        return view('livewire.leaderboard');
    }
}
