@props(['notification'])

@php
    $data = $notification->data;
    $type = $data['type'] ?? 'system';
    $actor = $data['actor_name'] ?? 'System';
    $title = $data['content_title'] ?? '';
    $url = $data['url'] ?? '#';
    $read = $notification->read_at !== null;
@endphp

<div class="flex items-start space-x-4 p-4 rounded-2xl border transition-all duration-200 {{ $read ? 'bg-white border-slate-100' : 'bg-slate-50 border-slate-200/60 shadow-sm' }}">
    <!-- Notification Icon -->
    <div class="shrink-0">
        @if($type === 'badge')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0L4 8m8 0l8-8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1M4.805 6.468C3.124 7.202 2 8.902 2 10.882c0 2.21 1.79 4 4 4h1a8 8 0 0016 0h1c2.21 0 4-1.79 4-4 0-1.98-1.124-3.68-2.805-4.414M12 17h.01" />
                </svg>
            </div>
        @elseif($type === 'answer')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
        @elseif($type === 'accepted')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        @elseif($type === 'reply')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
            </div>
        @elseif($type === 'comment')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
        @elseif($type === 'follow')
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
        @else
            <div class="h-10 w-10 rounded-xl bg-slate-900 border border-slate-900 text-white flex items-center justify-center font-bold shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Notification Details -->
    <div class="flex-grow">
        <p class="text-sm text-slate-600 leading-snug">
            <span class="font-bold text-slate-900 font-outfit">{{ $actor }}</span>
            @if($type === 'badge')
                earned the badge <span class="font-bold text-slate-800">"{{ $title }}"</span>!
            @elseif($type === 'answer')
                answered your question <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">"{{ $title }}"</a>.
            @elseif($type === 'accepted')
                accepted your answer on <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">"{{ $title }}"</a>.
            @elseif($type === 'reply')
                replied to your thread <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">"{{ $title }}"</a>.
            @elseif($type === 'comment')
                commented on your article <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">"{{ $title }}"</a>.
            @elseif($type === 'follow')
                started following you! View their <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">profile</a>.
            @else
                interacted with your content <a href="{{ $url }}" class="font-bold text-slate-900 hover:underline">"{{ $title }}"</a>.
            @endif
        </p>
        <span class="text-[10px] font-semibold text-slate-400 mt-1 block">
            {{ $notification->created_at->diffForHumans() }}
        </span>
    </div>

    <!-- Unread Dot Indicator -->
    @if(!$read)
        <div class="shrink-0 mt-2">
            <span class="h-2.5 w-2.5 rounded-full bg-slate-900 block animate-pulse" title="Unread"></span>
        </div>
    @endif
</div>
