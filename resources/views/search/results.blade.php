<x-app-layout>
    <x-slot name="title">Search Results for "{{ $query }}"</x-slot>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Filters -->
        <div class="w-full md:w-1/4 shrink-0">
            <div class="bg-white border border-black rounded-2xl p-5 shadow-sm sticky top-24">
                <h3 class="font-bold text-black mb-4 font-outfit">Filters</h3>
                
                <form action="{{ route('search') }}" method="GET" class="space-y-6">
                    <input type="hidden" name="q" value="{{ $query }}">
                    
                    <!-- Content Type -->
                    <div>
                        <p class="text-xs font-bold text-black uppercase tracking-wider mb-3">Content Type</p>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="type" value="all" class="text-black font-bold focus:ring-black rounded-full border-black bg-black" {{ $type === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="text-sm text-black ">All Results</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="type" value="articles" class="text-black font-bold focus:ring-black rounded-full border-black bg-black" {{ $type === 'articles' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="text-sm text-black ">Articles Only</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="type" value="questions" class="text-black font-bold focus:ring-black rounded-full border-black bg-black" {{ $type === 'questions' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="text-sm text-black ">Questions Only</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="type" value="threads" class="text-black font-bold focus:ring-black rounded-full border-black bg-black" {{ $type === 'threads' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="text-sm text-black ">Threads Only</span>
                            </label>
                        </div>
                    </div>

                    <!-- Topics -->
                    <div>
                        <p class="text-xs font-bold text-black uppercase tracking-wider mb-3">Topic</p>
                        <select name="topic_id" class="w-full rounded-xl border-black bg-white text-sm focus:ring-black focus:border-black " onchange="this.form.submit()">
                            <option value="">All Topics</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ $topicId == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div>
                        <p class="text-xs font-bold text-black uppercase tracking-wider mb-3">Tag</p>
                        <select name="tag_id" class="w-full rounded-xl border-black bg-white text-sm focus:ring-black focus:border-black " onchange="this.form.submit()">
                            <option value="">All Tags</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $tagId == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results Area -->
        <div class="flex-1 space-y-8">
            <div class="border-b border-black pb-5">
                <h1 class="text-2xl font-extrabold font-outfit text-black ">Search Results</h1>
                @if(empty($query))
                    <p class="text-black mt-1">Please enter a search query.</p>
                @else
                    <p class="text-black mt-1">Showing results for "<span class="font-bold text-black ">{{ $query }}</span>"</p>
                @endif
            </div>

            @if(empty($query))
                <div class="bg-black bg-black/30 rounded-2xl p-8 text-center text-black font-bold font-bold border border-black ">
                    <svg class="h-12 w-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="font-medium">Start typing in the search bar to find articles, questions, and discussions.</p>
                </div>
            @else
                
                @php
                    $hasResults = false;
                @endphp

                <!-- Articles Results -->
                @if(($type === 'all' || $type === 'articles') && $articles->count() > 0)
                    @php $hasResults = true; @endphp
                    <div>
                        <h2 class="text-lg font-bold font-outfit text-black mb-4 flex items-center">
                            <span class="bg-black text-black font-bold bg-black/50 font-bold p-1.5 rounded-lg mr-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path></svg>
                            </span>
                            Articles
                        </h2>
                        <div class="space-y-4">
                            @foreach($articles as $article)
                                <x-article-card :article="$article" />
                            @endforeach
                        </div>
                        @if($type === 'articles')
                            <div class="mt-6">{{ $articles->appends(request()->query())->links() }}</div>
                        @endif
                    </div>
                @endif

                <!-- Questions Results -->
                @if(($type === 'all' || $type === 'questions') && $questions->count() > 0)
                    @php $hasResults = true; @endphp
                    <div>
                        <h2 class="text-lg font-bold font-outfit text-black mb-4 flex items-center">
                            <span class="bg-gray-100 text-black font-bold font-bold p-1.5 rounded-lg mr-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Questions
                        </h2>
                        <div class="space-y-4">
                            @foreach($questions as $question)
                                <x-question-card :question="$question" />
                            @endforeach
                        </div>
                        @if($type === 'questions')
                            <div class="mt-6">{{ $questions->appends(request()->query())->links() }}</div>
                        @endif
                    </div>
                @endif

                <!-- Threads Results -->
                @if(($type === 'all' || $type === 'threads') && $threads->count() > 0)
                    @php $hasResults = true; @endphp
                    <div>
                        <h2 class="text-lg font-bold font-outfit text-black mb-4 flex items-center">
                            <span class="bg-gray-100 text-black font-bold font-bold p-1.5 rounded-lg mr-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            </span>
                            Discussions
                        </h2>
                        <div class="space-y-4">
                            @foreach($threads as $thread)
                                <x-thread-card :thread="$thread" />
                            @endforeach
                        </div>
                        @if($type === 'threads')
                            <div class="mt-6">{{ $threads->appends(request()->query())->links() }}</div>
                        @endif
                    </div>
                @endif

                @if(!$hasResults)
                    <div class="bg-white border border-black rounded-3xl p-12 text-center shadow-sm">
                        <div class="h-20 w-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="h-10 w-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold font-outfit text-black mb-2">No results found</h3>
                        <p class="text-black">We couldn't find anything matching your filters. Try adjusting them or using different keywords.</p>
                        <a href="{{ route('search', ['q' => $query]) }}" class="inline-block mt-6 px-6 py-2.5 bg-black text-black font-bold bg-black/30 font-bold font-bold rounded-xl hover:bg-black :bg-black/50 transition-colors">
                            Clear Filters
                        </a>
                    </div>
                @endif

            @endif
        </div>
    </div>
</x-app-layout>




