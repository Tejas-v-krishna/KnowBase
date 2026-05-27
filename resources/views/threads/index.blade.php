<x-app-layout>
    <div class="space-y-6">

        {{-- ── PAGE HEADER ──────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-extrabold font-outfit text-slate-900">Community Discussion Forums</h1>
                <p class="text-slate-400 text-sm mt-0.5">Share ideas, announcements, and engage with other members.</p>
            </div>
            <a href="{{ route('threads.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shrink-0">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Start a Discussion
            </a>
        </div>

        {{-- ── FILTER & SORT BAR ─────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                @foreach([
                    'all' => 'All Discussions',
                    'pinned' => 'Pinned',
                    'resolved' => 'Resolved'
                ] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['filter' => $key]) }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter === $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
                @auth
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'my']) }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter === 'my' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        My Threads
                    </a>
                @endauth
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Sort:</span>
                <select onchange="window.location.href=this.value"
                        class="border border-slate-200 bg-white text-xs font-semibold text-slate-700 px-3 py-1.5 rounded-xl outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 transition-all cursor-pointer">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Latest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'replies']) }}" {{ $sort === 'replies' ? 'selected' : '' }}>Most Replies</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'views']) }}" {{ $sort === 'views' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>
        </div>

        {{-- ── THREADS LIST ─────────────────────────────────── --}}
        <div class="space-y-4">
            @forelse($threads as $thread)
                <div class="group relative bg-white border border-slate-100 rounded-3xl p-5 hover:border-slate-200/80 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Left Side: Avatar & Core Info --}}
                    <div class="flex items-start space-x-4 min-w-0 flex-grow">
                        {{-- Author Avatar --}}
                        <div class="shrink-0 pt-0.5">
                            <x-user-avatar :user="$thread->user" class="h-10 w-10 rounded-2xl border border-slate-100" />
                        </div>

                        {{-- Title & Meta --}}
                        <div class="min-w-0 space-y-1.5 flex-grow">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @if($thread->is_pinned)
                                    <span class="bg-amber-50 text-amber-700 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-lg border border-amber-200/60 flex items-center gap-1">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 9.293a1 1 0 00-1.414 0L12.586 10.414 10 7.828V4a1 1 0 00-2 0v3.828L5.414 10.414l-.707-.707a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0L8.586 11.293l2.586 2.586V17a1 1 0 102 0v-3.172l1.293-1.293a1 1 0 000-1.414L15.707 9.293z" clip-rule="evenodd"/></svg>
                                        Pinned
                                    </span>
                                @endif
                                @if($thread->is_resolved)
                                    <span class="bg-emerald-50 text-emerald-700 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-lg border border-emerald-200/60">
                                        Resolved
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded-md font-bold text-[9px] uppercase tracking-wider">{{ $thread->topic->name ?? 'General' }}</span>
                            </div>
                            
                            <h3 class="text-base font-bold font-outfit text-slate-900 group-hover:text-black transition-colors leading-snug truncate">
                                <a href="{{ route('threads.show', $thread->slug) }}">
                                    {{ $thread->title }}
                                </a>
                            </h3>

                            <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 pr-4">
                                {{ strip_tags($thread->body) }}
                            </p>
                            
                            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium pt-1">
                                <span>By <a href="{{ route('users.show', $thread->user->username) }}" class="font-bold text-slate-600 hover:text-black hover:underline transition-colors">{{ $thread->user->name }}</a></span>
                                <span>&bull;</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Stats Widget --}}
                    <div class="flex items-center space-x-6 text-slate-500 sm:shrink-0 pl-14 sm:pl-0">
                        <div class="flex flex-col items-center bg-slate-50 rounded-2xl py-1.5 px-3 border border-slate-100/50 min-w-[60px]">
                            <span class="text-sm font-black text-slate-800 leading-none">{{ $thread->replies_count }}</span>
                            <span class="text-[8px] font-bold uppercase tracking-wider text-slate-400 mt-1">replies</span>
                        </div>
                        <div class="flex flex-col items-center min-w-[50px]">
                            <span class="text-xs font-bold text-slate-600">{{ $thread->views_count }}</span>
                            <span class="text-[8px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">views</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No discussions found</h3>
                    <p class="text-slate-400 text-sm mb-4">Start the first topic in the community forums!</p>
                    <a href="{{ route('threads.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-sm transition-all">
                        Start a Discussion
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ───────────────────────────────────── --}}
        @if($threads->hasPages())
            <div class="pt-6">
                {{ $threads->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
