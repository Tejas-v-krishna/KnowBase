<x-app-layout>
    <div class="space-y-8">
        <!-- Topic / Back link -->
        <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-slate-800">
            <a href="{{ route('questions.index') }}" class="hover:underline">Questions</a>
            <span>/</span>
            <span>{{ $question->topic->name ?? 'General' }}</span>
        </div>

        <!-- Question Header & Vote wrapper -->
        <div class="glass-panel p-6 rounded-2xl">
            <div class="flex items-start space-x-6">
                <!-- Voting Controls -->
                <div class="flex flex-col items-center shrink-0 space-y-2 bg-white/50 p-2 rounded-xl border border-slate-200 shadow-sm">
                    @auth
                        <form action="{{ route('votes', ['type' => 'question', 'id' => $question->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="value" value="1">
                            <button type="submit" class="p-1 hover:text-slate-800 font-bold transition-colors {{ auth()->user()->votes()->where('votable_type', \App\Models\Question::class)->where('votable_id', $question->id)->where('value', 1)->exists() ? 'text-slate-800 font-bold' : 'text-slate-800' }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <button class="p-1 text-slate-800 cursor-not-allowed">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    @endauth
                    
                    <span class="text-sm font-extrabold text-slate-800">{{ $question->votes_count }}</span>
                    
                    @auth
                        <form action="{{ route('votes', ['type' => 'question', 'id' => $question->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="value" value="-1">
                            <button type="submit" class="p-1 hover:text-slate-800 font-bold transition-colors {{ auth()->user()->votes()->where('votable_type', \App\Models\Question::class)->where('votable_id', $question->id)->where('value', -1)->exists() ? 'text-slate-800 font-bold' : 'text-slate-800' }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <button class="p-1 text-slate-800 cursor-not-allowed">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    @endauth
                </div>

                <!-- Title & Body -->
                <div class="flex-grow space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h1 class="text-2xl md:text-3xl font-extrabold font-outfit text-slate-800 leading-snug">
                            {{ $question->title }}
                            @if($question->bounty_amount > 0 && $question->status === 'open')
                                <span class="inline-flex items-center gap-1.5 align-middle ml-2 px-3 py-1 bg-amber-100 text-amber-700 text-sm font-black rounded-lg shadow-sm border border-amber-200">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    +{{ $question->bounty_amount }} XP Bounty
                                </span>
                            @endif
                        </h1>
                        
                        <!-- Bookmark/Delete controls -->
                        <div class="flex items-center space-x-2">
                            <!-- Report Button -->
                            <x-report-button :type="\App\Models\Question::class" :id="$question->id" />
                            @auth
                                <!-- Bookmark -->
                                <form action="{{ route('bookmarks.toggle', ['type' => 'question', 'id' => $question->id]) }}" method="POST">
                                    @csrf
                                    @php
                                        $isBookmarked = auth()->user()->bookmarks()->where('bookmarkable_type', \App\Models\Question::class)->where('bookmarkable_id', $question->id)->exists();
                                    @endphp
                                    <button type="submit" class="p-2 border border-slate-200 rounded-xl transition-colors {{ $isBookmarked ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}" title="{{ $isBookmarked ? 'Remove Bookmark' : 'Bookmark Question' }}">
                                        <svg class="h-5 w-5 {{ $isBookmarked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </form>

                                @can('delete', $question)
                                    <form action="{{ route('questions.destroy', $question->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 border border-slate-200 text-rose-500 rounded-xl hover:bg-rose-50 transition-colors" title="Delete Question">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            @endauth
                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="flex flex-wrap items-center space-x-3 text-xs text-slate-800">
                        <span>Asked {{ $question->created_at->diffForHumans() }}</span>
                        <span>&bull;</span>
                        <span>{{ $question->views_count }} views</span>
                    </div>

                    <!-- Details -->
                    <div class="prose max-w-none text-slate-800 font-sans text-sm md:text-base pt-3">
                        {!! $question->body !!}
                    </div>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-1.5 pt-3 border-t border-slate-200">
                        @foreach($question->tags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}" class="bg-slate-100 text-slate-800 hover:bg-slate-200 font-bold transition-colors text-xs px-2.5 py-0.5 rounded-lg">{{ $tag->name }}</a>
                        @endforeach
                    </div>

                    <!-- User Card -->
                    <div class="flex justify-end pt-4">
                        <div class="bg-white border border-slate-200 p-4 rounded-xl flex items-center space-x-3 w-64">
                            @if($question->user->avatar)
                                <img src="{{ asset('storage/' . $question->user->avatar) }}" class="h-9 w-9 rounded-lg object-cover" alt="">
                            @else
                                <div class="h-9 w-9 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($question->user->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('users.show', $question->user->username) }}" class="text-xs font-bold text-slate-800 hover:underline">{{ $question->user->name }}</a>
                                <p class="text-[10px] text-slate-500 font-semibold">{{ $question->user->reputation }} Reputation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Answers List -->
        <div class="space-y-6 pt-6">
            <h2 class="text-xl font-bold font-outfit">Answers ({{ $question->answers->count() }})</h2>
            
            <div class="space-y-6">
                @forelse($question->answers as $answer)
                    <div class="glass-panel p-6 rounded-2xl flex items-start space-x-6 relative">
                        
                        <!-- Brainliest Answer Badge on side -->
                        @if($answer->is_brainliest)
                            <div class="absolute -top-3 left-6 bg-gray-100 text-slate-800 text-[10px] font-bold uppercase px-3 py-1 rounded-full flex items-center space-x-1 shadow-sm">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Brainliest Answer</span>
                            </div>
                        @endif

                        <!-- Voting Controls -->
                        <div class="flex flex-col items-center shrink-0 space-y-2 bg-white/50 p-2 rounded-xl border border-slate-200 shadow-sm">
                            @auth
                                <form action="{{ route('votes', ['type' => 'answer', 'id' => $answer->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="value" value="1">
                                    <button type="submit" class="p-1 hover:text-slate-800 font-bold transition-colors {{ auth()->user()->votes()->where('votable_type', \App\Models\Answer::class)->where('votable_id', $answer->id)->where('value', 1)->exists() ? 'text-slate-800 font-bold' : 'text-slate-800' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <button class="p-1 text-slate-800 cursor-not-allowed">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            @endauth
                            
                            <span class="text-xs font-extrabold text-slate-800">{{ $answer->votes_count }}</span>
                            
                            @auth
                                <form action="{{ route('votes', ['type' => 'answer', 'id' => $answer->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="value" value="-1">
                                    <button type="submit" class="p-1 hover:text-slate-800 font-bold transition-colors {{ auth()->user()->votes()->where('votable_type', \App\Models\Answer::class)->where('votable_id', $answer->id)->where('value', -1)->exists() ? 'text-slate-800 font-bold' : 'text-slate-800' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <button class="p-1 text-slate-800 cursor-not-allowed">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            @endauth
                        </div>

                        <!-- Answer Body -->
                        <div class="flex-grow space-y-4 pt-1 w-full min-w-0">
                            <div class="prose max-w-none text-slate-800 font-sans text-sm leading-relaxed overflow-x-auto">
                                {!! $answer->body !!}
                            </div>
                            
                            <!-- Thanks display -->
                            @if($answer->thanks->count() > 0)
                                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-slate-100">
                                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 w-full mb-1 flex items-center gap-1">
                                        <svg class="h-3 w-3 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                        Appreciation
                                    </div>
                                    @foreach($answer->thanks->take(3) as $thank)
                                        <div class="inline-flex items-center gap-2 bg-rose-50/50 border border-rose-100 px-3 py-1.5 rounded-xl">
                                            @if($thank->user->avatar)
                                                <img src="{{ asset('storage/' . $thank->user->avatar) }}" class="h-5 w-5 rounded-md object-cover">
                                            @else
                                                <div class="h-5 w-5 rounded-md bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-[9px]">{{ strtoupper(substr($thank->user->name, 0, 2)) }}</div>
                                            @endif
                                            <div class="text-xs">
                                                <span class="font-bold text-slate-700">{{ $thank->user->name }}</span>
                                                @if($thank->xp_amount > 0)
                                                    <span class="text-[10px] font-black text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded ml-1">+{{ $thank->xp_amount }} XP</span>
                                                @endif
                                                @if($thank->message)
                                                    <span class="text-slate-500 italic ml-1">"{{ Str::limit($thank->message, 40) }}"</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($answer->thanks->count() > 3)
                                        <div class="inline-flex items-center justify-center bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-500">
                                            +{{ $answer->thanks->count() - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Controls: Accept & Delete -->
                            <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                                <div class="flex items-center space-x-2">
                                    @auth
                                        <!-- Question Owner Can Mark as Brainliest -->
                                        @if($question->user_id === auth()->id() && !$answer->is_brainliest)
                                            <form action="{{ route('answers.brainliest', $answer->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 text-slate-800 font-bold hover:bg-gray-200 rounded-xl text-xs transition-all border border-transparent">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Mark as Brainliest</span>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Thank You Button -->
                                        @if(auth()->id() !== $answer->user_id)
                                            <div x-data="{ open: false }">
                                                <button @click="open = true" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 rounded-xl text-xs transition-all border border-transparent">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                                    <span>Say Thanks</span>
                                                </button>
                                                
                                                <!-- Thank You Modal -->
                                                <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="open = false"></div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                        <div x-show="open" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full p-6">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="text-lg font-black font-outfit text-slate-900" id="modal-title">Say Thanks to {{ $answer->user->name }}</h3>
                                                                <button @click="open = false" class="text-slate-400 hover:text-slate-900 transition-colors">
                                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('thanks.store', $answer->id) }}" method="POST" class="space-y-4">
                                                                @csrf
                                                                <div>
                                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Message (Optional)</label>
                                                                    <textarea name="message" rows="2" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-slate-900 focus:border-slate-900 text-sm p-3" placeholder="Thanks for the great explanation!"></textarea>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tip XP (Optional)</label>
                                                                    <select name="xp_amount" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-slate-900 focus:border-slate-900 text-sm p-3">
                                                                        <option value="0">No Tip</option>
                                                                        <option value="5">5 XP</option>
                                                                        <option value="10">10 XP</option>
                                                                        <option value="25">25 XP</option>
                                                                    </select>
                                                                    <p class="text-[10px] text-slate-500 mt-1">This will be deducted from your current balance: {{ auth()->user()->reputation }} XP</p>
                                                                </div>
                                                                <div class="mt-5 flex gap-3">
                                                                    <button type="button" @click="open = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl transition-colors">Cancel</button>
                                                                    <button type="submit" class="flex-1 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition-colors shadow-sm">Send Thanks</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endauth
                                </div>

                                <div class="flex items-center space-x-3">
                                    <!-- Author meta -->
                                    <div class="flex items-center space-x-2">
                                        @if($answer->user->avatar)
                                            <img src="{{ asset('storage/' . $answer->user->avatar) }}" class="h-6 w-6 rounded-full object-cover" alt="">
                                        @else
                                            <div class="h-6 w-6 rounded-full bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-[9px]">
                                                {{ strtoupper(substr($answer->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="text-right flex items-center space-x-1.5">
                                            <a href="{{ route('users.show', $answer->user->username) }}" class="text-xs font-bold text-slate-800 hover:underline">{{ $answer->user->name }}</a>
                                            @if($answer->is_verified)
                                                <svg class="h-3 w-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" title="Verified Expert Answer">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            <p class="text-[9px] text-slate-500 ml-1">Answered {{ $answer->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 text-xs text-center py-4 bg-white rounded-2xl border border-slate-200 shadow-sm">No answers posted yet. Help the community by answering this question!</p>
                @endforelse
            </div>
        </div>

        <!-- Post Answer Form -->
        @auth
            <div class="glass-panel p-6 rounded-2xl space-y-4">
                <h3 class="text-lg font-bold font-outfit">Your Answer</h3>
                
                <form action="{{ route('answers.store', $question->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input id="answer_body" type="hidden" name="body">
                        <trix-editor input="answer_body"></trix-editor>
                        <x-input-error :messages="$errors->get('body')" class="mt-1" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-slate-900 hover:bg-black text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Post Answer</button>
                    </div>
                </form>
            </div>
        @else
            <p class="text-sm text-slate-600">Please <a href="{{ route('login') }}" class="text-slate-900 font-bold hover:underline">log in</a> to answer this question.</p>
        @endauth
    </div>
</x-app-layout>
