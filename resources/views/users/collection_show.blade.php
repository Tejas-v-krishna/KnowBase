<x-app-layout>
    <x-slot name="title">{{ $collection->name }} Collection</x-slot>

    <div class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $collection->is_public ? 'bg-slate-100 text-slate-600' : 'bg-slate-900 text-white' }}">
                            {{ $collection->is_public ? 'Public' : 'Private' }}
                        </span>
                        <span class="text-xs text-slate-500">Curated by <a href="{{ route('users.show', $user->username) }}" class="font-semibold text-slate-900 hover:text-slate-600 transition-colors">{{ $user->name }}</a></span>
                    </div>
                    <h1 class="text-2xl font-bold font-outfit text-slate-900">{{ $collection->name }}</h1>
                    @if($collection->description)
                        <p class="text-sm text-slate-600 max-w-3xl">{{ $collection->description }}</p>
                    @endif
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('users.collections', $user->username) }}" class="inline-flex items-center px-4 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        All Collections
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h2 class="text-lg font-bold font-outfit text-slate-900">Items ({{ $items->count() }})</h2>
            
            <div class="space-y-4">
                @forelse($items as $item)
                    @if($item instanceof \App\Models\Article)
                        <div class="relative">
                            <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-900 text-white select-none z-10">Article</span>
                            <x-article-card :article="$item" />
                        </div>
                    @elseif($item instanceof \App\Models\Question)
                        <div class="relative">
                            <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600 select-none z-10">Q&A</span>
                            <x-question-card :question="$item" />
                        </div>
                    @elseif($item instanceof \App\Models\Thread)
                        <div class="relative">
                            <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-50 border border-slate-200 text-slate-700 select-none z-10">Discussion</span>
                            <x-thread-card :thread="$item" />
                        </div>
                    @endif
                @empty
                    <div class="bg-slate-50 border border-slate-100 rounded-3xl p-12 text-center text-slate-500 shadow-sm">
                        <p class="text-base font-semibold">This collection is empty.</p>
                        <p class="text-sm text-slate-400 mt-1">Bookmark articles, questions, or discussions to add them here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
