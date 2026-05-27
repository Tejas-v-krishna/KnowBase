<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-6xl mx-auto py-6">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Breadcrumbs / Topic -->
            <div class="flex items-center space-x-2 text-xs font-extrabold uppercase tracking-wider text-slate-400">
                <a href="{{ route('articles.index') }}" class="hover:text-slate-900 transition-colors">Articles</a>
                <span>/</span>
                <span class="text-slate-900">{{ $article->topic->name ?? 'General' }}</span>
            </div>

            <!-- Title & Info -->
            <div class="space-y-4">
                <h1 class="text-3xl md:text-5xl font-black font-outfit text-slate-950 leading-tight tracking-tight">{{ $article->title }}</h1>
                
                <div class="flex flex-wrap items-center justify-between gap-4 border-y border-slate-100 py-4">
                    <div class="flex items-center space-x-3">
                        @if($article->user->avatar)
                            <img src="{{ asset('storage/' . $article->user->avatar) }}" class="h-10 w-10 rounded-xl object-cover border border-slate-100" alt="">
                        @else
                            <div class="h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($article->user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('users.show', $article->user->username) }}" class="text-sm font-bold text-slate-900 hover:underline">{{ $article->user->name }}</a>
                                <span class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $article->user->reputation }} XP</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">Published {{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }} &bull; {{ $article->reading_time ?? 1 }} min read</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        @auth
                            <!-- Like Button -->
                            <form action="{{ route('likes.toggle', $article->id) }}" method="POST">
                                @csrf
                                @php
                                    $isLiked = auth()->user()->likes()->where('likeable_type', \App\Models\Article::class)->where('likeable_id', $article->id)->exists();
                                @endphp
                                <button type="submit" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl border text-xs font-bold transition-all {{ $isLiked ? 'bg-slate-900 border-slate-900 text-white shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-400' }}">
                                    <svg class="h-4 w-4 {{ $isLiked ? 'fill-white stroke-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>{{ $article->likes_count }}</span>
                                </button>
                            </form>

                            <!-- Bookmark Button -->
                            <form action="{{ route('bookmarks.toggle', ['type' => 'article', 'id' => $article->id]) }}" method="POST">
                                @csrf
                                @php
                                    $isBookmarked = auth()->user()->bookmarks()->where('bookmarkable_type', \App\Models\Article::class)->where('bookmarkable_id', $article->id)->exists();
                                @endphp
                                <button type="submit" class="p-2 border rounded-xl transition-colors {{ $isBookmarked ? 'bg-slate-900 border-slate-900 text-white shadow-sm' : 'bg-white border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}" title="{{ $isBookmarked ? 'Remove Bookmark' : 'Bookmark Article' }}">
                                    <svg class="h-5 w-5 {{ $isBookmarked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Cover Image -->
            <div class="rounded-3xl overflow-hidden border border-slate-100 shadow-sm max-h-[400px] mb-6">
                <img src="{{ $article->cover_image ? asset('storage/' . $article->cover_image) : $article->thumbnail_url }}" class="w-full object-cover" alt="{{ $article->title }}">
            </div>

            <!-- Body -->
            <div class="prose prose-slate max-w-none text-slate-800 font-sans leading-relaxed text-base md:text-lg">
                {!! $article->body !!}
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-1.5 pt-6 border-t border-slate-100">
                @foreach($article->tags as $tag)
                    <a href="{{ route('tags.show', $tag->slug) }}" class="bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-950 hover:text-white hover:border-slate-950 transition-colors text-xs font-bold px-3 py-1.5 rounded-xl">{{ $tag->name }}</a>
                @endforeach
            </div>

            <!-- Comments Section -->
            <div class="space-y-6 pt-10 border-t border-slate-100">
                <h3 class="text-xl font-bold font-outfit text-slate-900">Comments ({{ $article->comments->count() }})</h3>
                
                @auth
                    <!-- Comment Create Form -->
                    <form action="{{ route('comments.store', ['type' => 'article', 'id' => $article->id]) }}" method="POST" class="space-y-3">
                        @csrf
                        <textarea name="body" rows="3" required class="block w-full border border-slate-200 rounded-2xl p-4 bg-white text-slate-800 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all" placeholder="Join the discussion..."></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-slate-950 hover:bg-slate-900 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all shadow-sm">Post Comment</button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-slate-500">Please <a href="{{ route('login') }}" class="text-slate-900 font-bold hover:underline">log in</a> to leave a comment.</p>
                @endauth

                <!-- Comments List -->
                <div class="space-y-4">
                    @forelse($article->comments as $comment)
                        <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    @if($comment->user->avatar)
                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="h-7 w-7 rounded-lg object-cover border border-slate-100" alt="">
                                    @else
                                        <div class="h-7 w-7 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-[10px] shadow-sm">
                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('users.show', $comment->user->username) }}" class="text-xs font-bold text-slate-900 hover:underline">{{ $comment->user->name }}</a>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                @auth
                                    @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors p-1">
                                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs py-4 text-center">No comments posted yet. Be the first to comment!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Related Articles -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black font-outfit uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">Related Articles</h3>
                <ul class="space-y-4">
                    @forelse($relatedArticles as $rel)
                        <li>
                            <a href="{{ route('articles.show', $rel->slug) }}" class="block group">
                                <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded-md font-bold text-[9px] uppercase tracking-wider">{{ $rel->topic->name ?? 'General' }}</span>
                                <h4 class="text-sm font-bold font-outfit mt-1.5 text-slate-900 group-hover:text-black group-hover:underline transition-all leading-snug">{{ $rel->title }}</h4>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $rel->views_count }} views &bull; {{ $rel->likes_count }} likes</p>
                            </a>
                        </li>
                    @empty
                        <p class="text-slate-400 text-xs">No related articles found.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
