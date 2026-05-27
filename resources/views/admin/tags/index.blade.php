<x-admin-layout>
    <x-slot name="title">Manage Tags</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-page-entrance">
        <!-- Tag List (Left/Mid) -->
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">Tags</h1>
                <p class="text-sm text-slate-500 mt-1">View and organize user-created tags</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                                <th class="py-4 px-6">Tag Name</th>
                                <th class="py-4 px-6">Slug</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm text-slate-700">
                            @foreach($tags as $tag)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 font-semibold">
                                        <x-tag-badge :tag="$tag" />
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-slate-500">
                                        #{{ $tag->slug }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tag? It will be removed from all articles/questions/threads.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-slate-600 hover:text-red-600 bg-slate-50 hover:bg-red-50/50 border border-slate-200 hover:border-red-200 px-3.5 py-1.5 rounded-full transition-all">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($tags->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $tags->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Form (Right) -->
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-extrabold font-outfit text-slate-900 tracking-tight">Create Tag</h2>
                <p class="text-sm text-slate-500 mt-1">Create a predefined system tag</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Tag Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="e.g. laravel-11" required />
                        @error('name')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="Brief explanation of what this tag covers..."></textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Create Tag
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
