<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'KnowBase') : config('app.name', 'KnowBase') }}</title>

        <!-- Trix Editor CDN -->
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

        <!-- Scripts & Tailwind -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-slate-700 bg-white flex flex-col min-h-screen">
        @include('layouts.navigation')

        <!-- Main Wrapper -->
        <main class="flex-grow py-8 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-5 p-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 flex items-center gap-3">
                    <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-4 rounded-xl border border-slate-900 bg-slate-900 text-white flex items-center gap-3">
                    <svg class="h-5 w-5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <div class="animate-page-entrance">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                    <div>
                        <p class="font-outfit font-extrabold text-slate-900 text-xl">Know<span class="text-slate-400">Base</span></p>
                        <p class="text-slate-400 text-sm mt-1 max-w-xs">The student knowledge platform. Ask, answer, and grow together.</p>
                    </div>
                    <div class="flex flex-wrap gap-x-12 gap-y-4">
                        <div>
                            <p class="text-xs font-bold text-slate-300 uppercase tracking-widest mb-2">Explore</p>
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('questions.index') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">Q&amp;A</a>
                                <a href="{{ route('articles.index') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">Articles</a>
                                <a href="{{ route('threads.index') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">Forums</a>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-300 uppercase tracking-widest mb-2">Community</p>
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('topics.index') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">Topics</a>
                                <a href="{{ route('leaderboard') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">Leaderboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-slate-400 text-xs">&copy; {{ date('Y') }} KnowBase. All rights reserved.</p>
                    <p class="text-slate-300 text-xs">Made with love for students</p>
                </div>
            </div>
        </footer>

        <!-- Livewire Scripts -->
        @livewireScripts

        <!-- Lenis Smooth Scrolling -->
        <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const lenis = new Lenis({
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                    direction: 'vertical',
                    gestureDirection: 'vertical',
                    smooth: true,
                    mouseMultiplier: 1,
                    smoothTouch: false,
                    touchMultiplier: 2,
                    infinite: false,
                });

                function raf(time) {
                    lenis.raf(time);
                    requestAnimationFrame(raf);
                }

                requestAnimationFrame(raf);
            });
        </script>
    </body>
</html>
