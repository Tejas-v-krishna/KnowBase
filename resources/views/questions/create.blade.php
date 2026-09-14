<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="border-b border-slate-200 pb-5">
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900">Ask a Question</h1>
            <p class="text-slate-500 mt-1 font-medium">Get assistance from the KnowBase developer community.</p>
        </div>

        <form action="{{ route('questions.store') }}" method="POST" class="space-y-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-bold text-slate-800">Question Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $prefill ?? '') }}" required class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all" placeholder="e.g., How do I configure Laravel Scout with SQLite driver?">
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Be specific and imagine you're asking a question to another developer.</p>
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <!-- School Level -->
            <div>
                <label for="school_level" class="block text-sm font-bold text-slate-800">School Level</label>
                <select name="school_level" id="school_level" required class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all">
                    <option value="">Select a school level</option>
                    <option value="elementary" {{ old('school_level') == 'elementary' ? 'selected' : '' }}>Elementary School</option>
                    <option value="middle_school" {{ old('school_level') == 'middle_school' ? 'selected' : '' }}>Middle School</option>
                    <option value="high_school" {{ old('school_level') == 'high_school' ? 'selected' : '' }}>High School</option>
                    <option value="college" {{ old('school_level') == 'college' ? 'selected' : '' }}>College</option>
                </select>
                <x-input-error :messages="$errors->get('school_level')" class="mt-1" />
            </div>

            <!-- Topic -->
            <div>
                <label for="topic_id" class="block text-sm font-bold text-slate-800">Topic</label>
                <select name="topic_id" id="topic_id" required class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all">
                    <option value="">Select a Subject (Required)</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('topic_id')" class="mt-1" />
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-bold text-slate-800">Tags</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" class="block w-full mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-slate-900 text-sm transition-all" placeholder="laravel, scout, database">
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Separate multiple tags with commas.</p>
                <x-input-error :messages="$errors->get('tags')" class="mt-1" />
            </div>

            <!-- Body -->
            <div>
                <label for="body" class="block text-sm font-bold text-slate-800 mb-2">Question Description</label>
                <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                <trix-editor input="body"></trix-editor>
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Include details about your problem, what you tried, and any code blocks.</p>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>
            <!-- Bounty -->
            <div x-data="{ bounty: 0, balance: {{ auth()->user()->reputation }} }">
                <label for="bounty_amount" class="block text-sm font-bold text-slate-800">Add a Bounty (Optional)</label>
                <p class="text-xs text-slate-500 mt-1 font-medium mb-3">Attach XP to your question to attract faster, higher-quality answers. The bounty will be deducted from your balance immediately.</p>
                <div class="grid grid-cols-5 gap-3">
                    <template x-for="val in [0, 25, 50, 100, 200]">
                        <label class="relative flex flex-col items-center justify-center p-3 border rounded-xl cursor-pointer transition-all"
                            :class="{
                                'border-amber-500 bg-amber-50 shadow-sm': bounty === val && val > 0,
                                'border-slate-900 bg-slate-900 text-white shadow-sm': bounty === val && val === 0,
                                'border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700': bounty !== val,
                                'opacity-50 cursor-not-allowed': balance < (10 + val) && val > 0
                            }">
                            <input type="radio" name="bounty_amount" :value="val" x-model.number="bounty" class="sr-only" :disabled="balance < (10 + val) && val > 0">
                            <span class="text-sm font-bold" x-text="val === 0 ? 'No Bounty' : '+' + val + ' XP'"></span>
                        </label>
                    </template>
                </div>
                <x-input-error :messages="$errors->get('bounty_amount')" class="mt-2" />
                
                <!-- Dynamic Cost Display -->
                <div class="mt-6 flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <span class="text-sm font-bold text-slate-700">Total Cost to Ask:</span>
                    <div class="text-right">
                        <span class="text-lg font-black text-slate-900" x-text="(10 + bounty) + ' XP'"></span>
                        <div class="text-xs text-slate-500 font-medium">10 XP base + <span x-text="bounty"></span> XP bounty</div>
                    </div>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                <div class="flex items-center space-x-2 text-sm text-slate-500">
                    <svg class="h-5 w-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 011-1h1.5a.5.5 0 01.5.5V3.8c0 .248-.093.486-.26.657l-.872.871-3.328 3.328a1 1 0 01-1.414 0l-.707-.707a1 1 0 010-1.414l3.328-3.328.871-.872A.93.93 0 0112 2zm0 10a1 1 0 011-1h1.5a.5.5 0 01.5.5v1.3c0 .248-.093.486-.26.657l-.872.871-3.328 3.328a1 1 0 01-1.414 0l-.707-.707a1 1 0 010-1.414l3.328-3.328.871-.872A.93.93 0 0112 12z" clip-rule="evenodd"/>
                    </svg>
                    <span>Your Balance: <span class="font-bold text-slate-900">{{ auth()->user()->reputation }} XP</span></span>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('questions.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm transition-all">Cancel</a>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm" :disabled="balance < (10 + bounty)">
                        Ask Question
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

