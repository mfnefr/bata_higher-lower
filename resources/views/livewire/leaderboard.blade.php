<div>
    <header class="border-b border-gray-300 py-4 shadow-sm">
        <div class="container mx-auto flex items-center px-5 max-w-7xl">

            <div class="flex-1">
                <a href="/" class="text-2xl font-bold text-black no-underline hover:text-gray-600 transition-colors">
                    <img src="{{ asset('images/logo.png') }}" alt="Baťa Logo" class="h-8 inline-block mr-2">
                </a>
            </div>

            <div x-data="{ open: false }" class="relative inline-block text-left">
    
                <div>
                    <button 
                        @click="open = !open" 
                        @click.outside="open = false"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none"
                    >
                        Filter by: 
                        <i class="ml-2 mt-1 text-xs fas fa-chevron-down"></i>
                    </button>
                </div>

                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    x-cloak
                >
                    <div class="py-1">
                        <button 
                            type="button"
                            wire:click="setTimeFilter('allTime')" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-700"
                        >
                             All Time
                        </button>
                        <button 
                            type="button"
                            wire:click="setTimeFilter('thisYear')" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-700"
                        >
                             This Year
                        </button>
                        <button 
                            type="button"
                            wire:click="setTimeFilter('thisMonth')" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-700"
                        >
                            This Month
                        </button>

                        <div class="border-t border-gray-400 my-1"></div>

                        <button 
                            type="button"
                            wire:click="setTypeOfScore('score')" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-700"
                        >
                            Best Score
                        </button>
                        <button 
                            type="button"
                            wire:click="setTypeOfScore('total_score')" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-700"
                        >
                            Total Score
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
<div class="container mx-auto my-8 p-4">
    <h1 class="text-2xl font-bold text-center text-red-600 my-8">
        <i class="fas fa-trophy text-gray-600"></i> Leaderboard
    </h1>

    <div class="flex items-center justify-between p-4 bg-white mb-2 w-full max-w-md mx-auto">
        <div class="flex items-center gap-4">
            <span class="text-lg font-bold text-gray-800">Player</span>
        </div>
        <span class="text-lg font-bold text-gray-900">Score</span>
    </div>

@foreach ($leaderboard as $player)
    <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow mb-2 w-full max-w-md mx-auto">
        <div class="flex items-center gap-4">
            <span class="text-lg font-bold text-gray-800">{{ $loop->iteration }}.</span>
            <span class="text-lg font-medium text-gray-700">{{ $player->name }}</span>
        </div>
        <span class="text-lg font-semibold text-gray-900">{{ $typeOfScore === 'score' ? $player->score : $player->total_score }} pts</span>
    </div>
@endforeach
</div>
</div>
