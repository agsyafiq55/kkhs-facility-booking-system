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
    <style>
        .parallax-element {
            transition: transform 0.1s ease-out;
            will-change: transform;
        }
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        /* Add floating animation to some elements */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .float-slow {
            animation: float 6s ease-in-out infinite;
        }
        
        .float-medium {
            animation: float 4s ease-in-out infinite;
        }
        
        .float-fast {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-black to-blue-950 text-white min-h-screen flex flex-col">
    <div class="flex-grow flex flex-col relative overflow-hidden">
        <!-- Parallax Background Elements -->
        <div class="absolute inset-0 -z-10">
            <div class="parallax-element absolute top-[10%] left-[5%] w-48 h-48 bg-blue-600/30 rounded-full blur-3xl" data-parallax-speed="0.7"></div>
            <div class="parallax-element absolute top-[30%] right-[15%] w-72 h-72 bg-indigo-500/30 rounded-full blur-3xl" data-parallax-speed="-0.6"></div>
            <div class="parallax-element absolute bottom-[20%] left-[20%] w-64 h-64 bg-cyan-500/30 rounded-full blur-3xl" data-parallax-speed="0.9"></div>
            <div class="parallax-element absolute top-[60%] right-[5%] w-56 h-56 bg-purple-500/30 rounded-full blur-3xl" data-parallax-speed="-0.8"></div>
            
            <!-- Additional floating elements for more obvious effect -->
            <div class="parallax-element float-slow absolute top-[45%] left-[40%] w-20 h-20 bg-white/5 rounded-full" data-parallax-speed="1.2"></div>
            <div class="parallax-element float-medium absolute top-[70%] left-[70%] w-16 h-16 bg-white/5 rounded-full" data-parallax-speed="0.6"></div>
            <div class="parallax-element float-fast absolute top-[15%] left-[60%] w-24 h-24 bg-white/5 rounded-full" data-parallax-speed="1.0"></div>
            
            <!-- Small particles -->
            <div class="parallax-element float-fast absolute top-[25%] left-[30%] w-3 h-3 bg-blue-400/40 rounded-full" data-parallax-speed="1.4"></div>
            <div class="parallax-element float-medium absolute top-[65%] left-[15%] w-2 h-2 bg-indigo-400/40 rounded-full" data-parallax-speed="1.6"></div>
            <div class="parallax-element float-slow absolute top-[40%] left-[75%] w-4 h-4 bg-purple-400/40 rounded-full" data-parallax-speed="1.3"></div>
        </div>
        
        <!-- Header/Navigation -->
        <header class="py-4 px-6 md:px-12 flex justify-between items-center z-20">
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
        <div class="container mx-auto px-6 flex-grow flex items-center justify-center relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 z-10 parallax-element" data-parallax-speed="0.4">
                    
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
                        <div class="parallax-element w-72 h-72 md:w-96 md:h-96 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full opacity-80 blur-xl absolute -top-10 -right-10" data-parallax-speed="-0.7"></div>
                        <div class="parallax-element w-72 h-72 md:w-96 md:h-96 bg-gradient-to-r from-blue-300 to-cyan-500 rounded-full opacity-70 animate-pulse absolute top-0 right-0" data-parallax-speed="-0.5"></div>
                        <!-- Booking illustration SVG -->
                        <div class="relative z-10 w-full max-w-md parallax-element" data-parallax-speed="0.3">
                            <img src="{{ asset('images/booking-illustration.svg') }}" alt="Booking Illustration" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-full h-full overflow-hidden -z-10">
            <div class="parallax-element absolute top-1/4 right-1/4 w-px h-40 md:h-72 bg-white/30 rotate-45" data-parallax-speed="0.6"></div>
            <div class="parallax-element absolute top-1/3 left-1/4 w-px h-40 md:h-72 bg-white/30 -rotate-45" data-parallax-speed="0.5"></div>
            <div class="parallax-element absolute bottom-1/4 right-1/2 w-px h-40 md:h-72 bg-white/30 rotate-45" data-parallax-speed="0.4"></div>
            
            <!-- Additional decorative lines for more obvious effect -->
            <div class="parallax-element absolute top-1/2 right-1/3 w-px h-24 md:h-48 bg-blue-400/30 rotate-45" data-parallax-speed="0.8"></div>
            <div class="parallax-element absolute top-2/3 left-1/3 w-px h-24 md:h-48 bg-indigo-400/30 -rotate-45" data-parallax-speed="0.7"></div>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const parallaxElements = document.querySelectorAll('.parallax-element');
            
            window.addEventListener('scroll', () => {
                const scrollY = window.scrollY;
                
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-parallax-speed') || 0;
                    const yPos = scrollY * speed;
                    element.style.transform = `translate3d(0, ${yPos}px, 0)`;
                });
            });

            // Add mouse move parallax effect
            document.addEventListener('mousemove', (e) => {
                const mouseX = e.clientX / window.innerWidth - 0.5;
                const mouseY = e.clientY / window.innerHeight - 0.5;
                
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-parallax-speed') || 0;
                    const xPos = mouseX * 60 * speed;
                    const yPos = mouseY * 60 * speed;
                    
                    // Combine scroll and mouse parallax
                    const currentTransform = element.style.transform || '';
                    const scrollY = window.scrollY;
                    const scrollYPos = scrollY * speed;
                    
                    element.style.transform = `translate3d(${xPos}px, ${scrollYPos + yPos}px, 0)`;
                });
            });
        });
    </script>
</body>
</html>
