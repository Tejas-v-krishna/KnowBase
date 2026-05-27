@props(['thread'])
<div class="bg-white rounded-2xl p-5 border border-black bg-black shadow-sm hover:shadow-md transition-all duration-200">
    <div class="flex items-center justify-between">
        <span class="text-xs text-black font-bold font-bold uppercase tracking-wider">{{ $thread->topic->name ?? 'General' }}</span>
        @if($thread->is_pinned)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-black font-bold font-bold select-none">
                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
                Pinned
            </span>
        @endif
    </div>

    <h3 class="text-lg font-bold font-outfit mt-1 hover:text-black font-bold :text-black font-bold transition-colors">
        <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
    </h3>

    @if($thread->tags->isNotEmpty())
        <div class="flex flex-wrap gap-1.5 mt-2">
            @foreach($thread->tags as $tag)
                <x-tag-badge :tag="$tag" />
            @endforeach
        </div>
    @endif

    <div class="flex items-center justify-between mt-4 pt-4 border-t border-black ">
        <div class="flex items-center space-x-2">
            <x-user-avatar :user="$thread->user" class="h-6 w-6 rounded-full" />
            <a href="{{ route('users.show', $thread->user->username) }}" class="text-xs font-semibold text-black hover:text-black font-bold ">{{ $thread->user->name }}</a>
            <x-reputation-badge :user="$thread->user" />
        </div>
        <div class="flex items-center space-x-3 text-xs text-black">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ $thread->replies_count }} replies
            </span>
            <span>{{ $thread->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>



