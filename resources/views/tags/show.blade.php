<x-app-layout>
    <div class="space-y-8">
        <div class="bg-white border border-black rounded-3xl p-6 md:p-8 bg-black shadow-sm">
            <span class="text-xs text-black font-bold font-bold uppercase tracking-wider">Posts Tagged With</span>
            <h1 class="text-2xl md:text-3xl font-extrabold font-outfit text-black mt-1">#{{ $tag->name }}</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Articles -->
                <div class="space-y-4">
                    <h2 class="text-lg font-bold font-outfit border-b border-black pb-2 flex items-center ">
                        <span class="h-4.5 w-1 bg-black rounded-full mr-2"></span>
                        Articles
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($articles as $article)
                            <div class="bg-white border border-black rounded-2xl p-5 bg-black shadow-sm flex flex-col justify-between h-full">
                                <div>
                                    <h3 class="text-sm font-bold font-outfit hover:text-black font-bold "><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></h3>
                                    <p class="text-black text-[11px] mt-1.5 line-clamp-2">{{ $article->excerpt }}</p>
                                </div>
                                <span class="text-[9px] text-black font-bold uppercase mt-3 pt-2.5 border-t border-black ">By {{ $article->user->name }}</span>
                            </div>
                        @empty
                            <p class="text-black text-xs">No articles with this tag.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Questions -->
                <div class="space-y-4">
                    <h2 class="text-lg font-bold font-outfit border-b border-black pb-2 flex items-center ">
                        <span class="h-4.5 w-1 bg-violet-600 rounded-full mr-2"></span>
                        Questions
                    </h2>
                    <div class="space-y-3">
                        @forelse($questions as $question)
                            <div class="bg-white border border-black rounded-2xl p-4 bg-black shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-bold font-outfit hover:text-black font-bold "><a href="{{ route('questions.show', $question->slug) }}">{{ $question->title }}</a></h3>
                                    <span class="text-[10px] text-black">Asked by {{ $question->user->name }}</span>
                                </div>
                                <span class="bg-black border border-black text-black font-bold text-[10px] font-bold px-2 py-1 rounded-lg">{{ $question->answers_count }} answers</span>
                            </div>
                        @empty
                            <p class="text-black text-xs">No questions with this tag.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Discussion Threads -->
                <div class="space-y-4">
                    <h2 class="text-lg font-bold font-outfit border-b border-black pb-2 flex items-center ">
                        <span class="h-4.5 w-1 bg-gray-100 rounded-full mr-2"></span>
                        Discussions
                    </h2>
                    <div class="space-y-3">
                        @forelse($threads as $thread)
                            <div class="bg-white border border-black rounded-2xl p-4 bg-black shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-bold font-outfit hover:text-black font-bold "><a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a></h3>
                                    <span class="text-[10px] text-black">Started by {{ $thread->user->name }}</span>
                                </div>
                                <span class="bg-white border border-black text-black text-[10px] font-bold px-2 py-1 rounded-lg">{{ $thread->replies_count }} replies</span>
                            </div>
                        @empty
                            <p class="text-black text-xs">No forum threads with this tag.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




