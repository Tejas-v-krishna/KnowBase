@props(['tag'])
<a href="{{ route('tags.show', $tag->slug) }}" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 transition-colors duration-200 font-sans tracking-wide uppercase select-none border border-slate-200/40">
    #{{ $tag->name }}
</a>



