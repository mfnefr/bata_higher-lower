<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Leaderboard extends Component{
    public string $typeOfScore = 'score';
    public string $timeFilter = 'allTime';

    public function setTimeFilter(string $filter){
        $this->timeFilter = $filter;
    }

    public function setTypeOfScore(string $type){
        $this->typeOfScore = $type;
    }

    public function render(){
        $query = Player::query();

        if($this->timeFilter === 'thisYear'){
            $query->whereYear('updated_at', now()->year);
        }elseif($this->timeFilter === 'thisMonth'){
            $query->whereYear('updated_at', now()->year)
                  ->whereMonth('updated_at', now()->month);
        }

        $leaderboard = $query->orderByDesc($this->typeOfScore)->take(10)->get();

        return view('livewire.leaderboard', ['leaderboard' => $leaderboard]);
    }
}
