<div>
    <header class="border-b border-gray-300 py-4 shadow-sm">
        <div class="container mx-auto flex justify-between items-center px-5 max-w-7xl">

            <div>
                <a href="/" class="text-2xl font-bold text-black no-underline hover:text-gray-600 transition-colors">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Baťa Logo"
                        class="h-8 inline-block mr-2"
                    >
                </a>
            </div>

            <div class="text-2xl font-bold px-4 py-2">
                Score: <span class="font-bold text-black text-2xl">{{ $score }}</span>
            </div>

        </div>
    </header>

<div class="min-h-screen flex flex-col items-center justify-center p-4 w-full">

    <h1 class="text-xl mb-6 text-center text-dark">Which product is more expensive?</h1>

    <div class="flex flex-col md:flex-row gap-6 w-full max-w-3xl lg:max-w-5xl xl:max-w-7xl mx-auto justify-center items-stretch">

    <button wire:click="guess({{ $productA['id'] }})" @disabled($answered) class="flex-1 rounded-2xl overflow-hidden shadow-lg bg-[#f0f0f0] transition-all duration-200
           {{ !$answered ? 'hover:scale-105 cursor-pointer' : 'cursor-default opacity-75' }}">
        <img
            src="{{ $productA['image_url'] }}"
            alt="{{ $productA['name'] }}"
            class="w-full h-48 md:h-64 lg:h-80 xl:h-96 object-contain p-4"
        >
        <div class="p-4 text-gray text-center flex flex-col justify-between min-h-[80px]">
            <p class="font-semibold line-clamp-2 text-sm md:text-base lg:text-lg xl:text-xl">
                {{ $productA['name'] }}
            </p>
            <p class="text-gray mt-1 text-sm md:text-base lg:text-lg xl:text-xl min-h-[28px]">
                @if($answered)
                    @if($salePriceA !== null)
                        <span class="line-through text-gray-400 font-normal mr-2">
                            {{ number_format($priceA, 2) }} EUR
                        </span>
                        <span class="text-red-500">
                            {{ number_format($salePriceA, 2) }} EUR
                        </span>
                    @else
                        <span class="text-gray-600">
                            {{ number_format($priceA, 2) }} EUR
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
            OR
        @endif
    </div>

    <button wire:click="guess({{ $productB['id'] }})" @disabled($answered) class="flex-1 rounded-2xl overflow-hidden shadow-lg bg-[#f0f0f0] transition-all duration-200
           {{ !$answered ? 'hover:scale-105 cursor-pointer' : 'cursor-default opacity-75' }}">
        <img
            src="{{ $productB['image_url'] }}"
            alt="{{ $productB['name'] }}"
            class="w-full h-48 md:h-64 lg:h-80 xl:h-96 object-contain p-4"
        >
        <div class="p-4 text-gray text-center flex flex-col justify-between min-h-[80px]">
            <p class="font-semibold line-clamp-2 text-sm md:text-base lg:text-lg xl:text-xl">
                {{ $productB['name'] }}
            </p>
            <p class="text-gray mt-1 text-sm md:text-base lg:text-lg xl:text-xl min-h-[28px]">
                @if($answered)
                    @if($salePriceB !== null)
                        <span class="line-through text-gray-400 font-normal mr-2">
                            {{ number_format($priceB, 2) }} EUR
                        </span>
                        <span class="text-red-500">
                            {{ number_format($salePriceB, 2) }} EUR
                        </span>
                    @else
                        <span class="text-gray-600">
                            {{ number_format($priceB, 2) }} EUR
                        </span>
                    @endif
                @endif
            </p>
        </div>
    </button>

    </div>

    <div class="mt-8 text-center min-h-[100px] flex flex-col items-center justify-center">
        @if($answered)
            <button
                wire:click="nextRound"
                class="mt-4 px-8 py-3 bg-black hover:bg-gray-700 text-white rounded-xl text-lg font-semibold transition"
            >
                Next product →
            </button>
        @endif
    </div>
</div>
</div>
