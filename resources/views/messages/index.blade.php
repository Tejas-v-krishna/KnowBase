<x-app-layout>
    <x-slot name="title">Inbox</x-slot>

    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm flex h-[700px]">
        
        <!-- Conversations List (Sidebar) -->
        <div class="w-1/3 border-r border-slate-100 flex flex-col bg-slate-50">
            <div class="p-4 border-b border-slate-100">
                <h2 class="text-lg font-bold font-outfit text-slate-900">Messages</h2>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conv)
                    @php
                        $otherUser = $conv->getOtherUser(auth()->user());
                        $isActive = $activeConversation && $activeConversation->id === $conv->id;
                        $hasUnread = $conv->messages->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count() > 0;
                    @endphp
                    <a href="{{ route('messages.index', ['c' => $conv->id]) }}" class="block p-4 border-b border-slate-100 hover:bg-white transition-colors {{ $isActive ? 'bg-white shadow-sm relative z-10' : '' }}">
                        <div class="flex items-center space-x-3">
                            <x-user-avatar :user="$otherUser" class="h-10 w-10 rounded-xl" />
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-bold text-slate-900 truncate {{ $hasUnread && !$isActive ? 'text-slate-900 font-extrabold' : '' }}">{{ $otherUser->name }}</p>
                                    @if($conv->latestMessage)
                                        <p class="text-[10px] text-slate-400">{{ $conv->latestMessage->created_at->shortAbsoluteDiffForHumans() }}</p>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 truncate {{ $hasUnread && !$isActive ? 'font-bold text-slate-800' : '' }}">
                                    @if($conv->latestMessage)
                                        @if($conv->latestMessage->sender_id === auth()->id())
                                            <span class="text-slate-400">You:</span>
                                        @endif
                                        {{ $conv->latestMessage->body }}
                                    @else
                                        <span class="italic text-slate-400">No messages yet.</span>
                                    @endif
                                </p>
                            </div>
                            @if($hasUnread && !$isActive)
                                <div class="h-2.5 w-2.5 bg-slate-900 rounded-full"></div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        No conversations yet. <br> Visit a user's profile to send them a message!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-2/3 flex flex-col bg-white">
            @if($activeConversation)
                @php
                    $otherUser = $activeConversation->getOtherUser(auth()->user());
                @endphp
                
                <!-- Chat Header -->
                <div class="p-4 border-b border-slate-100 flex items-center space-x-3 bg-white">
                    <x-user-avatar :user="$otherUser" class="h-10 w-10 rounded-xl" />
                    <div>
                        <h3 class="text-base font-bold text-slate-900"><a href="{{ route('users.show', $otherUser->username) }}" class="hover:underline">{{ $otherUser->name }}</a></h3>
                        <p class="text-xs text-slate-500">{{ '@' . $otherUser->username }}</p>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/50" id="chat-window">
                    @foreach($activeConversation->messages as $msg)
                        @if($msg->sender_id === auth()->id())
                            <!-- Sent Message -->
                            <div class="flex justify-end">
                                <div class="max-w-[75%] bg-slate-900 text-white rounded-2xl rounded-tr-none px-4 py-2.5 text-sm shadow-sm">
                                    {{ $msg->body }}
                                    <div class="text-[9px] text-slate-300 mt-1 text-right">{{ $msg->created_at->format('g:i A') }}</div>
                                </div>
                            </div>
                        @else
                            <!-- Received Message -->
                            <div class="flex justify-start space-x-2">
                                <x-user-avatar :user="$msg->sender" class="h-8 w-8 rounded-lg shrink-0 mt-auto" />
                                <div class="max-w-[75%] bg-white border border-slate-100 text-slate-800 rounded-2xl rounded-tl-none px-4 py-2.5 text-sm shadow-sm">
                                    {{ $msg->body }}
                                    <div class="text-[9px] text-slate-400 mt-1">{{ $msg->created_at->format('g:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-slate-100 bg-white">
                    <form action="{{ route('messages.store', $activeConversation) }}" method="POST" class="flex space-x-3">
                        @csrf
                        <input type="text" name="body" required autocomplete="off" placeholder="Type your message..." class="flex-1 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-slate-200 focus:border-slate-300 text-sm transition-colors outline-none px-4 py-2">
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center space-x-2">
                            <span>Send</span>
                            <svg class="h-4 w-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>

                <script>
                    // Scroll to bottom of chat
                    const chatWindow = document.getElementById('chat-window');
                    if (chatWindow) {
                        chatWindow.scrollTop = chatWindow.scrollHeight;
                    }
                </script>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 space-y-4 bg-slate-50/50">
                    <div class="h-20 w-20 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center">
                        <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Select a conversation or start a new one.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
