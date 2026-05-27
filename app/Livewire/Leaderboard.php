<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Leaderboard extends Component{

    public $leaderboard = [];

    public function mount(){
        $this->leaderboard = Player::orderBy('score', 'desc')->take(10)->get();
    }

    public function render(){
        return view('livewire.leaderboard');
    }
}
