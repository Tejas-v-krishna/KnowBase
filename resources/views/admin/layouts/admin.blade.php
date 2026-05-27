<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - Admin Panel' : 'Admin Panel' }}</title>

        <!-- Scripts & Tailwind -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Trix Editor CDN -->
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-slate-700 bg-slate-50 flex min-h-screen">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 shrink-0 hidden md:flex flex-col justify-between p-6">
            <div class="space-y-8">
                <!-- Logo / Title -->
                <div>
                    <a href="{{ route('home') }}" class="font-outfit font-black text-xl tracking-tight text-slate-900 hover:text-slate-700 transition-colors">
                        KnowBase <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-900 text-white ml-1 uppercase tracking-wider">Admin</span>
                    </a>
                </div>

                <!-- Nav Items -->
                <nav class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Users</span>
                    </a>

                    <a href="{{ route('admin.topics.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.topics.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Topics</span>
                    </a>

                    <a href="{{ route('admin.tags.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.tags.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Tags</span>
                    </a>

                    <a href="{{ route('admin.badges.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.badges.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0L4 8m8 0l8-8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1M4.805 6.468C3.124 7.202 2 8.902 2 10.882c0 2.21 1.79 4 4 4h1a8 8 0 0016 0h1c2.21 0 4-1.79 4-4 0-1.98-1.124-3.68-2.805-4.414M12 17h.01" />
                        </svg>
                        <span>Badges</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer -->
            <div class="pt-6 border-t border-slate-200">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Exit Admin</span>
                </a>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-grow flex flex-col min-h-screen min-w-0">
            <!-- Header -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center space-x-4 md:hidden">
                    <a href="{{ route('home') }}" class="font-outfit font-black text-lg tracking-tight text-slate-900">
                        KnowBase Admin
                    </a>
                </div>
                
                <div class="hidden md:block">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Welcome back, Admin</p>
                    <h2 class="text-sm font-bold text-slate-900 font-outfit">{{ auth()->user()->name }}</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">View Site</a>
                    <x-user-avatar :user="auth()->user()" class="h-9 w-9 rounded-xl shadow-sm ring-1 ring-slate-200" />
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-grow p-6 md:p-8 max-w-7xl w-full mx-auto">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 flex items-center shadow-sm">
                        <svg class="h-5 w-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Alert -->
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 flex items-center shadow-sm">
                        <svg class="h-5 w-5 mr-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-semibold text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>






