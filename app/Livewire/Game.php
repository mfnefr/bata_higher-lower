<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.app')]
class Game extends Component{
    public int $score = 0;
    public array $productA = [];
    public array $productB = [];
    public string $result = '';
    public bool $answered = false;
    private string $guess;
    private string $answer;
    private string $sessionKey;

    public function mount(): void{
        $this->loadProducts();
    }

    private function loadProducts(): void{
        $products = Product::getTwoRandomProducts();

        $key = Str::random(32);
        Session::put('session_key', $key);
        Session::put('product_a_id', $products[0]->id);
        Session::put('product_b_id', $products[1]->id);

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
        $sessionIdA = Session::get('product_a_id');
        $sessionIdB = Session::get('product_b_id');

        if($this->productA['id'] !== $sessionIdA || $this->productB['id'] !== $sessionIdB){
            $this->loadProducts();
            return;
        }

        $priceA = Product::getPriceById($sessionIdA);
        $priceB = Product::getPriceById($sessionIdB);

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

        Session::forget('session_key');
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
