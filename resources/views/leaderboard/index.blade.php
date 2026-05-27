<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 mb-2">Global Leaderboard</h1>
                <p class="text-slate-500 text-sm">The smartest minds on KnowBase. Earn points by helping others!</p>
            </div>

            {{-- Leaderboard List --}}
            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="divide-y divide-slate-50">
                    @forelse($topUsers as $index => $user)
                        @php
                            $isTopThree = $index < 3;
                            $rowClass = $isTopThree ? 'bg-slate-50/50' : 'bg-white';
                            $rankColor = $index === 0 ? 'text-slate-900' : ($index === 1 ? 'text-slate-700' : ($index === 2 ? 'text-slate-500' : 'text-slate-300'));
                            $rankSize = $index === 0 ? 'text-2xl' : ($index === 1 ? 'text-xl' : ($index === 2 ? 'text-lg' : 'text-base'));
                            $avatarBorder = $isTopThree ? 'border-slate-300' : 'border-slate-100';
                        @endphp
                        
                        <div class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors {{ $rowClass }}">
                            <div class="flex items-center gap-5">
                                {{-- Rank --}}
                                <div class="w-10 text-center font-bold font-outfit {{ $rankColor }} {{ $rankSize }}">
                                    #{{ $index + 1 }}
                                </div>

                                {{-- Avatar --}}
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="h-12 w-12 rounded-full object-cover border-2 {{ $avatarBorder }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-lg border-2 {{ $avatarBorder }}">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif

                                {{-- User Info --}}
                                <div>
                                    <a href="{{ route('users.show', $user->username) }}" class="text-base font-bold text-slate-900 hover:text-slate-600 transition-colors flex items-center gap-1.5">
                                        {{ $user->name }}
                                        @if($user->is_expert)
                                            <svg class="w-4 h-4 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </a>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 uppercase tracking-widest border border-slate-200">
                                            {{ $user->rank }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">{{ '@' . $user->username }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Points --}}
                            <div class="text-right">
                                <div class="text-xl font-extrabold text-slate-900">{{ number_format($user->reputation) }}</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Points</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-slate-400 text-sm">No users found on the leaderboard yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
