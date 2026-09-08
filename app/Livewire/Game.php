<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Player;
use App\Models\GameLog;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Game extends Component{
    public int $score = 0;
    public int $bestScore = 0;
    public array $productA = [];
    public array $productB = [];
    public string $result = '';
    public string $brokenRecordType = '';
    public bool $answered = false;
    public bool $showGameOver = false;
    public string $name = '';
    public float $priceA = 0;
    public float $priceB = 0;
    public ?float $salePriceA = null;
    public ?float $salePriceB = null;
    private string $guess;
    private string $answer;

    public function mount(): void{
        $this->loadProducts();
    }

    private function loadProducts(): void{
        $locale = app()->getLocale();

        $products = Product::getTwoRandomProducts($locale);

        $this->productA = [
            'id' => $products[0]->id,
            'name' => $products[0]->name,
            'image_url' => $products[0]->image_url,
            'currency' => $products[0]->currency,
        ];

        $this->productB = [
            'id' => $products[1]->id,
            'name' => $products[1]->name,
            'image_url' => $products[1]->image_url,
            'currency' => $products[1]->currency,
        ];
    }

    public function guess(string $guess): void{
        if($this->answered){
            return;
        }

        $pricesA = Product::getPriceById($this->productA['id']);
        $pricesB = Product::getPriceById($this->productB['id']);

        $this->priceA = $pricesA['price'];
        $this->priceB = $pricesB['price'];
        $this->salePriceA = $pricesA['sale_price'];
        $this->salePriceB = $pricesB['sale_price'];

        $finalPriceA = $this->salePriceA !== null ? $this->salePriceA : $this->priceA;
        $finalPriceB = $this->salePriceB !== null ? $this->salePriceB : $this->priceB;

        $this->guess = $guess;
        $this->answer = $finalPriceA > $finalPriceB ? $this->productA['id'] : $this->productB['id'];

        if($finalPriceA === $finalPriceB || $this->guess === $this->answer){
            $this->score++;
            $this->result = 'correct';
        }else{
            $this->result = 'wrong';
            $this->wrongAnswer();
        }

        $this->answered = true;
    }

    public function nextRound(): void{
        $this->result = '';
        $this->guess = '';
        $this->answer = '';
        $this->answered = false;
        $this->priceA = 0;
        $this->priceB = 0;
        $this->salePriceA = null;
        $this->salePriceB = null;
        $this->brokenRecordType = '';
        $this->loadProducts();
    }

    public function closeModal(): void{
        $this->score = 0;
        $this->showGameOver = false;
        $this->brokenRecordType = '';
    }

    private function wrongAnswer(): void{
        $this->showGameOver = true;

        if(session()->has('name')){
            $this->name = session('name');
            $player = Player::where('name', $this->name)->first();

            if($player){
                $this->bestScore = $player->gameLogs()->max('score') ?? 0;
            }
        }

        $this->checkWorldRecord();
    }

    private function checkWorldRecord(): void{
        if($this->score === 0) return;

        $allTimeBest = GameLog::max('score') ?? 0;
        $yearBest = GameLog::whereYear('created_at', now()->year)->max('score') ?? 0;
        $monthBest = GameLog::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->max('score') ?? 0;

        if ($this->score > $allTimeBest) {
            $this->brokenRecordType = 'all_time';
        } elseif ($this->score > $yearBest) {
            $this->brokenRecordType = 'year';
        } elseif ($this->score > $monthBest) {
            $this->brokenRecordType = 'month';
        }

        if($this->brokenRecordType !== ''){
            $this->dispatch('trigger-confetti');
        }
    }

    public function saveScore(): void{
        $this->validate(['name' => 'required|min:2|max:30']);
        $player = Player::whereRaw('BINARY name = ?', [$this->name])->first();

        if(!$player){
            $player = Player::create(['name' => $this->name]);
            $this->bestScore = $player->gameLogs()->max('score') ?? 0;
        }

        $player->gameLogs()->create(['score' => $this->score]);

        session(['name' => $this->name]);

        $this->score = 0;
        $this->name = '';
        $this->showGameOver = false;
    }

    public function goToLeaderboard(){
        $this->validate(['name' => 'required|min:2|max:30']);
        
        $player = Player::whereRaw('BINARY name = ?', [$this->name])->first();
        $player->gameLogs()->create(['score' => $this->score]);

        session(['name' => $this->name]);

        return redirect()->to('/leaderboard');
    }

    public function logOut(){
        session()->forget('name');
        return redirect('/');
    }

    public function render(){
        return view('livewire.game');
    }
}
