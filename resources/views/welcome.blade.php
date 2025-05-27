<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KKHS Facility Booking System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-black to-blue-950 text-white min-h-screen flex flex-col">
    <div class="flex-grow flex flex-col">
        <!-- Header/Navigation -->
        <header class="py-4 px-6 md:px-12 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-white">KKHS</h1>
            </div>
            <nav class="hidden md:flex space-x-10">
                <a href="#" class="text-white hover:text-blue-300 transition">Home</a>
                <a href="#" class="text-white hover:text-blue-300 transition">Facilities</a>
                <a href="#" class="text-white hover:text-blue-300 transition">How to Book</a>
                <a href="#" class="text-white hover:text-blue-300 transition">Contact</a>
            </nav>
            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="hidden md:block">
                    <flux:button variant="primary">Login</flux:button>
                </a>
                <a href="{{ route('register') }}">
                    <flux:button variant="primary">Register</flux:button>
                </a>
            </div>
        </header>

        <!-- Hero Section - Vertically Centered -->
        <div class="container mx-auto px-6 flex-grow flex items-center justify-center relative">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 z-10">
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Welcome to <br>KKHS Facility Booking System!
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-8">
                        Book facilities online with ease!
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <flux:button variant="primary">Book a Facility</flux:button>
                        <flux:button variant="ghost">View Facilities</flux:button>
                    </div>
                </div>
                <div class="md:w-1/2 mt-12 md:mt-0 flex justify-center">
                    <div class="relative">
                        <!-- Abstract shape behind the SVG -->
                        <div class="w-72 h-72 md:w-96 md:h-96 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full opacity-80 blur-xl absolute -top-10 -right-10"></div>
                        <div class="w-72 h-72 md:w-96 md:h-96 bg-gradient-to-r from-blue-300 to-cyan-500 rounded-full opacity-70 animate-pulse absolute top-0 right-0"></div>
                        <!-- Booking illustration SVG -->
                        <div class="relative z-10 w-full max-w-md">
                            <img src="{{ asset('images/booking-illustration.svg') }}" alt="Booking Illustration" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-1/4 right-1/4 w-px h-40 md:h-72 bg-white/20 rotate-45"></div>
            <div class="absolute top-1/3 left-1/4 w-px h-40 md:h-72 bg-white/20 -rotate-45"></div>
            <div class="absolute bottom-1/4 right-1/2 w-px h-40 md:h-72 bg-white/20 rotate-45"></div>
        </div>
    </div>

    <!-- Footer - Anchored to bottom -->
    <footer class="bg-transparent py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-white text-sm">&copy; {{ date('Y') }} KKHS Facility Booking System. All rights reserved.</p>
                </div>
                <!-- <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Help</a>
                </div> -->
            </div>
        </div>
    </footer>
</body>
</html>
