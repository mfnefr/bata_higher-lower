<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baťa – Which product is more expensive?</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    {{ $slot }}

    @livewireScripts

    <footer class="mt-10 border-t border-gray-300 py-5">
        <div class="container mx-auto flex justify-between items-center px-5 max-w-7xl">

            <div class="text-sm text-black">
                © {{ date('Y') }} Bata Brands
            </div>

            <div class="flex space-x-6 text-2xl">
                <a href="#" class="text-black hover:text-gray-300 transition-colors"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-black hover:text-gray-300 transition-colors"><i class="fab fa-pinterest-p"></i></a>
                <a href="#" class="text-black hover:text-gray-300 transition-colors"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-black hover:text-gray-300 transition-colors"><i class="fab fa-youtube"></i></a>
            </div>

        </div>
    </footer>
</body>
</html>
