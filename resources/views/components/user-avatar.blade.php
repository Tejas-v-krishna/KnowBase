@props(['user'])
@if($user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" {{ $attributes->merge(['class' => 'h-10 w-10 rounded-xl object-cover border border-black shadow-sm']) }} alt="{{ $user->name }}">
@else
    <div {{ $attributes->merge(['class' => 'h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-extrabold text-xs uppercase select-none border border-black/20 shadow-sm']) }}>
        {{ strtoupper(substr($user->name, 0, 2)) }}
    </div>
@endif



