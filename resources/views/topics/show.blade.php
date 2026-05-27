<x-app-layout>
    <div class="space-y-8">
        <!-- Topic Banner (Redesigned) -->
        <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="space-y-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Topic Feed</span>
                <h1 class="text-2xl md:text-3xl font-extrabold font-outfit text-slate-900">{{ $topic->name }}</h1>
                <p class="text-slate-500 text-sm max-w-xl leading-relaxed">{{ $topic->description }}</p>
            </div>
            
            <div class="flex items-center space-x-4 shrink-0">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">{{ $topic->followers_count }} {{ $topic->followers_count === 1 ? 'Follower' : 'Followers' }}</span>
                @auth
                    <form action="{{ route('topics.follow', $topic->id) }}" method="POST">
                        @csrf
                        @php
                            $isFollowing = auth()->user()->topics()->where('topic_id', $topic->id)->exists();
                        @endphp
                        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-all border {{ $isFollowing ? 'bg-slate-100 text-slate-800 border-slate-200 hover:bg-slate-200' : 'bg-slate-900 text-white border-slate-900 hover:bg-slate-800' }}">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Section Switcher Tabs -->
        <div class="flex border-b border-slate-200 space-x-6">
            <a href="{{ route('topics.show', ['slug' => $topic->slug, 'tab' => 'articles']) }}" class="pb-3 text-sm font-bold tracking-tight border-b-2 transition-all {{ $tab === 'articles' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-900' }}">Articles</a>
            <a href="{{ route('topics.show', ['slug' => $topic->slug, 'tab' => 'questions']) }}" class="pb-3 text-sm font-bold tracking-tight border-b-2 transition-all {{ $tab === 'questions' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-900' }}">Questions</a>
            <a href="{{ route('topics.show', ['slug' => $topic->slug, 'tab' => 'threads']) }}" class="pb-3 text-sm font-bold tracking-tight border-b-2 transition-all {{ $tab === 'threads' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-900' }}">Forums</a>
        </div>

        <!-- Active Tab Feed -->
        <div class="space-y-6">
            @if($tab === 'articles')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($articles as $article)
                        <div class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex flex-col justify-between h-full shadow-sm">
                            <div>
                                <h3 class="text-base font-bold font-outfit text-slate-900 group-hover:text-slate-600 transition-colors">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-xs mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                <span>By {{ $article->user->name }}</span>
                                <span>{{ $article->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">No articles published in this topic yet.</p>
                    @endforelse
                </div>
                @if($articles->hasPages())
                    <div class="pt-4">{{ $articles->links() }}</div>
                @endif
            @elseif($tab === 'questions')
                <div class="space-y-4">
                    @forelse($questions as $question)
                        <div class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex items-center justify-between shadow-sm">
                            <div>
                                <h3 class="text-base font-bold font-outfit text-slate-900 group-hover:text-slate-600 transition-colors">
                                    <a href="{{ route('questions.show', $question->slug) }}">{{ $question->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Asked by {{ $question->user->name }} &bull; {{ $question->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="bg-slate-50 border border-slate-200 text-slate-600 font-bold px-3 py-1 rounded-xl text-xs">{{ $question->answers_count }} Answers</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">No questions asked in this topic yet.</p>
                    @endforelse
                </div>
                @if($questions->hasPages())
                    <div class="pt-4">{{ $questions->links() }}</div>
                @endif
            @elseif($tab === 'threads')
                <div class="bg-white border border-slate-100 rounded-2xl divide-y divide-slate-100 shadow-sm overflow-hidden">
                    @forelse($threads as $thread)
                        <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div>
                                <h3 class="text-base font-bold font-outfit text-slate-900 hover:text-slate-600 transition-colors">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Started by {{ $thread->user->name }} &bull; {{ $thread->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="bg-slate-50 border border-slate-200 text-slate-600 font-semibold px-3 py-1 rounded-xl text-xs">{{ $thread->replies_count }} Replies</span>
                        </div>
                    @empty
                        <p class="text-slate-400 p-5 text-sm">No discussions started in this topic yet.</p>
                    @endforelse
                </div>
                @if($threads->hasPages())
                    <div class="pt-4">{{ $threads->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>




