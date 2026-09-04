<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public function mount()
    {
        $this->currentLocale = session('locale', config('app.locale'));
    }

    public function switchLanguage(string $locale)
    {
        session(['locale' => $locale]);

        App::setLocale($locale);

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
