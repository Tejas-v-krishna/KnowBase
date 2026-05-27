<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-slate-900">Overview</h1>
            <p class="text-sm text-slate-500">General platform metrics and status</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users Card -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Users</span>
                    <h3 class="text-3xl font-extrabold font-outfit text-slate-900 mt-1">{{ $usersCount }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 font-bold flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <!-- Articles Card -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Articles</span>
                    <h3 class="text-3xl font-extrabold font-outfit text-slate-900 mt-1">{{ $articlesCount }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5" />
                    </svg>
                </div>
            </div>

            <!-- Questions Card -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Q&A Asked</span>
                    <h3 class="text-3xl font-extrabold font-outfit text-slate-900 mt-1">{{ $questionsCount }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 font-bold flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Threads Card -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Discussions</span>
                    <h3 class="text-3xl font-extrabold font-outfit text-slate-900 mt-1">{{ $threadsCount }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-slate-50 text-slate-800 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick actions/details -->
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold font-outfit text-slate-900 uppercase tracking-wider mb-4">Quick Administration Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.topics.index') }}" class="p-4 rounded-2xl border border-slate-100 hover:border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center space-x-4">
                    <span class="text-xl">📁</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Manage Topics</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Create, edit, or delete main subject topics.</p>
                    </div>
                </a>
                <a href="{{ route('admin.users.index') }}" class="p-4 rounded-2xl border border-slate-100 hover:border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center space-x-4">
                    <span class="text-xl">👥</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Manage Users</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Change roles, permissions, or ban bad actors.</p>
                    </div>
                </a>
                <a href="{{ route('admin.badges.index') }}" class="p-4 rounded-2xl border border-slate-100 hover:border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center space-x-4">
                    <span class="text-xl">🏆</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Manage Badges</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Configure automated gamification badges.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
