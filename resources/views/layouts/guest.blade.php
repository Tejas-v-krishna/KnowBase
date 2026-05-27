<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KnowBase') }}</title>

        <!-- Scripts & Tailwind -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-8">
        <div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-xl flex overflow-hidden min-h-[650px]">
            
            <!-- Left Side Illustration Panel -->
            <div class="hidden md:block w-[45%] relative shrink-0">
                <div class="w-full h-full bg-white relative overflow-hidden flex flex-col items-center justify-center">

                    <!-- Logo Overlay -->
                    <div class="absolute top-8 left-1/2 -translate-x-1/2 z-10">
                        <span class="text-3xl font-extrabold tracking-tight font-outfit text-slate-900">Know<span class="text-slate-400">Base</span></span>
                    </div>

                    <!-- Illustration Slot -->
                    @isset($illustration)
                        <div class="relative z-10 w-full h-full">
                            {{ $illustration }}
                        </div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-tr from-slate-100 to-slate-50 z-0"></div>
                        <span class="relative z-10 text-slate-400 text-sm font-semibold tracking-widest uppercase">Illustration Here</span>
                    @endisset
                </div>
            </div>

            <!-- Right Side Form Container -->
            <div class="w-full md:w-[55%] flex flex-col px-8 py-10 sm:px-12 lg:px-16 overflow-y-auto">
                <a href="/" class="mb-6 inline-block text-slate-400 hover:text-slate-900 transition-colors w-fit">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>

                <div class="flex-grow flex flex-col justify-center">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
