<div>
    <header class="border-b border-gray-300 py-4 shadow-sm">
        <div class="container mx-auto flex items-center px-5 max-w-7xl">

            <div class="flex-1">
                <a href="/" class="text-2xl font-bold text-black no-underline hover:text-gray-600 transition-colors">
                    <img src="{{ asset('images/logo.png') }}" alt="Baťa Logo" class="h-8 inline-block mr-2">
                </a>
            </div>

            <div class="text-2xl font-bold px-4 py-2 text-center">
                {{ __("Score") }}: <span class="font-bold text-black text-2xl">{{ $score }}</span>
            </div>

            <div class="flex-1 flex justify-end text-2xl font-bold px-4 py-2">
                <a href="/leaderboard" class="no-underline hover:text-red-700 text-red-600 transition-colors">
                    <i class="fas fa-trophy text-gray-600"></i> {{ __("Leaderboard") }}
                </a>
            </div>
        </div>
    </header>

    <div class="min-h-screen flex flex-col items-center justify-center p-4 w-full">

        <h1 class="text-xl mb-12 text-center text-dark">{{ __("Which product is more expensive?") }}</h1>

        <div
            class="flex flex-col md:flex-row gap-6 w-full max-w-3xl lg:max-w-5xl xl:max-w-7xl mx-auto justify-center items-stretch">

            <button wire:click="guess({{ $productA['id'] }})" @disabled($answered) class="flex-1 rounded-2xl overflow-hidden shadow-lg bg-[#f0f0f0] transition-all duration-200
           {{ !$answered ? 'hover:scale-105 cursor-pointer' : 'cursor-default opacity-75' }}">
                <img src="{{ $productA['image_url'] }}" alt="{{ $productA['name'] }}"
                    class="w-full h-48 md:h-64 lg:h-80 xl:h-96 object-contain p-4">
                <div class="p-4 text-gray text-center flex flex-col justify-between min-h-[80px]">
                    <p class="font-semibold line-clamp-2 text-sm md:text-base lg:text-lg xl:text-xl">
                        {{ $productA['name'] }}
                    </p>
                    <p class="text-gray mt-1 text-sm md:text-base lg:text-lg xl:text-xl min-h-[28px]">
                        @if($answered)
                        @if($salePriceA !== null)
                        <span class="line-through text-gray-400 font-normal mr-2">
                            @if($productA['currency'] === 'Kč')
                                {{ number_format($priceA, 0, ",", " ") }} {{ $productA['currency'] }}
                            @else
                                {{ number_format($priceA, 2) }} {{ $productA['currency'] }}
                            @endif
                        </span>
                        <span class="text-red-500">
                            @if($productA['currency'] === 'Kč')
                                {{ number_format($salePriceA, 0, ",", " ") }} {{ $productA['currency'] }}
                            @else
                                {{ number_format($salePriceA, 2) }} {{ $productA['currency'] }}
                            @endif
                        </span>
                        @else
                        <span class="text-gray-600">
                            @if($productA['currency'] === 'Kč')
                                {{ number_format($priceA, 0, ",", " ") }} {{ $productA['currency'] }}
                            @else
                                {{ number_format($priceA, 2) }} {{ $productA['currency'] }}
                            @endif
                        </span>
                        @endif
                        @endif
                    </p>
                </div>
            </button>

            <div class="flex items-center justify-center text-3xl lg:text-4xl xl:text-5xl font-bold text-dark">
                @if($answered)
                @if($result === 'correct')
                ✅
                @else
                ❌

                @endif
                @else
                {{ __("OR") }}
                @endif
            </div>

            <button wire:click="guess({{ $productB['id'] }})" @disabled($answered) class="flex-1 rounded-2xl overflow-hidden shadow-lg bg-[#f0f0f0] transition-all duration-200
           {{ !$answered ? 'hover:scale-105 cursor-pointer' : 'cursor-default opacity-75' }}">
                <img src="{{ $productB['image_url'] }}" alt="{{ $productB['name'] }}"
                    class="w-full h-48 md:h-64 lg:h-80 xl:h-96 object-contain p-4">
                <div class="p-4 text-gray text-center flex flex-col justify-between min-h-[80px]">
                    <p class="font-semibold line-clamp-2 text-sm md:text-base lg:text-lg xl:text-xl">
                        {{ $productB['name'] }}
                    </p>
                    <p class="text-gray mt-1 text-sm md:text-base lg:text-lg xl:text-xl min-h-[28px]">
                        @if($answered)
                        @if($salePriceB !== null)
                        <span class="line-through text-gray-400 font-normal mr-2">
                            @if ($productB['currency'] === 'Kč')
                                {{ number_format($priceB, 0, ",", " ") }} {{ $productB['currency'] }}
                            @else
                                {{ number_format($priceB, 2) }} {{ $productB['currency'] }}
                            @endif
                        </span>
                        <span class="text-red-500">
                            @if($productB['currency'] === 'Kč')
                                {{ number_format($salePriceB, 0, ",", " ") }} {{ $productB['currency'] }}
                            @else
                                {{ number_format($salePriceB, 2) }} {{ $productB['currency'] }}
                            @endif
                        </span>
                        @else
                        <span class="text-gray-600">
                            @if($productB['currency'] === 'Kč')
                                {{ number_format($priceB, 0, ",", " ") }} {{ $productB['currency'] }}
                            @else
                                {{ number_format($priceB, 2) }} {{ $productB['currency'] }}
                            @endif
                        </span>
                        @endif
                        @endif
                    </p>
                </div>
            </button>

        </div>

        <div class="mt-8 text-center min-h-[100px] flex flex-col items-center justify-center">
            @if($answered)
            <button wire:click="nextRound"
                class="mt-4 px-8 py-3 bg-black hover:bg-gray-700 text-white rounded-xl text-lg font-semibold transition">
                {{ __("Next product →") }}
            </button>
            @endif
        </div>
    </div>

    <div x-show="$wire.showGameOver" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div x-show="$wire.showGameOver"
                class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="$wire.showGameOver"
                class="inline-block px-8 pt-6 pb-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <button
                    type="button"
                    wire:click="closeModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors focus:outline-none"
                    title="{{ __('Close without saving') }}"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="flex items-center mb-6">
                    <div class="w-full text-center mb-6">
                        <h3 class="block text-3xl font-bold text-gray-900 mx-auto" id="modal-title">
                            {{ __("Game Over!") }}
                        </h3>
                    </div>
                </div>

                <div class="space-y-4">

                    @if($brokenRecordType !== '')                           
                            @if($brokenRecordType === 'all_time')
                                <div class="text-red-600 font-extrabold text-2xl flex justify-center items-center gap-2">
                                    {{ __('New All-Time World Record!') }} 
                                </div>
                            @elseif($brokenRecordType === 'year')
                                <div class="text-red-600 font-bold text-xl flex justify-center items-center gap-2">
                                    {{ __('Best Score This Year!') }}
                                </div>
                            @elseif($brokenRecordType === 'month')
                                <div class="text-red-600 font-bold text-xl flex justify-center items-center gap-2">
                                    {{ __('Best Score This Month!') }}
                                </div>
                            @endif
                    @endif

                    <p class="text-lg text-gray-700">
                        {{ __("Your score:") }} <span class="font-bold text-black">{{ $score }}</span>.
                    </p>

                    @if(session()->has('name'))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">

                        <div>
                            <p class="text-sm text-gray-600">{{ __("You:") }} <span class="font-bold text-gray-900">{{ session('name') }}</span></p>
                            <p class="text-sm text-gray-600 mt-1">{{ __("Your personal best:") }} <span class="font-bold text-green-600">{{ $bestScore }}</span></p>
                        </div>

                        <button
                            type="button"
                            wire:click="logOut"
                            class="p-2 text-red-500 transition-colors rounded-md hover:bg-gray-200 hover:text-red-700 focus:outline-none"
                            title="{{ __('Log Out') }}"
                        >
                            <i class="text-lg fas fa-sign-out-alt"></i>
                        </button>

                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" wire:click="saveScore"
                            class="flex-1 px-6 py-3 text-lg font-semibold text-white transition-colors bg-red-600 rounded-lg shadow hover:bg-red-700">
                            <i class="mr-2 fas fa-redo"></i> {{ __("Play Again") }}
                        </button>
                        <a href="/leaderboard"
                            class="px-6 py-3 text-lg font-semibold text-gray-700 transition-colors bg-gray-200 rounded-lg shadow hover:bg-gray-300">
                            {{ __("Leaderboard") }}
                        </a>
                    </div>

                    @else

                    <label for="name" class="block text-sm font-medium text-gray-800">
                        {{ __("Enter your name to save your score:") }}
                    </label>

                    <input type="text" id="name" wire:model="name"
                        class="w-full px-4 py-2.5 text-lg border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                        placeholder="{{ __("Your name") }}" autofocus>
                    @error('name')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                    <div class="flex gap-3 mt-8">
                        <button type="button" wire:click="saveScore"
                            class="flex-1 px-6 py-3 text-lg font-semibold text-white transition-colors bg-red-600 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="mr-2 fas fa-trophy"></i> {{ __("Save and Play Again") }}
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        window.addEventListener('trigger-confetti', (event) => {
            
            let duration = 3 * 1000;
            let animationEnd = Date.now() + duration;
            let defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 }; // Z-index ještě vyšší

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            let interval = setInterval(function() {
                let timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                let particleCount = 50 * (timeLeft / duration);
                
                if (typeof confetti === 'function') {
                    confetti(Object.assign({}, defaults, { 
                        particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                    }));
                    confetti(Object.assign({}, defaults, { 
                        particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                    }));
                } else {
                    console.error("Knihovna canvas-confetti nebyla načtena!");
                }
            }, 250);
            
        });
</script>
