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

        $timeCondition = function($query){
            if($this->timeFilter === 'thisYear'){
                $query->whereYear('updated_at', now()->year);
            }elseif($this->timeFilter === 'thisMonth'){
                $query->whereYear('updated_at', now()->year)
                    ->whereMonth('updated_at', now()->month);
            }
        };

        if($this->typeOfScore === 'score'){
            $query->withMax(['gameLogs' => $timeCondition], 'score')->orderByDesc('game_logs_max_score');
        }else{
            $query->withSum(['gameLogs' => $timeCondition], 'score')->orderByDesc('game_logs_sum_score');
        }

        $leaderboard = $query->take(10)->get();

        return view('livewire.leaderboard', ['leaderboard' => $leaderboard]);
    }
}
