@props(['article'])
<div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200/80 transition-all hover:-translate-y-0.5 duration-300">
    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
        <span class="px-2.5 py-1 bg-slate-50 text-slate-600 rounded-full font-bold text-[10px]">{{ $article->topic->name ?? 'General' }}</span>
        <span>{{ $article->reading_time ?? 1 }} min read</span>
    </div>
    
    <h3 class="text-xl font-bold font-outfit mt-3 text-slate-900 hover:text-black hover:underline transition-colors leading-snug">
        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
    </h3>
    
    <p class="text-slate-600 mt-2 text-sm leading-relaxed line-clamp-2">{{ $article->excerpt }}</p>
    
    @if($article->tags->isNotEmpty())
        <div class="flex flex-wrap gap-1.5 mt-4">
            @foreach($article->tags as $tag)
                <x-tag-badge :tag="$tag" />
            @endforeach
        </div>
    @endif

    <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100">
        <div class="flex items-center space-x-2.5">
            <x-user-avatar :user="$article->user" class="h-7 w-7 rounded-xl" />
            <a href="{{ route('users.show', $article->user->username) }}" class="text-xs font-bold text-slate-700 hover:text-black hover:underline transition-colors">{{ $article->user->name }}</a>
        </div>
        <div class="flex items-center space-x-4 text-xs text-slate-400 font-medium">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-slate-400 fill-rose-500 stroke-rose-500" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="text-slate-600 font-bold">{{ $article->likes_count }}</span>
            </span>
            <span>{{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>



