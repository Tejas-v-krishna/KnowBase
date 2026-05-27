<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="border-b border-black pb-5 ">
            <h1 class="text-3xl font-extrabold font-outfit text-black ">Edit Article</h1>
            <p class="text-black mt-1 ">Updating "{{ $article->title }}"</p>
        </div>

        <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl border border-black shadow-sm bg-black ">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-black ">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required class="block w-full mt-1.5 px-4 py-2.5 border border-black bg-black rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm transition-all">
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <!-- Topic -->
            <div>
                <label for="topic_id" class="block text-sm font-semibold text-black ">Topic</label>
                <select name="topic_id" id="topic_id" class="block w-full mt-1.5 px-4 py-2.5 border border-black bg-black rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm transition-all">
                    <option value="">Select a topic (Optional)</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id', $article->topic_id) == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('topic_id')" class="mt-1" />
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-semibold text-black ">Summary / Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="2" class="block w-full mt-1.5 px-4 py-2.5 border border-black bg-black rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm transition-all">{{ old('excerpt', $article->excerpt) }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-1" />
            </div>

            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-semibold text-black ">Cover Image</label>
                @if($article->cover_image)
                    <div class="my-3">
                        <img src="{{ asset('storage/' . $article->cover_image) }}" class="h-20 w-36 object-cover rounded-lg border">
                    </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" class="block w-full mt-1.5 text-sm text-black file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-black file:text-black font-bold hover:file:bg-black">
                <x-input-error :messages="$errors->get('cover_image')" class="mt-1" />
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-semibold text-black ">Tags (comma-separated)</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', $tagsString) }}" class="block w-full mt-1.5 px-4 py-2.5 border border-black bg-black rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm transition-all">
                <p class="text-[10px] text-black mt-1">Separate multiple tags with a comma.</p>
                <x-input-error :messages="$errors->get('tags')" class="mt-1" />
            </div>

            <!-- Body (Trix Editor) -->
            <div>
                <label for="body" class="block text-sm font-semibold text-black mb-1.5">Article Body</label>
                <input id="body" type="hidden" name="body" value="{{ old('body', $article->body) }}">
                <trix-editor input="body"></trix-editor>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-black ">Status</label>
                <select name="status" id="status" class="block w-full mt-1.5 px-4 py-2.5 border border-black bg-black rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm transition-all">
                    <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-black ">
                <a href="{{ route('articles.show', $article->slug) }}" class="px-5 py-2.5 rounded-xl border border-black text-black hover:bg-white font-bold text-sm transition-all ">Cancel</a>
                <button type="submit" class="bg-black hover:bg-black text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Update Article</button>
            </div>
        </form>
    </div>
</x-app-layout>




