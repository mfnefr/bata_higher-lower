<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <div class="flex items-center gap-2"> 
    <button
        type="button"
        wire:click="switchLanguage('cz')"
        class="px-2 py-1 text-sm rounded transition-colors {{ app()->getLocale() === 'cz' ? 'bg-red-600 text-white font-bold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
    >
        CZ
    </button>

    <button
        type="button"
        wire:click="switchLanguage('en')"
        class="px-2 py-1 text-sm rounded transition-colors {{ app()->getLocale() === 'en' ? 'bg-red-600 text-white font-bold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
    >
        EN
    </button>
    </div>
</div>
