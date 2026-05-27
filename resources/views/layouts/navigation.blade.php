<div x-data="{ 
    open: false, 
    userDropdown: false, 
    searchModalOpen: false,
    query: '',
    loading: false,
    results: {questions: [], articles: [], threads: []},
    fetchResults() {
        if(this.query.length < 2) {
            this.results = {questions: [], articles: [], threads: []};
            return;
        }
        this.loading = true;
        fetch('/search/suggestions?q=' + encodeURIComponent(this.query))
            .then(res => res.json())
            .then(data => {
                this.results = data;
                this.loading = false;
            });
    }
}" class="sticky top-4 z-50 max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <nav class="bg-white/90 backdrop-blur-xl border border-slate-200 shadow-sm rounded-full px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Left: Logo --}}
            <div class="flex items-center flex-shrink-0">
                <a href="/">
                    <span class="text-xl font-extrabold tracking-tight font-outfit text-slate-900">Know<span class="text-slate-400">Base</span></span>
                </a>
            </div>

            {{-- Center: Nav Links & Search --}}
            <div class="hidden lg:flex flex-1 items-center justify-center gap-6 px-8">
                {{-- Desktop Nav Links --}}
                <div class="flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">Home</a>
                    <a href="{{ route('questions.index') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition-all {{ request()->routeIs('questions.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">Q&amp;A</a>
                    <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition-all {{ request()->routeIs('articles.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">Articles</a>
                    <a href="{{ route('threads.index') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition-all {{ request()->routeIs('threads.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">Forums</a>
                    <a href="{{ route('leaderboard') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition-all {{ request()->routeIs('leaderboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">Leaderboard</a>
                </div>

                {{-- Search Bar (Trigger) --}}
                <div class="flex-1 max-w-xs">
                    <button @click="searchModalOpen = true; $nextTick(() => $refs.searchInput.focus())" class="w-full relative group flex items-center pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-400 hover:bg-white hover:border-slate-400 transition-all text-left">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        Search...
                    </button>
                </div>
            </div>

            {{-- Right: Actions --}}
            <div class="flex items-center flex-shrink-0 gap-2">
                @auth
                    {{-- Notifications --}}
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-full text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-slate-900 ring-2 ring-white"></span>
                        @endif
                    </a>

                    {{-- XP Badge --}}
                    <div class="hidden sm:flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-full text-xs font-bold select-none">
                        <svg class="h-3 w-3 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>{{ auth()->user()->reputation }} XP</span>
                    </div>

                    {{-- Profile Dropdown --}}
                    <div class="relative">
                        <button @click="userDropdown = !userDropdown" @click.away="userDropdown = false" class="flex items-center gap-1.5 focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="h-8 w-8 rounded-full object-cover ring-2 ring-white shadow-sm" alt="Avatar">
                            @else
                                <div class="h-8 w-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <svg class="h-3.5 w-3.5 text-slate-400 hidden sm:block transition-transform duration-200" :class="{'rotate-180': userDropdown}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="userDropdown"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-3xl shadow-xl shadow-slate-100 py-1.5 z-50"
                             style="display:none;">
                            <div class="px-5 py-3 border-b border-slate-50">
                                <p class="text-xs text-slate-400 font-medium">Signed in as</p>
                                <p class="text-sm font-bold text-slate-900 font-outfit truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400">&#64;{{ auth()->user()->username }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('users.show', auth()->user()->username) }}" class="flex items-center gap-2.5 px-5 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <a href="{{ route('users.collections', auth()->user()->username) }}" class="flex items-center gap-2.5 px-5 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    Collections
                                </a>
                                <a href="{{ route('messages.index') }}" class="flex items-center gap-2.5 px-5 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    Messages
                                </a>
                                <a href="{{ route('settings.edit') }}" class="flex items-center gap-2.5 px-5 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-5 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Admin Panel
                                </a>
                                @endif
                            </div>
                            <div class="border-t border-slate-50 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-5 py-2 text-sm text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 px-4 py-2 rounded-full transition-all">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 px-5 py-2.5 rounded-full transition-all shadow-sm">Sign up</a>
                @endauth

                {{-- Mobile Hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-full text-slate-500 hover:bg-slate-100 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="md:hidden absolute top-[calc(100%+0.5rem)] left-4 right-4 bg-white border border-slate-100 rounded-3xl p-4 space-y-1 shadow-xl shadow-slate-200/50"
         style="display: none;">
        <div class="relative mb-3">
            <button @click="searchModalOpen = true; open = false; $nextTick(() => $refs.searchInput.focus())" class="w-full flex items-center text-left pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-400 outline-none hover:border-slate-400 transition-all">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                Search...
            </button>
        </div>
        <a href="{{ route('home') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Home</a>
        <a href="{{ route('questions.index') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Q&amp;A</a>
        <a href="{{ route('articles.index') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Articles</a>
        <a href="{{ route('threads.index') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Forums</a>
        <a href="{{ route('leaderboard') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Leaderboard</a>
        <a href="{{ route('topics.index') }}" class="block px-4 py-3 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-50">Topics</a>
    </div>

    {{-- Search Modal Command Palette --}}
    <div x-show="searchModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-start justify-center pt-16 sm:pt-24 px-4" @keydown.escape.window="searchModalOpen = false">
        <div x-show="searchModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="searchModalOpen = false"></div>
        
        <div x-show="searchModalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
             class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden ring-1 ring-slate-100"
             @click.stop>
             
             <div class="flex items-center px-4 py-4 border-b border-slate-100">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-ref="searchInput" x-model="query" @input.debounce.300ms="fetchResults()" @keydown.enter="window.location.href = '/search?q=' + encodeURIComponent(query)" type="text" placeholder="Search questions, articles, forums..." class="flex-1 bg-transparent border-0 focus:ring-0 text-base text-slate-900 px-4 outline-none placeholder:text-slate-400">
                <div x-show="loading" class="h-4 w-4 rounded-full border-2 border-slate-200 border-t-slate-500 animate-spin mr-2" style="display:none;"></div>
                <button @click="searchModalOpen = false" class="text-xs font-semibold text-slate-400 hover:text-slate-700 bg-slate-100 px-2 py-1 rounded">ESC</button>
             </div>

             <div class="max-h-[60vh] overflow-y-auto px-2 py-4" x-show="query.length > 0" style="display:none;">
                 
                 <template x-if="results.questions && results.questions.length > 0">
                     <div class="mb-4">
                         <h3 class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Questions</h3>
                         <ul class="space-y-1">
                             <template x-for="item in results.questions" :key="item.id">
                                 <li>
                                     <a :href="'/questions/' + item.slug" class="block px-3 py-2.5 rounded-xl hover:bg-slate-50 text-sm font-semibold text-slate-700 transition-colors" x-text="item.title"></a>
                                 </li>
                             </template>
                         </ul>
                     </div>
                 </template>
                 
                 <template x-if="results.articles && results.articles.length > 0">
                     <div class="mb-4">
                         <h3 class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Articles</h3>
                         <ul class="space-y-1">
                             <template x-for="item in results.articles" :key="item.id">
                                 <li>
                                     <a :href="'/articles/' + item.slug" class="block px-3 py-2.5 rounded-xl hover:bg-slate-50 text-sm font-semibold text-slate-700 transition-colors" x-text="item.title"></a>
                                 </li>
                             </template>
                         </ul>
                     </div>
                 </template>

                 <template x-if="results.threads && results.threads.length > 0">
                     <div class="mb-4">
                         <h3 class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Forums</h3>
                         <ul class="space-y-1">
                             <template x-for="item in results.threads" :key="item.id">
                                 <li>
                                     <a :href="'/threads/' + item.slug" class="block px-3 py-2.5 rounded-xl hover:bg-slate-50 text-sm font-semibold text-slate-700 transition-colors" x-text="item.title"></a>
                                 </li>
                             </template>
                         </ul>
                     </div>
                 </template>
                 
                 <template x-if="(!results.questions || results.questions.length === 0) && (!results.articles || results.articles.length === 0) && (!results.threads || results.threads.length === 0) && !loading">
                     <div class="text-center py-8">
                         <p class="text-sm text-slate-500">No results found for "<span x-text="query" class="font-bold text-slate-900"></span>".</p>
                     </div>
                 </template>
             </div>
             
             <!-- Show before typing -->
             <div class="px-6 py-12 text-center" x-show="query.length === 0">
                 <p class="text-sm font-medium text-slate-400">Start typing to search across all content...</p>
             </div>
             
        </div>
    </div>
</div>
