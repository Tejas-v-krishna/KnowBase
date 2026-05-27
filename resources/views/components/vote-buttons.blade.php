@props(['model', 'type'])

@php
    $userVote = auth()->check() ? $model->votes()->where('user_id', auth()->id())->first() : null;
    $upvoted = $userVote && $userVote->value === 1;
    $downvoted = $userVote && $userVote->value === -1;
@endphp

<div class="flex flex-col items-center space-y-1 select-none">
    <!-- Upvote -->
    <form action="{{ route('votes', ['type' => $type, 'id' => $model->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="value" value="1">
        <button type="submit" class="p-1 rounded-lg transition-all hover:bg-white :bg-white/50 {{ $upvoted ? 'text-black font-bold font-bold' : 'text-black hover:text-black' }}" title="Upvote">
            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>

    <!-- Vote Count -->
    <span class="text-sm font-extrabold font-outfit {{ ($model->votes_count ?? 0) > 0 ? 'text-black font-bold font-bold' : (($model->votes_count ?? 0) < 0 ? 'text-black font-bold' : 'text-black ') }}">
        {{ $model->votes_count ?? 0 }}
    </span>

    <!-- Downvote -->
    <form action="{{ route('votes', ['type' => $type, 'id' => $model->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="value" value="-1">
        <button type="submit" class="p-1 rounded-lg transition-all hover:bg-white :bg-white/50 {{ $downvoted ? 'text-black font-bold font-bold' : 'text-black hover:text-black font-bold' }}" title="Downvote">
            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
</div>



