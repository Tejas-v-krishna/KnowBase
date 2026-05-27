@props(['user'])
@php
    $rank = 'Beginner';
    if ($user->reputation >= 1000) {
        $rank = 'Genius';
    } elseif ($user->reputation >= 500) {
        $rank = 'Ambitious';
    } elseif ($user->reputation >= 100) {
        $rank = 'Helping Hand';
    }
@endphp
<div class="inline-flex items-center space-x-1 bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded-lg text-[10px] dark:bg-indigo-950/40 dark:border-indigo-900/50 dark:text-indigo-400 font-outfit select-none shadow-sm" title="{{ $rank }} ({{ $user->reputation }} XP)">
    <svg class="h-3 w-3 mr-0.5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
    </svg>
    <span>{{ $rank }} &bull; {{ $user->reputation }} XP</span>
</div>
