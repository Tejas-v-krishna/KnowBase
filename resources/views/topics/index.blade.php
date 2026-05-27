<x-app-layout>
    <div class="space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-extrabold font-outfit text-slate-900">Knowledge Topics</h1>
                <p class="text-slate-400 text-sm mt-0.5">Follow specific topics to filter your feed and stay updated.</p>
            </div>
        </div>

        {{-- Grid of Topic Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($topics as $topic)
                <div class="group bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md hover:-translate-y-px transition-all duration-200 flex flex-col justify-between h-full space-y-4 shadow-sm">
                    <div class="space-y-2">
                        <h2 class="text-lg font-bold font-outfit text-slate-900 group-hover:text-slate-600 transition-colors">
                            <a href="{{ route('topics.show', $topic->slug) }}">{{ $topic->name }}</a>
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">{{ $topic->description }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ $topic->followers_count }} {{ $topic->followers_count === 1 ? 'follower' : 'followers' }}</span>
                        
                        @auth
                            <form action="{{ route('topics.follow', $topic->id) }}" method="POST">
                                @csrf
                                @php
                                    $isFollowing = auth()->user()->topics()->where('topic_id', $topic->id)->exists();
                                @endphp
                                <button type="submit" class="px-3.5 py-1.5 rounded-xl border text-xs font-bold transition-all {{ $isFollowing ? 'bg-slate-100 text-slate-800 border-slate-200 hover:bg-slate-200' : 'bg-slate-900 text-white border-slate-900 hover:bg-slate-800' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No topics found</h3>
                    <p class="text-slate-400 text-sm">Please check back later or contact an administrator to create topics.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>




