<div>
    <header class="border-b border-gray-300 py-4 shadow-sm">
        <div class="container mx-auto flex items-center px-5 max-w-7xl">

            <div class="flex-1">
                <a href="/" class="text-2xl font-bold text-black no-underline hover:text-gray-600 transition-colors">
                    <img src="{{ asset('images/logo.png') }}" alt="Baťa Logo" class="h-8 inline-block mr-2">
                </a>
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
        <span class="text-lg font-semibold text-gray-900">{{ $player->score }} pts</span>
    </div>
@endforeach
</div>
</div>
