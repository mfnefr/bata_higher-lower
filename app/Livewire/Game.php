<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Game extends Component{
    public int $score = 0;
    public array $productA = [];
    public array $productB = [];
    public string $result = '';
    public bool $answered = false;
    private string $guess;
    private string $answer;

    public function mount(): void{
        $this->loadProducts();
    }

    private function loadProducts(): void{
        $products = Product::getTwoRandomProducts();

        $this->productA = [
            'id' => $products[0]->id,
            'name' => $products[0]->name,
            'image_url' => $products[0]->image_url,
        ];

        $this->productB = [
            'id' => $products[1]->id,
            'name' => $products[1]->name,
            'image_url' => $products[1]->image_url,
        ];
    }

    public function guess(string $guess): void{
        $priceA = Product::getPriceById($this->productA['id']);
        $priceB = Product::getPriceById($this->productB['id']);

        $this->guess = $guess;
        $this->answer = $priceA > $priceB ? 'higher' : 'lower';

        if($priceA === $priceB || $this->guess === $this->answer){
            $this->score++;
            $this->result = 'correct';
        }else{
            $this->score = 0;
            $this->result = 'wrong';
        }

        $this->answered = true;
    }

    public function nextRound(): void{
        $this->result = '';
        $this->guess = '';
        $this->answer = '';
        $this->answered = false;
        $this->loadProducts();
    }

    public function render(){
        return view('livewire.game');
    }
}
