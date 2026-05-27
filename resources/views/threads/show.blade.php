<x-app-layout>
    <div class="space-y-8 max-w-4xl mx-auto py-6">
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2 text-xs font-extrabold uppercase tracking-wider text-slate-400">
            <a href="{{ route('threads.index') }}" class="hover:text-slate-900 transition-colors">Forums</a>
            <span>/</span>
            <span class="text-slate-900">{{ $thread->topic->name ?? 'General' }}</span>
        </div>

        <!-- Thread Original Post -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-bold text-[9px] uppercase tracking-wider">
                            {{ $thread->topic->name ?? 'General' }}
                        </span>
                        @if($thread->is_pinned)
                            <span class="bg-amber-550/10 text-amber-700 font-bold text-[9px] uppercase px-2 py-0.5 rounded-md border border-amber-200/50">Pinned</span>
                        @endif
                        @if($thread->is_resolved)
                            <span class="bg-emerald-550/10 text-emerald-700 font-bold text-[9px] uppercase px-2 py-0.5 rounded-md border border-emerald-200/50">Resolved</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-4xl font-black font-outfit text-slate-900 leading-tight tracking-tight">{{ $thread->title }}</h1>
                </div>
                
                @auth
                    @can('delete', $thread)
                        <form action="{{ route('threads.destroy', $thread->slug) }}" method="POST" onsubmit="return confirm('Delete this thread?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 border border-slate-200 text-slate-500 rounded-xl hover:text-red-600 hover:border-red-200 hover:bg-red-50/50 transition-all">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endcan
                @endauth
            </div>

            <!-- OP Details -->
            <div class="flex items-center space-x-3">
                @if($thread->user->avatar)
                    <img src="{{ asset('storage/' . $thread->user->avatar) }}" class="h-10 w-10 rounded-2xl object-cover border border-slate-100" alt="">
                @else
                    <div class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('users.show', $thread->user->username) }}" class="text-sm font-bold text-slate-900 hover:underline">{{ $thread->user->name }}</a>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $thread->user->reputation }} XP</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Posted {{ $thread->created_at->diffForHumans() }} &bull; {{ $thread->views_count }} views</p>
                </div>
            </div>

            <!-- OP Body -->
            <div class="prose prose-slate max-w-none text-slate-800 font-sans text-sm md:text-base leading-relaxed pt-2">
                {!! $thread->body !!}
            </div>
        </div>

        <!-- Replies List -->
        <div class="space-y-6 pt-6">
            <h2 class="text-lg font-black font-outfit text-slate-900 uppercase tracking-wide">Replies ({{ $thread->replies->count() }})</h2>

            <div class="space-y-4">
                @forelse($thread->replies as $reply)
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:border-slate-200/80 transition-all duration-300 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                @if($reply->user->avatar)
                                    <img src="{{ asset('storage/' . $reply->user->avatar) }}" class="h-8 w-8 rounded-xl object-cover border border-slate-100" alt="">
                                @else
                                    <div class="h-8 w-8 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                        {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('users.show', $reply->user->username) }}" class="text-xs font-bold text-slate-900 hover:underline">{{ $reply->user->name }}</a>
                                        <span class="bg-slate-100 text-slate-700 text-[9px] font-extrabold px-1.5 py-0.2 rounded-full">{{ $reply->user->reputation }} XP</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Replied {{ $reply->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @auth
                                @can('delete', $reply)
                                    <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Delete reply?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-1.5 rounded-lg hover:bg-red-50/50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            @endauth
                        </div>

                        <div class="prose prose-slate max-w-none text-slate-700 font-sans text-sm leading-relaxed">
                            {!! $reply->body !!}
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-100 rounded-3xl p-10 text-center shadow-sm">
                        <p class="text-slate-400 text-xs">No replies posted yet. Join the discussion below!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Reply Form -->
        @auth
            <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-4">
                <h3 class="text-lg font-black font-outfit text-slate-900 uppercase tracking-wide">Post a Reply</h3>

                <form action="{{ route('replies.store', $thread->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input id="reply_body" type="hidden" name="body">
                        <trix-editor input="reply_body" class="trix-content min-h-[150px] text-sm text-slate-800"></trix-editor>
                        <x-input-error :messages="$errors->get('body')" class="mt-1" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm">Post Reply</button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 text-center">
                <p class="text-sm text-slate-500">Please <a href="{{ route('login') }}" class="text-slate-900 font-bold hover:underline">log in</a> to post a reply.</p>
            </div>
        @endauth
    </div>
</x-app-layout>
