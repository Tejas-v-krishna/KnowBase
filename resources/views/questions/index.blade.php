<x-app-layout>
    <div class="space-y-6">

        {{-- ── PAGE HEADER ──────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-extrabold font-outfit text-slate-900">Questions & Answers</h1>
                <p class="text-slate-400 text-sm mt-0.5">Browse community questions or share your own knowledge.</p>
            </div>
            <a href="{{ route('questions.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shrink-0">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Ask a Question
            </a>
        </div>

        {{-- ── FILTER & SORT BAR ─────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                @foreach(['all' => 'All', 'unanswered' => 'Unanswered', 'resolved' => 'Resolved'] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['filter' => $key]) }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter === $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
                @auth
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'my']) }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter === 'my' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Mine
                    </a>
                @endauth
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Sort:</span>
                <select onchange="window.location.href=this.value"
                        class="border border-slate-200 bg-white text-xs font-semibold text-slate-700 px-3 py-1.5 rounded-xl outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 transition-all cursor-pointer">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'votes']) }}" {{ $sort === 'votes' ? 'selected' : '' }}>Most Voted</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'views']) }}" {{ $sort === 'views' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>
        </div>

        {{-- ── QUESTION CARDS ────────────────────────────────── --}}
        <div class="space-y-2.5">
            @forelse($questions as $question)
                @php
                    $isResolved = $question->status === 'resolved';
                    $hasAnswers = $question->answers_count > 0;
                    $answerBox = $isResolved
                        ? 'bg-slate-900 text-white border-slate-900'
                        : ($hasAnswers
                            ? 'bg-slate-100 text-slate-700 border-slate-200'
                            : 'bg-white text-slate-300 border-slate-200');
                @endphp

                <div class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex items-start gap-5">

                    {{-- Stats Column --}}
                    <div class="shrink-0 flex flex-col items-center gap-2 w-14 text-center pt-0.5">
                        <div>
                            <div class="text-base font-extrabold text-slate-700 leading-none">{{ $question->votes_count }}</div>
                            <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wide mt-0.5">votes</div>
                        </div>
                        <div class="w-full py-1.5 border rounded-xl {{ $answerBox }}">
                            <div class="text-sm font-extrabold leading-none">{{ $question->answers_count }}</div>
                            <div class="text-[9px] font-semibold uppercase tracking-wide mt-0.5 opacity-60">ans</div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $question->views_count }}v</div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                            @if($question->topic)
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">{{ $question->topic->name }}</span>
                            @endif
                            @if($isResolved)
                                <span class="inline-flex items-center gap-0.5 bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Solved
                                </span>
                            @endif
                        </div>

                        <h3 class="text-base font-bold text-slate-900 group-hover:text-slate-600 transition-colors leading-snug mb-2">
                            <a href="{{ route('questions.show', $question->slug) }}">{{ $question->title }}</a>
                        </h3>

                        @if($question->tags->count())
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach($question->tags as $tag)
                                    <a href="{{ route('tags.show', $tag->slug) }}"
                                       class="bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 hover:text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded-lg transition-colors">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                            @if($question->user->avatar)
                                <img src="{{ asset('storage/' . $question->user->avatar) }}" class="h-4 w-4 rounded-full object-cover">
                            @else
                                <div class="h-4 w-4 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-[8px]">
                                    {{ strtoupper(substr($question->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>By <a href="{{ route('users.show', $question->user->username) }}" class="font-semibold text-slate-600 hover:text-slate-900">{{ $question->user->name }}</a></span>
                            <span>·</span>
                            <span>{{ $question->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Quick CTA --}}
                    <div class="shrink-0 self-center hidden sm:block">
                        <a href="{{ route('questions.show', $question->slug) }}"
                           class="text-xs font-bold text-slate-600 bg-slate-50 hover:bg-slate-900 hover:text-white px-3 py-1.5 rounded-lg border border-slate-200 hover:border-transparent transition-all whitespace-nowrap">
                            {{ $isResolved ? 'View' : 'Answer' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No questions found</h3>
                    <p class="text-slate-400 text-sm mb-4">Be the first to ask a question to the community!</p>
                    <a href="{{ route('questions.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-sm transition-all">
                        Ask a Question
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ───────────────────────────────────── --}}
        @if($questions->hasPages())
            <div class="pt-4">{{ $questions->links() }}</div>
        @endif

    </div>
</x-app-layout>
