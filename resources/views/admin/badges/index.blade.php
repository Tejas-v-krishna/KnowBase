<x-admin-layout>
    <x-slot name="title">Manage Badges</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-page-entrance">
        <!-- Badge List (Left/Mid) -->
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">Badges</h1>
                <p class="text-sm text-slate-500 mt-1">View and manage automated system badges</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                                <th class="py-4 px-6">Badge</th>
                                <th class="py-4 px-6">Description</th>
                                <th class="py-4 px-6">Criteria</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm text-slate-700">
                            @foreach($badges as $badge)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 flex items-center space-x-3">
                                        <span class="text-3xl" role="img" aria-label="{{ $badge->name }} icon">{{ $badge->icon }}</span>
                                        <span class="font-bold text-slate-900 font-outfit">{{ $badge->name }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="text-xs text-slate-500 max-w-xs truncate" title="{{ $badge->description }}">{{ $badge->description }}</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize font-mono">
                                            {{ str_replace('_', ' ', $badge->criteria_type) }} &ge; {{ $badge->criteria_value }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('admin.badges.destroy', $badge->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this badge? It will be removed from all users who earned it.')">
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

                @if($badges->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $badges->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Form (Right) -->
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-extrabold font-outfit text-slate-900 tracking-tight">Create Badge</h2>
                <p class="text-sm text-slate-500 mt-1">Add an automated gamification reward</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <form action="{{ route('admin.badges.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Badge Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="e.g. Master Writer" required />
                        @error('name')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="icon" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Icon / Emoji</label>
                        <input type="text" name="icon" id="icon" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="e.g. ✍️ or 🏆" required />
                        @error('icon')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="Tell users how to unlock this badge..." required></textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="criteria_type" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Criteria Type</label>
                        <select name="criteria_type" id="criteria_type" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all cursor-pointer capitalize" required>
                            <option value="reputation">Reputation (XP)</option>
                            <option value="articles_count">Articles Published</option>
                            <option value="questions_count">Questions Asked</option>
                            <option value="answers_count">Answers Submitted</option>
                            <option value="accepted_answers_count">Accepted Answers</option>
                        </select>
                        @error('criteria_type')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="criteria_value" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Criteria Target Value</label>
                        <input type="number" name="criteria_value" id="criteria_value" min="1" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-400" placeholder="e.g. 500" required />
                        @error('criteria_value')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Create Badge
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
