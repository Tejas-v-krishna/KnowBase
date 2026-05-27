<x-app-layout>
    <div class="space-y-6">

        {{-- ── PAGE HEADER ──────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-extrabold font-outfit text-slate-900">Knowledge Base Articles</h1>
                <p class="text-slate-400 text-sm mt-0.5">Discover detailed posts, guides, and documentations from the community.</p>
            </div>
            <a href="{{ route('articles.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shrink-0">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Write an Article
            </a>
        </div>

        {{-- ── FILTER & SORT BAR ─────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                @foreach([
                    'latest' => 'Latest',
                    'liked' => 'Most Liked',
                    'viewed' => 'Most Read'
                ] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $sort === $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── ARTICLES GRID ────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($articles as $article)
                <article class="group relative bg-white border border-slate-100 rounded-2xl overflow-hidden hover:border-slate-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                    
                    {{-- Cover Image --}}
                    <div class="relative h-48 w-full overflow-hidden bg-slate-50">
                        @if($article->cover_image)
                            <img src="{{ asset('storage/' . $article->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $article->title }}">
                        @else
                            <img src="{{ $article->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $article->title }}">
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $article->topic->name ?? 'General' }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">{{ $article->reading_time ?? 1 }} min read</span>
                        </div>
                        
                        <h3 class="text-lg font-bold font-outfit text-slate-900 group-hover:text-slate-600 transition-colors leading-snug mb-2">
                            <a href="{{ route('articles.show', $article->slug) }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ $article->title }}
                            </a>
                        </h3>
                        
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4 flex-grow">{{ $article->excerpt }}</p>

                        {{-- Tags --}}
                        @if($article->tags->count())
                            <div class="flex flex-wrap gap-1.5 mb-5 relative z-10">
                                @foreach($article->tags->take(3) as $tag)
                                    <a href="{{ route('tags.show', $tag->slug) }}" 
                                       class="bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-700 border border-slate-200 text-[10px] font-semibold px-2 py-0.5 rounded-lg transition-colors">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                            <div class="flex items-center gap-2 relative z-10">
                                @if($article->user->avatar)
                                    <img src="{{ asset('storage/' . $article->user->avatar) }}" class="h-6 w-6 rounded-full object-cover">
                                @else
                                    <div class="h-6 w-6 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($article->user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <a href="{{ route('users.show', $article->user->username) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">{{ $article->user->name }}</a>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white border border-slate-100 rounded-2xl p-12 text-center">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No articles found</h3>
                    <p class="text-slate-400 text-sm mb-4">Be the first to write a knowledge base article!</p>
                    <a href="{{ route('articles.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-sm transition-all">
                        Write an Article
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ───────────────────────────────────── --}}
        @if($articles->hasPages())
            <div class="pt-6">
                {{ $articles->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
