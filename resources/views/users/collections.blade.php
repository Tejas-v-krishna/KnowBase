<x-app-layout>
    <x-slot name="title">{{ $user->name }}'s Collections</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold font-outfit text-slate-900">Collections</h1>
                <p class="text-sm text-slate-500">Curated resources by {{ $user->name }}</p>
            </div>
            <a href="{{ route('users.show', $user->username) }}" class="inline-flex items-center px-4 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
                Back to Profile
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($collections as $collection)
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $collection->is_public ? 'bg-slate-100 text-slate-600' : 'bg-slate-900 text-white' }}">
                                {{ $collection->is_public ? 'Public' : 'Private' }}
                            </span>
                            <span class="text-xs text-slate-400">{{ $collection->updated_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-slate-900 hover:text-slate-600 transition-colors">
                            <a href="{{ route('users.collections.show', ['username' => $user->username, 'id' => $collection->id]) }}">{{ $collection->name }}</a>
                        </h3>
                        @if($collection->description)
                            <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ $collection->description }}</p>
                        @endif
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 text-xs font-bold text-slate-400 flex items-center justify-between">
                        <span>{{ $collection->items()->count() }} items</span>
                        <a href="{{ route('users.collections.show', ['username' => $user->username, 'id' => $collection->id]) }}" class="text-slate-900 hover:underline">View Collection &rarr;</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-slate-50 border border-slate-100 rounded-3xl p-12 text-center text-slate-500 shadow-sm">
                    <p class="text-base font-semibold">No collections found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $collections->links() }}
        </div>
    </div>
</x-app-layout>
