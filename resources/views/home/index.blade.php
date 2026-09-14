<x-app-layout>
    {{-- ── HERO ──────────────────────────────────────────────── --}}
    <div class="relative mt-4 mb-8">
        <div class="max-w-4xl mx-auto px-6 py-12 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-3">The Student Knowledge Platform</p>
            <h1 class="text-4xl md:text-6xl font-black font-outfit text-slate-900 leading-tight mb-5 tracking-tight">
                What do you need<br>to <span x-data="{
                    words: ['learn', 'discover', 'solve', 'explore', 'master'],
                    currentWordIndex: 0,
                    displayedText: 'learn',
                    init() {
                        this.scheduleCycle();
                    },
                    scheduleCycle() {
                        setTimeout(() => {
                            this.backspaceWord();
                        }, 3000);
                    },
                    backspaceWord() {
                        let interval = setInterval(() => {
                            if (this.displayedText.length > 0) {
                                this.displayedText = this.displayedText.slice(0, -1);
                            } else {
                                clearInterval(interval);
                                this.currentWordIndex = (this.currentWordIndex + 1) % this.words.length;
                                setTimeout(() => {
                                    this.typeWord(this.words[this.currentWordIndex]);
                                }, 300);
                            }
                        }, 60);
                    },
                    typeWord(word) {
                        let index = 0;
                        let interval = setInterval(() => {
                            if (index < word.length) {
                                this.displayedText += word[index];
                                index++;
                            } else {
                                clearInterval(interval);
                                this.scheduleCycle();
                            }
                        }, 120);
                    }
                }" class="relative inline-flex items-center text-slate-900">
                    <span x-text="displayedText || '\u200B'"></span><span class="inline-block w-[3px] h-[0.8em] bg-slate-900 ml-1.5 animate-pulse align-middle"></span>
                </span> today?
            </h1>
            <p class="text-slate-400 text-base md:text-lg mb-8 max-w-xl mx-auto leading-relaxed">Ask questions, share knowledge, and earn points by helping others. Join thousands of students levelling up together.</p>

            {{-- Search --}}
            <div class="relative max-w-3xl mx-auto mb-8">
                <!-- Outer Border Container -->
                <div class="p-[1px] rounded-3xl bg-slate-200/60 shadow-sm">
                    <!-- Inner Container -->
                    <div class="bg-slate-50 rounded-[23px] overflow-hidden flex flex-col">
                        
                        <!-- Top Bar -->
                        <div class="flex items-center justify-between px-6 pt-4 pb-2 text-[12px] font-bold uppercase tracking-wider text-slate-400">
                            <span>10,000+ Topics Available</span>
                            <div class="flex items-center gap-1 text-slate-900 cursor-pointer hover:text-slate-600 transition-colors">
                                <span>Discover</span>
                                <svg class="h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        </div>

                        <!-- AI Chat Form Area -->
                        <div x-data="{
                            query: '',
                            loading: false,
                            aiResponse: null,
                            displayedAiResponse: '',
                            typingInterval: null,
                            related: { questions: [], articles: [], threads: [] },
                            suggestions: [],
                            file: null,
                            fileName: '',
                            handleFileSelect(e) {
                                this.file = e.target.files[0];
                                this.fileName = this.file ? this.file.name : '';
                            },
                            triggerFile() {
                                $refs.fileInput.click();
                            },
                            askAi() {
                                if (!this.query.trim() && !this.file) return;
                                
                                this.loading = true;
                                this.aiResponse = null;
                                this.displayedAiResponse = '';
                                if (this.typingInterval) clearInterval(this.typingInterval);
                                
                                let formData = new FormData();
                                if (this.query) formData.append('q', this.query);
                                if (this.file) formData.append('file', this.file);
                                
                                // CSRF Token handling
                                formData.append('_token', '{{ csrf_token() }}');
                                
                                fetch('{{ route('ai.ask') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    setTimeout(() => {
                                        this.aiResponse = data.ai_response;
                                        this.related = data.related;
                                        this.suggestions = data.suggestions || [];
                                        this.loading = false;
                                        this.typeResponse(data.ai_response);
                                    }, 1000);
                                })
                                .catch(err => {
                                    setTimeout(() => {
                                        this.aiResponse = 'Sorry, an error occurred while connecting to the AI. ' + err.message;
                                        this.loading = false;
                                        this.typeResponse(this.aiResponse);
                                    }, 1000);
                                });
                            },
                            typeResponse(fullText) {
                                if (!fullText) return;
                                this.displayedAiResponse = '';
                                let chunks = fullText.split(/(\s+)/);
                                let i = 0;
                                this.typingInterval = setInterval(() => {
                                    this.displayedAiResponse += chunks[i];
                                    i++;
                                    if (i >= chunks.length) {
                                        clearInterval(this.typingInterval);
                                    }
                                }, 15);
                            }
                        }" class="flex flex-col bg-white rounded-2xl m-1.5 mt-0 shadow-sm border border-slate-200/80">
                            
                            <!-- Input -->
                            <input
                                type="text" x-model="query" @keydown.enter="askAi()"
                                placeholder="What do you wanna learn today?"
                                class="w-full px-5 pt-5 pb-8 bg-transparent border-0 focus:ring-0 text-slate-900 text-lg outline-none placeholder:text-slate-400 font-medium"
                                :disabled="loading"
                            >

                            <!-- Hidden File Input -->
                            <input type="file" x-ref="fileInput" class="hidden" @change="handleFileSelect" accept="image/*,audio/*,video/*,application/pdf">

                            <!-- File Preview Badge -->
                            <div x-show="fileName" style="display:none;" class="px-5 pb-2">
                                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span x-text="fileName"></span>
                                    <button @click="file = null; fileName = ''; $refs.fileInput.value = ''" class="hover:text-red-500 ml-1">&times;</button>
                                </span>
                            </div>
                            
                            <!-- Bottom Actions -->
                            <div class="flex items-center justify-between px-4 pb-4">
                                <!-- Icons -->
                                <div class="flex items-center gap-4 text-slate-400 pl-1">
                                    <button @click="triggerFile" type="button" class="hover:text-slate-600 transition-colors" title="Attach an image or audio">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </button>
                                </div>
                                
                                <!-- Submit Button -->
                                <button @click="askAi" type="button" :disabled="loading" class="h-9 w-9 rounded-full bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white flex items-center justify-center transition-transform hover:scale-105 shadow-sm">
                                    <svg x-show="!loading" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    <svg x-show="loading" style="display:none;" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                            </div>

                            <!-- AI Output Container -->
                            <div x-show="loading || aiResponse || (related.questions && related.questions.length)" style="display:none;" class="border-t border-slate-100 p-5 bg-slate-50/50 rounded-b-2xl text-left">
                                <!-- AI Loading Shimmer Skeleton -->
                                <div x-show="loading" class="space-y-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="h-6 w-6 rounded bg-slate-900 text-white flex items-center justify-center animate-pulse">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">AI is thinking...</span>
                                    </div>
                                    <div class="space-y-2.5">
                                        <div class="h-4 w-full rounded bg-slate-200 shimmer"></div>
                                        <div class="h-4 w-[92%] rounded bg-slate-200 shimmer"></div>
                                        <div class="h-4 w-[85%] rounded bg-slate-200 shimmer"></div>
                                        <div class="h-4 w-[60%] rounded bg-slate-200 shimmer"></div>
                                    </div>
                                    <div class="mt-6 border-t border-slate-200/60 pt-4 space-y-2.5">
                                        <div class="h-3 w-1/4 rounded bg-slate-200 shimmer mb-1"></div>
                                        <div class="h-9 w-3/4 rounded-xl bg-slate-100 shimmer"></div>
                                        <div class="h-9 w-2/3 rounded-xl bg-slate-100 shimmer"></div>
                                    </div>
                                </div>

                                <!-- AI Answer -->
                                <div x-show="!loading && aiResponse" class="mb-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="h-6 w-6 rounded bg-slate-900 text-white flex items-center justify-center">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">AI Response</span>
                                    </div>
                                    <div class="prose prose-sm prose-slate max-w-none text-slate-700" x-html="window.marked ? marked.parse(displayedAiResponse || '') : displayedAiResponse"></div>
                                </div>

                                <!-- Suggestions -->
                                <div x-show="!loading && suggestions && suggestions.length > 0" style="display:none;" class="mt-6 border-t border-slate-200 pt-4">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Suggested Follow-ups:</h4>
                                    <div class="flex flex-col gap-2">
                                        <template x-for="suggestion in suggestions" :key="suggestion">
                                            <button @click="query = suggestion; askAi()" class="text-left text-sm font-medium text-slate-700 bg-white border border-slate-200 hover:border-slate-400 hover:text-slate-900 rounded-xl px-4 py-2.5 transition-colors shadow-sm">
                                                <span x-text="suggestion"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Related Knowbase Results -->
                                <div x-show="!loading && related.questions && related.questions.length > 0">
                                    <h4 class="text-xs font-bold text-slate-900 mb-2 mt-4">Related on KnowBase:</h4>
                                    <ul class="space-y-1.5">
                                        <template x-for="q in related.questions" :key="q.id">
                                            <li><a :href="'/questions/' + q.slug" class="text-sm font-semibold text-slate-900 hover:underline" x-text="q.title"></a></li>
                                        </template>
                                        <template x-for="a in related.articles" :key="'a-'+a.id">
                                            <li><a :href="'/articles/' + a.slug" class="text-sm font-semibold text-slate-900 hover:underline" x-text="'Article: ' + a.title"></a></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTAs --}}
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('questions.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Ask a Question
                </a>
                <a href="{{ route('questions.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-400 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    Browse Questions
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ── PLATFORM STATISTICS DASHBOARD ROW (NEW) ──────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mb-16">
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm text-center">
            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Members Joined</span>
            <span class="text-2xl md:text-3xl font-black font-outfit text-slate-900">{{ number_format($stats['members'] ?? 0) }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm text-center">
            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Questions Asked</span>
            <span class="text-2xl md:text-3xl font-black font-outfit text-slate-900">{{ number_format($stats['questions'] ?? 0) }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm text-center">
            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Guides & Articles</span>
            <span class="text-2xl md:text-3xl font-black font-outfit text-slate-900">{{ number_format($stats['articles'] ?? 0) }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm text-center">
            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Forum Threads</span>
            <span class="text-2xl md:text-3xl font-black font-outfit text-slate-900">{{ number_format($stats['threads'] ?? 0) }}</span>
        </div>
    </div>

    <div class="space-y-16">

        {{-- ── SUBJECTS ──────────────────────────────────────── --}}
        <section class="animate-blur-in">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-outfit uppercase tracking-wide">Browse by Subject</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Explore active discussions and guides in your department</p>
                </div>
                <a href="{{ route('topics.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors flex items-center gap-1">
                    See all topics
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                @foreach($popularTopics->take(8) as $topic)
                    <a href="{{ route('topics.show', $topic->slug) }}"
                       class="flex flex-col items-center justify-center p-5 bg-white border border-slate-100 rounded-2xl hover:border-slate-300 hover:shadow-sm hover:-translate-y-0.5 text-center gap-3.5 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                            <svg class="w-5.5 h-5.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-black leading-tight">{{ $topic->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ── FEATURED & TRENDING ARTICLES (NEW) ───────────────── --}}
        @if($trendingArticles->isNotEmpty())
        <section class="border-t border-slate-100 pt-16 animate-blur-in delay-75">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-outfit uppercase tracking-wide">Featured Guides & Articles</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Top trending and deeply informative community posts</p>
                </div>
                <a href="{{ route('articles.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors flex items-center gap-1">
                    See all articles
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($trendingArticles->take(3) as $art)
                    <article class="group bg-white border border-slate-100 rounded-3xl overflow-hidden hover:border-slate-200/80 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            {{-- Thumbnail Image --}}
                            <div class="relative h-40 w-full overflow-hidden bg-slate-50 mb-4">
                                <img src="{{ $art->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $art->title }}">
                            </div>

                            <div class="px-6">
                                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-3">
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded-md">{{ $art->topic->name ?? 'General' }}</span>
                                    <span>{{ $art->reading_time ?? 1 }} min read</span>
                                </div>
                                <h3 class="text-base font-bold font-outfit text-slate-900 group-hover:text-black group-hover:underline transition-colors leading-snug mb-2">
                                    <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed mb-4">{{ $art->excerpt }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-6 pb-6 pt-4 border-t border-slate-100 mt-4">
                            <div class="flex items-center space-x-2">
                                <x-user-avatar :user="$art->user" class="h-5 w-5 rounded-md" />
                                <a href="{{ route('users.show', $art->user->username) }}" class="text-xs font-bold text-slate-700 hover:text-slate-950">{{ $art->user->name }}</a>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                <svg class="h-3 w-3 text-rose-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                {{ $art->likes_count }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── EXPERT SPOTLIGHTS (NEW) ─────────────────────────── --}}
        @if($expertGuides->isNotEmpty())
        <section class="border-t border-slate-100 pt-16 animate-blur-in delay-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-outfit uppercase tracking-wide flex items-center gap-2">
                        <span>Expert Spotlight Guides</span>
                        <span class="px-2 py-0.5 bg-slate-900 text-white rounded-full text-[9px] font-extrabold uppercase tracking-wider">Verified</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Rigorous academic references authored by verified subject experts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($expertGuides as $guide)
                    <article class="group bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden hover:bg-white hover:border-slate-200/80 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            {{-- Thumbnail Image --}}
                            <div class="relative h-40 w-full overflow-hidden bg-slate-50 mb-4">
                                <img src="{{ $guide->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $guide->title }}">
                            </div>

                            <div class="px-6">
                                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-3">
                                    <span class="px-2.5 py-1 bg-slate-900 text-white rounded-xl text-[9px] font-extrabold">{{ $guide->topic->name ?? 'General' }}</span>
                                    <span>{{ $guide->reading_time ?? 1 }} min read</span>
                                </div>
                                <h3 class="text-base font-bold font-outfit text-slate-900 group-hover:text-black group-hover:underline transition-colors leading-snug mb-2">
                                    <a href="{{ route('articles.show', $guide->slug) }}">{{ $guide->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed mb-4">{{ $guide->excerpt }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-6 pb-6 pt-4 border-t border-slate-100 mt-4">
                            <div class="flex items-center space-x-2">
                                <x-user-avatar :user="$guide->user" class="h-6 w-6 rounded-lg" />
                                <div>
                                    <a href="{{ route('users.show', $guide->user->username) }}" class="text-xs font-extrabold text-slate-700 hover:text-slate-950 block">{{ $guide->user->name }}</a>
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-0.5 mt-0.5">
                                        <svg class="h-2.5 w-2.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Expert
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 font-semibold">
                                {{ $guide->published_at ? $guide->published_at->diffForHumans() : $guide->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── TWO COLUMN MAIN CONTENT FEED ─────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 border-t border-slate-100 pt-16">
            
            {{-- Left Feed Column (2/3 width) --}}
            <div class="lg:col-span-8 space-y-12 animate-blur-in delay-150">

                {{-- Personalized Welcome & Streak Card --}}
                @auth
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <x-user-avatar :user="auth()->user()" class="h-12 w-12 rounded-2xl border border-slate-100" />
                        <div>
                            <h2 class="text-base font-black font-outfit text-slate-900 leading-snug">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Let's solve some questions and share knowledge today.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 self-start sm:self-center">
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl px-4 py-2 flex items-center gap-2 select-none shadow-sm">
                            <span class="text-lg">🔥</span>
                            <div>
                                <span class="text-xs font-extrabold text-slate-900 block leading-none">{{ auth()->user()->streak ?? 1 }}-Day Streak</span>
                                <span class="text-[8px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Active Student</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                {{-- Quick Q&A Draft Composer Widget (NEW) --}}
                @auth
                <div x-data="{ expanded: false, title: '', body: '', topic_id: '', school_level: 'college', tags: '' }" 
                     class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm transition-all duration-300">
                    
                    {{-- Collapsed State Trigger --}}
                    <div x-show="!expanded" class="flex items-center space-x-3 cursor-pointer" @click="expanded = true">
                        <x-user-avatar :user="auth()->user()" class="h-9 w-9 rounded-xl border border-slate-100 shrink-0" />
                        <div class="flex-1 bg-slate-50 hover:bg-slate-100/80 border border-slate-200/50 rounded-2xl px-4 py-2.5 text-xs font-semibold text-slate-400 transition-colors">
                            What's your question today? Ask the community...
                        </div>
                    </div>

                    {{-- Expanded Form State --}}
                    <div x-show="expanded" style="display: none;" class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                                <span>Quick Question Composer</span>
                                <span class="bg-slate-100 text-slate-600 text-[9px] font-extrabold px-1.5 py-0.5 rounded">-10 XP</span>
                            </h3>
                            <button @click="expanded = false" class="text-xs text-slate-400 hover:text-slate-950 font-bold transition-colors">Cancel</button>
                        </div>

                        <form action="{{ route('questions.store') }}" method="POST" class="space-y-3.5">
                            @csrf
                            
                            {{-- Title --}}
                            <div>
                                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Question Title</label>
                                <input type="text" name="title" x-model="title" required placeholder="Be specific (e.g. How to balance a chemical equation?)" 
                                       class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
                            </div>

                            {{-- Detailed Description --}}
                            <div>
                                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Details</label>
                                <textarea name="body" x-model="body" rows="3" required placeholder="Describe your problem or share screenshots/equations..." 
                                          class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Subject --}}
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Subject Topic</label>
                                    <select name="topic_id" x-model="topic_id" required 
                                            class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all cursor-pointer">
                                        <option value="" disabled selected>Select a subject</option>
                                        @foreach($popularTopics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- School Level --}}
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Education Level</label>
                                    <select name="school_level" x-model="school_level" required 
                                            class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all cursor-pointer">
                                        <option value="elementary">Elementary School</option>
                                        <option value="middle_school">Middle School</option>
                                        <option value="high_school">High School</option>
                                        <option value="college" selected>College / University</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tags --}}
                            <div>
                                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Tags (Optional)</label>
                                <input type="text" name="tags" x-model="tags" placeholder="chemistry, equation, balancing (comma separated)" 
                                       class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
                            </div>

                            {{-- Publish Button --}}
                            <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-4">
                                <span class="text-[10px] text-slate-400 font-bold">You need at least 10 XP to ask</span>
                                <button type="submit" :disabled="!title || !body || !topic_id" 
                                        class="inline-flex items-center gap-1.5 px-4.5 py-2 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-300 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                                    Publish Question
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
                @endauth

                {{-- QUESTIONS FEED --}}
                <div class="space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-base font-black font-outfit uppercase tracking-wider text-slate-900">Recent Questions</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Participate and help answer peer queries</p>
                        </div>
                        
                        {{-- Tab Bar --}}
                        @auth
                        <div class="flex gap-1 p-1 bg-slate-100 rounded-xl w-fit shrink-0">
                            <a href="{{ route('home', ['tab' => 'all']) }}"
                               class="px-3.5 py-1 rounded-lg text-xs font-semibold transition-all {{ $tab !== 'following' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                All
                            </a>
                            <a href="{{ route('home', ['tab' => 'following']) }}"
                               class="px-3.5 py-1 rounded-lg text-xs font-semibold transition-all {{ $tab === 'following' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                Following
                            </a>
                        </div>
                        @endauth
                    </div>

                    {{-- Question Cards --}}
                    <div class="space-y-3">
                        @forelse($questions as $question)
                            @php
                                $isResolved = $question->status === 'resolved';
                                $hasAnswers = $question->answers_count > 0;
                                $answerBoxClass = $isResolved
                                    ? 'bg-slate-900 text-white border-slate-900 shadow-sm'
                                    : ($hasAnswers ? 'bg-slate-100 text-slate-800 border-slate-200' : 'bg-white text-slate-300 border-slate-200');
                            @endphp
                            <div class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex items-start gap-4">
                                {{-- Answer count --}}
                                <div class="shrink-0 flex flex-col items-center justify-center w-14 h-14 rounded-xl border text-center {{ $answerBoxClass }}">
                                    <span class="text-lg font-extrabold leading-none">{{ $question->answers_count }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wide opacity-60 mt-0.5">ans</span>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                        @if($question->topic)
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ $question->topic->name }}</span>
                                        @endif
                                        @if($isResolved)
                                            <span class="inline-flex items-center gap-0.5 bg-slate-900 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                                                Solved
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-black group-hover:underline transition-colors leading-snug mb-1.5">
                                        <a href="{{ route('questions.show', $question->slug) }}">{{ $question->title }}</a>
                                    </h3>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                        <x-user-avatar :user="$question->user" class="h-4 w-4 rounded-full" />
                                        <a href="{{ route('users.show', $question->user->username) }}" class="font-bold text-slate-600 hover:text-slate-900">{{ $question->user->name }}</a>
                                        <span>·</span>
                                        <span>{{ $question->created_at->diffForHumans() }}</span>
                                        <span>·</span>
                                        <span class="font-semibold">{{ $question->votes_count }} votes</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center">
                                <p class="text-slate-400 text-xs">No questions yet. Be the first to ask!</p>
                            </div>
                        @endforelse
                    </div>

                    @if($questions->count() >= 5)
                        <div class="text-center pt-2">
                            <a href="{{ route('questions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-wider">
                                View all questions
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- WEEKLY CHALLENGES WIDGET (NEW) ─────────────────────── --}}
                <div x-data="{
                    challenges: [],
                    loading: true,
                    fetchChallenges() {
                        fetch('{{ route('challenges.index') }}')
                            .then(res => res.json())
                            .then(data => {
                                this.challenges = data;
                                this.loading = false;
                            });
                    }
                }" x-init="fetchChallenges()" class="space-y-6 border-t border-slate-100 pt-12">
                    <div>
                        <h2 class="text-base font-black font-outfit uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span>Weekly Challenges</span>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md text-[9px] font-black tracking-widest uppercase">Earn XP</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Complete goals before Sunday to earn bonus reputation</p>
                    </div>

                    <div x-show="loading" class="space-y-3">
                        <div class="h-20 w-full rounded-2xl bg-slate-50 shimmer"></div>
                        <div class="h-20 w-full rounded-2xl bg-slate-50 shimmer"></div>
                    </div>

                    <div x-show="!loading" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="c in challenges" :key="c.id">
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 transition-all shadow-sm flex flex-col justify-between relative overflow-hidden"
                                 :class="{ 'opacity-60': c.is_completed }">
                                <div x-show="c.is_completed" class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-10 flex items-center justify-center">
                                    <span class="bg-emerald-500 text-white font-bold px-3 py-1 rounded-full text-xs shadow-sm flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Completed
                                    </span>
                                </div>
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="text-2xl" x-text="c.icon"></div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 leading-snug" x-text="c.title"></h3>
                                        <p class="text-xs text-slate-500 mt-0.5" x-text="c.description"></p>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 mb-1">
                                        <span x-text="c.user_progress + ' / ' + c.target_count"></span>
                                        <span class="text-amber-500">+<span x-text="c.reward_xp"></span> XP</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-amber-500 h-full rounded-full transition-all duration-500" :style="`width: ${Math.min((c.user_progress / c.target_count) * 100, 100)}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- BOUNTY BOARD WIDGET (NEW) ─────────────────────── --}}
                @if(isset($bountyQuestions) && $bountyQuestions->isNotEmpty())
                <div class="space-y-6 border-t border-slate-100 pt-12">
                    <div>
                        <h2 class="text-base font-black font-outfit uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span>Bounty Board</span>
                            <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">High priority questions offering extra XP for the best answer</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($bountyQuestions as $bountyQ)
                            <div class="group bg-rose-50/30 border border-rose-100 rounded-2xl p-5 hover:bg-rose-50 hover:border-rose-200 transition-all duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-lg uppercase tracking-widest">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        +{{ $bountyQ->bounty_amount }} XP
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $bountyQ->topic->name ?? 'General' }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 group-hover:text-black group-hover:underline transition-colors leading-snug line-clamp-2 mb-4">
                                    <a href="{{ route('questions.show', $bountyQ->slug) }}">{{ $bountyQ->title }}</a>
                                </h3>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <x-user-avatar :user="$bountyQ->user" class="h-5 w-5 rounded-md" />
                                        <span class="text-slate-500 font-semibold">{{ $bountyQ->user->name }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $bountyQ->answers_count }} Answers</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- COMMUNITY DISCUSSIONS / ACTIVE FORUMS (NEW) ───── --}}
                @if($threads->isNotEmpty())
                <div class="space-y-6 border-t border-slate-100 pt-12">
                    <div>
                        <h2 class="text-base font-black font-outfit uppercase tracking-wider text-slate-900">Active Discussions</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Engage in forums and debate core topics</p>
                    </div>

                    <div class="space-y-3">
                        @foreach($threads->take(4) as $thread)
                            <div class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex items-center justify-between gap-4">
                                <div class="flex items-center space-x-3.5 min-w-0">
                                    <x-user-avatar :user="$thread->user" class="h-9 w-9 rounded-xl border border-slate-100" />
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            @if($thread->is_pinned)
                                                <span class="bg-slate-900 text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md">Pinned</span>
                                            @endif
                                            <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded-md font-bold text-[9px] uppercase tracking-wider">{{ $thread->topic->name ?? 'General' }}</span>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-black group-hover:underline transition-colors leading-snug truncate">
                                            <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                        </h3>
                                        <div class="flex items-center gap-1.5 text-[10px] text-slate-400 mt-0.5">
                                            <span>By <a href="{{ route('users.show', $thread->user->username) }}" class="font-bold text-slate-500 hover:text-slate-800">{{ $thread->user->name }}</a></span>
                                            <span>&bull;</span>
                                            <span>{{ $thread->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="shrink-0 flex items-center space-x-4">
                                    <div class="flex flex-col items-center bg-slate-50 rounded-xl py-1 px-2.5 border border-slate-100/50 min-w-[50px]">
                                        <span class="text-xs font-black text-slate-800">{{ $thread->replies_count }}</span>
                                        <span class="text-[8px] font-bold uppercase tracking-wider text-slate-400">replies</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center pt-2">
                        <a href="{{ route('threads.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-wider">
                            Go to forums
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @endif

            </div>

            {{-- Right Sidebar Column (1/3 width) --}}
            <div class="lg:col-span-4 space-y-8 animate-blur-in delay-200">
                
                {{-- Interactive Community Poll (NEW) --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900">Community Poll</h3>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md font-bold text-[9px] uppercase tracking-wider">Weekly</span>
                    </div>
                    
                    <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $activePoll->question }}</h4>
                    
                    @auth
                        @if($userVote !== null)
                            {{-- Voted view: Show animated progress bars --}}
                            <div class="space-y-3.5 pt-1">
                                @foreach($activePoll->options as $index => $option)
                                    @php
                                        $votesForOption = $optionVotes[$index] ?? 0;
                                        $percentage = $pollVotesCount > 0 ? round(($votesForOption / $pollVotesCount) * 100) : 0;
                                        $isUserChoice = $userVote === $index;
                                    @endphp
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between text-xs font-semibold">
                                            <span class="{{ $isUserChoice ? 'text-slate-900 font-extrabold' : 'text-slate-600' }}">
                                                {{ $option }} @if($isUserChoice) <span class="text-[10px] text-slate-400 font-bold ml-1">(Your Choice)</span> @endif
                                            </span>
                                            <span class="text-slate-900 font-bold">{{ $percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-slate-900 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                                <p class="text-[10px] text-slate-400 font-bold text-center pt-2">{{ number_format($pollVotesCount) }} community votes</p>
                            </div>
                        @else
                            {{-- Vote Form --}}
                            <form action="{{ route('polls.vote', $activePoll->id) }}" method="POST" class="space-y-2 pt-1">
                                @csrf
                                @foreach($activePoll->options as $index => $option)
                                    <button type="submit" name="option_index" value="{{ $index }}" class="w-full text-left text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 hover:border-slate-400 hover:text-slate-900 hover:bg-white rounded-xl px-4 py-3 transition-colors shadow-sm">
                                        {{ $option }}
                                    </button>
                                @endforeach
                            </form>
                        @endif
                    @else
                        {{-- Guest view: Show percentages directly with login link --}}
                        <div class="space-y-3.5 pt-1">
                            @foreach($activePoll->options as $index => $option)
                                @php
                                    $votesForOption = $optionVotes[$index] ?? 0;
                                    $percentage = $pollVotesCount > 0 ? round(($votesForOption / $pollVotesCount) * 100) : 0;
                                @endphp
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-xs font-semibold">
                                        <span class="text-slate-600">{{ $option }}</span>
                                        <span class="text-slate-900 font-bold">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-slate-900 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            <p class="text-[10px] text-slate-400 font-bold text-center pt-2">
                                <a href="{{ route('login') }}" class="underline hover:text-slate-800">Sign in to cast your vote!</a>
                            </p>
                        </div>
                    @endauth
                </div>

                {{-- Daily Trivia Challenge Widget (NEW) --}}
                <div x-data="{
                    loading: true,
                    question: '',
                    category: '',
                    difficulty: '',
                    answers: [],
                    selected: null,
                    result: null,
                    submitting: false,
                    fetchTrivia() {
                        this.loading = true;
                        this.selected = null;
                        this.result = null;
                        fetch('{{ route('trivia.random') }}')
                            .then(res => res.json())
                            .then(data => {
                                setTimeout(() => {
                                    this.question = data.question;
                                    this.category = data.category;
                                    this.difficulty = data.difficulty;
                                    this.answers = data.answers;
                                    this.loading = false;
                                }, 1000);
                            });
                    },
                    submitAnswer(ans) {
                        if (this.selected || this.submitting) return;
                        this.selected = ans;
                        this.submitting = true;
                        
                        fetch('{{ route('trivia.verify') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ answer: ans })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.result = data;
                            this.submitting = false;
                        });
                    }
                }" x-init="fetchTrivia()" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900">Trivia Challenge</h3>
                        <span class="px-2.5 py-0.5 bg-slate-900 text-white rounded-md font-bold text-[9px] uppercase tracking-wider flex items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live API
                        </span>
                    </div>

                    <!-- Loading State (Skeleton Shimmer) -->
                    <div x-show="loading" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="h-3 w-1/4 rounded bg-slate-200 shimmer"></div>
                            <div class="h-3 w-12 rounded bg-slate-200 shimmer"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-4 w-full rounded bg-slate-200 shimmer"></div>
                            <div class="h-4 w-[80%] rounded bg-slate-200 shimmer"></div>
                        </div>
                        <div class="space-y-2.5 pt-2">
                            <div class="h-[38px] w-full rounded-xl bg-slate-100 shimmer"></div>
                            <div class="h-[38px] w-full rounded-xl bg-slate-100 shimmer"></div>
                            <div class="h-[38px] w-full rounded-xl bg-slate-100 shimmer"></div>
                            <div class="h-[38px] w-full rounded-xl bg-slate-100 shimmer"></div>
                        </div>
                    </div>

                    <!-- Question Container -->
                    <div x-show="!loading" style="display: none;" class="space-y-4">
                        <div class="flex items-center justify-between text-[9px] font-extrabold uppercase tracking-wider text-slate-400">
                            <span x-text="category"></span>
                            <span :class="{
                                'text-emerald-600': difficulty === 'Easy',
                                'text-amber-600': difficulty === 'Medium',
                                'text-rose-600': difficulty === 'Hard'
                            }" x-text="difficulty"></span>
                        </div>

                        <h4 class="text-sm font-bold text-slate-900 leading-snug" x-html="question"></h4>

                        <!-- Answers list -->
                        <div class="space-y-2">
                            <template x-for="ans in answers" :key="ans">
                                <button @click="submitAnswer(ans)"
                                        :disabled="selected !== null"
                                        :class="{
                                            'border-slate-200 bg-slate-50 text-slate-700 hover:border-slate-400 hover:text-slate-900 hover:bg-white': selected === null,
                                            'bg-emerald-50 border-emerald-300 text-emerald-800 font-extrabold': selected !== null && ans === result?.correct_answer,
                                            'bg-rose-50 border-rose-200 text-rose-800 opacity-60': selected !== null && selected === ans && !result?.correct,
                                            'opacity-40 border-slate-100 bg-slate-50 text-slate-400': selected !== null && ans !== result?.correct_answer && (selected !== ans)
                                        }"
                                        class="w-full text-left text-xs font-semibold border rounded-xl px-4 py-2.5 transition-all shadow-sm flex items-center justify-between"
                                >
                                    <span x-html="ans"></span>
                                    
                                    <!-- Icons indicating status -->
                                    <template x-if="selected !== null && ans === result?.correct_answer">
                                        <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <template x-if="selected !== null && selected === ans && !result?.correct">
                                        <svg class="h-4 w-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </template>
                                </button>
                            </template>
                        </div>

                        <!-- Result Box -->
                        <div x-show="result" style="display: none;" class="pt-2">
                            <div :class="result?.correct ? 'bg-emerald-550/10 border border-emerald-100 text-emerald-800' : 'bg-rose-550/10 border border-rose-100 text-rose-800'" class="p-3.5 rounded-2xl text-xs font-semibold text-center leading-relaxed">
                                <span x-text="result?.message"></span>
                            </div>
                            
                            @auth
                            <button @click="fetchTrivia()" class="mt-3 w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shadow-sm">
                                Try Another Question
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="mt-3 block text-center bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shadow-sm">
                                Sign In to Earn XP
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                
                {{-- Help Others CTA --}}
                @if($unansweredCount > 0)
                <div class="bg-slate-900 text-white rounded-3xl p-6 relative overflow-hidden shadow-sm">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="absolute -right-4 bottom-0 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>
                    <h3 class="text-sm font-black font-outfit uppercase tracking-wider mb-1.5 relative z-10">Help peers learn</h3>
                    <p class="text-slate-400 text-xs mb-5 relative z-10 leading-relaxed">
                        <span class="text-white font-bold">{{ $unansweredCount }} questions</span> are open and waiting for answers.
                    </p>
                    <a href="{{ route('questions.index', ['filter' => 'unanswered']) }}"
                       class="relative z-10 block text-center bg-white hover:bg-slate-50 text-slate-900 font-bold py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-sm transition-colors">
                        Start Answering →
                    </a>
                </div>
                @endif

                {{-- LIVE ACTIVITY FEED WIDGET (NEW) ─────────────────────── --}}
                <div x-data="{
                    activities: [],
                    loading: true,
                    fetchActivities() {
                        fetch('{{ route('activities.feed') }}')
                            .then(res => res.json())
                            .then(data => {
                                this.activities = data;
                                this.loading = false;
                            });
                    }
                }" x-init="fetchActivities()" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            Activity Feed
                            <span class="flex h-2 w-2 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        </h3>
                    </div>

                    <div x-show="loading" class="space-y-4">
                        <div class="h-10 w-full rounded-xl bg-slate-50 shimmer"></div>
                        <div class="h-10 w-full rounded-xl bg-slate-50 shimmer"></div>
                        <div class="h-10 w-full rounded-xl bg-slate-50 shimmer"></div>
                    </div>

                    <div x-show="!loading" style="display: none;" class="space-y-4 max-h-80 overflow-y-auto pr-2 scrollbar-thin">
                        <template x-for="activity in activities" :key="activity.id">
                            <div class="flex gap-3 text-sm">
                                <template x-if="activity.user.avatar_url">
                                    <img :src="activity.user.avatar_url" class="h-7 w-7 rounded-full object-cover shrink-0 mt-0.5">
                                </template>
                                <div>
                                    <p class="text-slate-800 text-xs leading-relaxed">
                                        <span class="font-bold text-slate-900 hover:underline cursor-pointer" x-text="activity.user.name"></span>
                                        <span x-html="activity.description"></span>
                                    </p>
                                    <span class="text-[10px] font-bold text-slate-400" x-text="activity.time_ago"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="activities.length === 0">
                            <p class="text-xs text-slate-400">No recent activity.</p>
                        </template>
                    </div>
                </div>

                {{-- Top Contributors (Top Minds) --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900">Top Minds</h3>
                        <a href="{{ route('leaderboard') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-wider">See all</a>
                    </div>
                    <ul class="space-y-4">
                        @forelse($topContributors as $i => $contributor)
                            <li class="flex items-center gap-3">
                                <span class="text-xs font-extrabold text-slate-300 w-4 text-center tabular-nums">{{ $i + 1 }}</span>
                                <x-user-avatar :user="$contributor" class="h-8 w-8 rounded-full" />
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('users.show', $contributor->username) }}" class="text-xs font-bold text-slate-800 hover:text-black hover:underline transition-all truncate block">{{ $contributor->name }}</a>
                                    <p class="text-[10px] text-slate-400 font-bold mt-0.5">{{ number_format($contributor->reputation) }} XP</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-slate-400 text-xs">No contributors yet.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- POPULAR TAGS CLOUD (NEW) ──────────────────── --}}
                @if($popularTags->isNotEmpty())
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900">Popular Tags</h3>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}" class="px-2.5 py-1 bg-slate-50 border border-slate-100 text-slate-600 hover:bg-slate-950 hover:text-white hover:border-slate-950 rounded-lg text-[10px] font-bold transition-all uppercase tracking-wider">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Latest Articles (Secondary Widget) --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-50">
                        <h3 class="text-xs font-black font-outfit uppercase tracking-wider text-slate-900">Latest Articles</h3>
                        <a href="{{ route('articles.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-wider">See all</a>
                    </div>
                    <ul class="space-y-4">
                        @foreach($articles->take(3) as $article)
                            <li class="group">
                                <a href="{{ route('articles.show', $article->slug) }}" class="block">
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-black group-hover:underline transition-colors line-clamp-2 leading-snug mb-1">{{ $article->title }}</h4>
                                    <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wider">{{ $article->topic->name ?? 'General' }} &bull; {{ $article->reading_time ?? 1 }} min read</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Include Marked.js for Markdown parsing -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</x-app-layout>
