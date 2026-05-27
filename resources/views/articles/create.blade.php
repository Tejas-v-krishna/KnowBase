<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="border-b border-slate-200 pb-5">
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900">Write a New Article</h1>
            <p class="text-slate-500 mt-1 font-medium">Share your expertise and knowledge with the community.</p>
        </div>

        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-bold text-slate-800">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all" placeholder="Enter article title...">
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <!-- Topic -->
            <div>
                <label for="topic_id" class="block text-sm font-bold text-slate-800">Topic</label>
                <select name="topic_id" id="topic_id" class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all">
                    <option value="">Select a topic (Optional)</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('topic_id')" class="mt-1" />
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-bold text-slate-800">Summary / Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="2" class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all" placeholder="A brief description of what this article is about...">{{ old('excerpt') }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-1" />
            </div>

            <!-- Cover Image -->
            <div>
                <label for="cover_image" class="block text-sm font-bold text-slate-800">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="block w-full mt-2 text-sm text-slate-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer">
                <x-input-error :messages="$errors->get('cover_image')" class="mt-1" />
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-bold text-slate-800">Tags (comma-separated)</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all" placeholder="laravel, php, vue">
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Separate multiple tags with a comma.</p>
                <x-input-error :messages="$errors->get('tags')" class="mt-1" />
            </div>

            <!-- Body (Trix Editor) -->
            <div>
                <label for="body" class="block text-sm font-bold text-slate-800 mb-2">Article Body</label>
                <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                <trix-editor input="body"></trix-editor>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-bold text-slate-800">Status</label>
                <select name="status" id="status" class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all">
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publish Immediately</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Save as Draft</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-200">
                <a href="{{ route('articles.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm">Save Article</button>
            </div>
        </form>
    </div>
</x-app-layout>




