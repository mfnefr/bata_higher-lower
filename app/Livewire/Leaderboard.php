<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Leaderboard extends Component{
    public string $typeOfScore = 'score';
    public string $timeFilter = 'allTime';
    public string $search = '';

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
            $query->withMax(['gameLogs' => $timeCondition], 'score')->having('game_logs_max_score', '>=', 0)->orderByDesc('game_logs_max_score');
        }else{
            $query->withSum(['gameLogs' => $timeCondition], 'score')->having('game_logs_sum_score', '>=', 0)->orderByDesc('game_logs_sum_score');
        }

        $allPlayers = $query->get();
        $leaderboard = collect();
        $searchedPlayerId = null;

        if (!empty($this->search)) {
            $searchedIndex = $allPlayers->search(function ($player) {
                return stripos($player->name, $this->search) !== false;
            });

            if ($searchedIndex !== false) {
                $searchedPlayerId = $allPlayers[$searchedIndex]->id;

                if ($searchedIndex < 5) {
                    $leaderboard = $allPlayers->take(10);
                } else {
                    $leaderboard = $allPlayers->slice($searchedIndex - 5, 10);
                }
            } else {
                $leaderboard = $allPlayers->take(10);
            }
        } else {
            $leaderboard = $allPlayers->take(10);
        }

        return view('livewire.leaderboard', ['leaderboard' => $leaderboard, 'searchedPlayerId' => $searchedPlayerId]);
    }
}
