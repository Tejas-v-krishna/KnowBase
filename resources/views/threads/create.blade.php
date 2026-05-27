<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-8 py-6">
        <div class="border-b border-slate-100 pb-5">
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900">Start a Discussion Thread</h1>
            <p class="text-slate-400 mt-1 text-sm">Ask the community, share project announcements, or spark conversations.</p>
        </div>

        <form action="{{ route('threads.store') }}" method="POST" class="space-y-6 bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Thread Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="block w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all" placeholder="What would you like to discuss?">
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <!-- Topic -->
            <div>
                <label for="topic_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Topic</label>
                <select name="topic_id" id="topic_id" class="block w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all cursor-pointer">
                    <option value="">Select a topic (Optional)</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('topic_id')" class="mt-1" />
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Tags</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" class="block w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all" placeholder="laravel, packages, general (comma separated)">
                <x-input-error :messages="$errors->get('tags')" class="mt-1" />
            </div>

            <!-- Body -->
            <div>
                <label for="body" class="block text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Thread Body</label>
                <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                <trix-editor input="body" class="trix-content min-h-[200px] text-sm text-slate-800"></trix-editor>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('threads.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-400 font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Start Thread</button>
            </div>
        </form>
    </div>
</x-app-layout>
