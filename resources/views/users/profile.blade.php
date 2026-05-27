<x-app-layout>
    <x-slot name="title">{{ $user->name }}'s Profile</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar / User Info Card -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <x-user-avatar :user="$user" class="h-28 w-28 rounded-2xl mb-4" />
                    
                    <h2 class="text-xl font-bold font-outfit text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium">@<span>{{ $user->username }}</span></p>
                    
                    <div class="mt-3 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 capitalize">
                            {{ $user->role }}
                        </span>
                        <x-reputation-badge :user="$user" />
                    </div>

                    @if($user->bio)
                        <p class="mt-4 text-sm text-slate-600 line-clamp-4">
                            {{ $user->bio }}
                        </p>
                    @else
                        <p class="mt-4 text-xs italic text-slate-400">
                            No bio provided yet.
                        </p>
                    @endif

                    <div class="mt-6 w-full pt-6 border-t border-slate-100 flex justify-around text-center">
                        <div>
                            <span class="block text-lg font-bold text-slate-900">{{ $user->articles()->where('status', 'published')->count() }}</span>
                            <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Articles</span>
                        </div>
                        <div>
                            <span class="block text-lg font-bold text-slate-900">{{ $user->questions()->count() }}</span>
                            <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Questions</span>
                        </div>
                        <div>
                            <span class="block text-lg font-bold text-slate-900">{{ $user->reputation }}</span>
                            <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Points</span>
                        </div>
                    </div>

                    @if(auth()->id() === $user->id)
                        <div class="mt-6 w-full">
                            <a href="{{ route('settings.edit') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-sm font-semibold rounded-full transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Edit Profile Settings
                            </a>
                        </div>
                    @else
                        <div class="mt-6 w-full">
                            @auth
                                <form action="{{ route('users.follow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 {{ auth()->user()->isFollowing($user) ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' : 'bg-slate-900 hover:bg-slate-800 text-white shadow-sm' }} text-sm font-semibold rounded-full transition-all">
                                        @if(auth()->user()->isFollowing($user))
                                            <svg class="h-4 w-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                            </svg>
                                            Unfollow
                                        @else
                                            <svg class="h-4 w-4 mr-2 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Follow
                                        @endif
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white shadow-sm text-sm font-semibold rounded-full transition-all">
                                    <svg class="h-4 w-4 mr-2 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Follow
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            <!-- Badges Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-bold font-outfit text-slate-900 uppercase tracking-wider mb-4">Badges ({{ $user->badges->count() }})</h3>
                @if($user->badges->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($user->badges as $badge)
                            <div class="flex items-center space-x-2.5 p-2.5 rounded-xl border border-slate-100 bg-slate-50">
                                <span class="text-2xl" role="img" aria-label="{{ $badge->name }} icon">{{ $badge->icon }}</span>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate" title="{{ $badge->name }}">{{ $badge->name }}</h4>
                                    <p class="text-[10px] text-slate-500 truncate" title="{{ $badge->description }}">{{ $badge->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs italic text-slate-400 text-center py-4">No badges awarded yet.</p>
                @endif
            </div>

            <!-- Followed Topics Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-bold font-outfit text-slate-900 uppercase tracking-wider mb-4">Following Topics ({{ $user->topics->count() }})</h3>
                @if($user->topics->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->topics as $topic)
                            <a href="{{ route('topics.show', $topic->slug) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold rounded-full transition-colors">
                                <span class="mr-1.5 text-xs">📚</span>
                                {{ $topic->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs italic text-slate-400 text-center py-4">Not following any topics yet.</p>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200 flex space-x-6 overflow-x-auto">
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'articles']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'articles' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Articles
                </a>
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'questions']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'questions' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Questions
                </a>
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'answers']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'answers' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Answers
                </a>
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'collections']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'collections' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Collections
                </a>
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'followers']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'followers' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Followers ({{ $user->followers()->count() }})
                </a>
                <a href="{{ route('users.show', ['username' => $user->username, 'tab' => 'following']) }}" class="pb-4 font-outfit text-sm font-bold tracking-tight border-b-2 transition-colors shrink-0 {{ $tab === 'following' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Following ({{ $user->following()->count() }})
                </a>
            </div>

            <!-- Tab Content -->
            <div class="space-y-4">
                @if($tab === 'articles')
                    @forelse($articles as $article)
                        <x-article-card :article="$article" />
                    @empty
                        <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                            <p class="text-sm font-medium">No published articles yet.</p>
                        </div>
                    @endforelse
                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>

                @elseif($tab === 'questions')
                    @forelse($questions as $question)
                        <x-question-card :question="$question" />
                    @empty
                        <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                            <p class="text-sm font-medium">No questions asked yet.</p>
                        </div>
                    @endforelse
                    <div class="mt-4">
                        {{ $questions->links() }}
                    </div>

                @elseif($tab === 'answers')
                    @forelse($answers as $answer)
                        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow space-y-3">
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Answered on <a href="{{ route('questions.show', $answer->question->slug) }}" class="text-slate-900 font-bold hover:underline normal-case">{{ $answer->question->title }}</a>
                            </div>
                            <div class="text-slate-600 text-sm line-clamp-3">
                                {!! strip_tags($answer->body) !!}
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs text-slate-400">
                                <span>{{ $answer->votes_count }} votes</span>
                                <span>{{ $answer->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                            <p class="text-sm font-medium">No answers provided yet.</p>
                        </div>
                    @endforelse
                    <div class="mt-4">
                        {{ $answers->links() }}
                    </div>

                @elseif($tab === 'collections')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($collections as $collection)
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $collection->is_public ? 'bg-slate-100 text-slate-600' : 'bg-slate-900 text-white' }}">
                                            {{ $collection->is_public ? 'Public' : 'Private' }}
                                        </span>
                                    </div>
                                    <h4 class="font-outfit font-bold text-base text-slate-900 hover:text-slate-600 transition-colors">
                                        <a href="{{ route('users.collections.show', ['username' => $user->username, 'id' => $collection->id]) }}">{{ $collection->name }}</a>
                                    </h4>
                                    @if($collection->description)
                                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">{{ $collection->description }}</p>
                                    @endif
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-400 flex items-center justify-between">
                                    <span>{{ $collection->items()->count() }} items</span>
                                    <span>{{ $collection->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                                <p class="text-sm font-medium">No collections found.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $collections->links() }}
                    </div>

                @elseif($tab === 'followers')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-blur-in">
                        @forelse($followers as $followerUser)
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <x-user-avatar :user="$followerUser" class="h-12 w-12 rounded-xl" />
                                    <div>
                                        <h4 class="font-outfit font-bold text-sm text-slate-900 hover:text-slate-600 transition-colors">
                                            <a href="{{ route('users.show', $followerUser->username) }}">{{ $followerUser->name }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-500">@{{ $followerUser->username }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wide">{{ $followerUser->rank }} &bull; {{ $followerUser->reputation }} XP</p>
                                    </div>
                                </div>
                                @if(auth()->check() && auth()->id() !== $followerUser->id)
                                    <form action="{{ route('users.follow', $followerUser->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="inline-flex justify-center items-center px-4 py-1.5 {{ auth()->user()->isFollowing($followerUser) ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' : 'bg-slate-900 hover:bg-slate-800 text-white' }} text-xs font-bold rounded-full transition-all">
                                            {{ auth()->user()->isFollowing($followerUser) ? 'Unfollow' : 'Follow' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                                <p class="text-sm font-medium">No followers yet.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $followers->links() }}
                    </div>

                @elseif($tab === 'following')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-blur-in">
                        @forelse($following as $followingUser)
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <x-user-avatar :user="$followingUser" class="h-12 w-12 rounded-xl" />
                                    <div>
                                        <h4 class="font-outfit font-bold text-sm text-slate-900 hover:text-slate-600 transition-colors">
                                            <a href="{{ route('users.show', $followingUser->username) }}">{{ $followingUser->name }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-500">@{{ $followingUser->username }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wide">{{ $followingUser->rank }} &bull; {{ $followingUser->reputation }} XP</p>
                                    </div>
                                </div>
                                @if(auth()->check() && auth()->id() !== $followingUser->id)
                                    <form action="{{ route('users.follow', $followingUser->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="inline-flex justify-center items-center px-4 py-1.5 {{ auth()->user()->isFollowing($followingUser) ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' : 'bg-slate-900 hover:bg-slate-800 text-white' }} text-xs font-bold rounded-full transition-all">
                                            {{ auth()->user()->isFollowing($followingUser) ? 'Unfollow' : 'Follow' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full bg-slate-50 border border-slate-100 rounded-3xl p-8 text-center text-slate-500">
                                <p class="text-sm font-medium">Not following anyone yet.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $following->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
