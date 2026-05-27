@props(['question'])
<div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200/80 transition-all hover:-translate-y-0.5 duration-300 flex items-start space-x-5">
    <!-- Stats column -->
    <div class="flex flex-col items-center justify-center shrink-0 w-14 text-center select-none bg-slate-50 rounded-2xl p-2.5 border border-slate-100/50">
        <div class="text-lg font-black text-slate-800 leading-none">{{ $question->votes_count }}</div>
        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">votes</div>
        
        @php
            $isResolved = $question->status === 'resolved';
            $hasAnswers = $question->answers_count > 0;
            $answerBg = $isResolved 
                ? 'bg-emerald-500 text-white border-transparent' 
                : ($hasAnswers ? 'bg-slate-900 text-white border-transparent' : 'bg-white text-slate-500 border-slate-200');
        @endphp
        <div class="mt-2.5 w-full py-1 border rounded-xl text-xs font-extrabold shadow-sm transition-all {{ $answerBg }}" title="{{ $isResolved ? 'Resolved' : ($hasAnswers ? 'Has Answers' : 'No Answers') }}">
            {{ $question->answers_count }}
        </div>
        <div class="text-[8px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">answers</div>
     </div>
    
     <div class="flex-grow">
        <span class="px-2.5 py-1 bg-slate-50 text-slate-600 rounded-full font-bold text-[10px] uppercase tracking-wider">{{ $question->topic->name ?? 'General' }}</span>
        
        <h3 class="text-lg font-bold font-outfit mt-2 text-slate-900 hover:text-black hover:underline transition-colors leading-snug">
            <a href="{{ route('questions.show', $question->slug) }}">{{ $question->title }}</a>
        </h3>
        
        @if($question->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mt-3">
                @foreach($question->tags as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
            </div>
        @endif

        <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100">
            <div class="flex items-center space-x-2.5">
                <x-user-avatar :user="$question->user" class="h-6 w-6 rounded-lg" />
                <a href="{{ route('users.show', $question->user->username) }}" class="text-xs font-bold text-slate-700 hover:text-black hover:underline transition-colors">{{ $question->user->name }}</a>
                <x-reputation-badge :user="$question->user" />
            </div>
            <span class="text-xs text-slate-400 font-medium">{{ $question->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>



