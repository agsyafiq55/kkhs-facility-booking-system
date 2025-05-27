<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            .auth-gradient {
                background: linear-gradient(to right, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0) 100%);
                backdrop-filter: blur(12px);
            }
        </style>
    </head>
    <body class="min-h-screen antialiased">
        <!-- Full-screen background image -->
        <div class="fixed inset-0 -z-10">
            <img src="{{ asset('images/login-background.jpg') }}" alt="KKHS Facility" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-br from-black/40 to-indigo-950/60"></div>
        </div>
        
        <div class="min-h-screen flex justify-end">
            <!-- Login Form Container - Right Side -->
            <div class="w-full md:w-2/3 lg:w-1/2 xl:w-2/5 min-h-screen flex justify-center md:justify-end">
                <!-- Translucent form area with left-to-right gradient -->
                <div class="w-full max-w-lg flex flex-col justify-center relative overflow-hidden auth-gradient">
                    <!-- Content container with padding -->
                    <div class="relative z-10 p-6 md:p-10 lg:p-16">
                        <a href="{{ route('home') }}" class="flex flex-col items-center md:items-start gap-2 font-medium mb-6" wire:navigate>
                            <span class="flex h-9 w-9 items-center justify-center rounded-md">
                                <x-app-logo-icon class="size-9 fill-current text-white" />
                            </span>
                            <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                        
                        <div class="w-full text-white">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
