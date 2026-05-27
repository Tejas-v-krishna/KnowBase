<x-admin-layout>
    <x-slot name="title">Manage Topics</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-page-entrance">
        <!-- Topic List (Left/Mid) -->
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">Topics</h1>
                <p class="text-sm text-slate-500 mt-1">Manage standard content categories</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                                <th class="py-4 px-6">Topic</th>
                                <th class="py-4 px-6">Description</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm text-slate-700">
                            @foreach($topics as $topic)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 flex items-center space-x-3">
                                        @if($topic->cover_image)
                                            <img src="{{ asset('storage/' . $topic->cover_image) }}" class="h-10 w-10 rounded-2xl object-cover border border-slate-100 shadow-sm" alt="{{ $topic->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-2xl bg-slate-50 border border-slate-200 text-slate-600 font-bold flex items-center justify-center text-xs uppercase shadow-sm font-outfit">
                                                {{ substr($topic->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <span class="font-bold text-slate-900 font-outfit">{{ $topic->name }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="text-xs text-slate-500 max-w-xs truncate" title="{{ $topic->description }}">{{ $topic->description ?? 'No description' }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <!-- Edit Form Trigger -->
                                            <button onclick="document.getElementById('edit-topic-form-{{ $topic->id }}').classList.toggle('hidden')" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-300 px-3.5 py-1.5 rounded-full transition-all">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this topic?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-slate-600 hover:text-red-600 bg-slate-50 hover:bg-red-50/50 border border-slate-200 hover:border-red-200 px-3.5 py-1.5 rounded-full transition-all">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Hidden inline update form -->
                                        <div id="edit-topic-form-{{ $topic->id }}" class="hidden text-left mt-4 p-5 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-4">
                                            <form action="{{ route('admin.topics.update', $topic->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div class="space-y-1.5">
                                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Topic Name</label>
                                                    <input type="text" name="name" value="{{ $topic->name }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 outline-none transition-all text-sm" required />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</label>
                                                    <textarea name="description" class="w-full px-4 py-2.5 border border-slate-200 rounded-2xl bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 outline-none transition-all text-sm" rows="3">{{ $topic->description }}</textarea>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cover Image</label>
                                                    <input type="file" name="cover_image" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                                                </div>
                                                <div class="flex items-center space-x-2 pt-2">
                                                    <button type="submit" class="text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 px-4 py-2 rounded-full transition-all">
                                                        Save
                                                    </button>
                                                    <button type="button" onclick="document.getElementById('edit-topic-form-{{ $topic->id }}').classList.add('hidden')" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-full transition-all">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($topics->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $topics->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Form (Right) -->
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-extrabold font-outfit text-slate-900 tracking-tight">Create Topic</h2>
                <p class="text-sm text-slate-500 mt-1">Add a new category to the platform</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <form action="{{ route('admin.topics.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Topic Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="e.g. Artificial Intelligence" required />
                        @error('name')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="Brief explanation of this category..."></textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="cover_image" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                        @error('cover_image')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Create Topic
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
