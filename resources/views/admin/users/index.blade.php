<x-admin-layout>
    <x-slot name="title">Manage Users</x-slot>

    <div class="space-y-8 animate-page-entrance">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">User Management</h1>
                <p class="text-sm text-slate-500 mt-1">Review student accounts, assign moderator roles, and suspend users.</p>
            </div>
        </div>

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Stat Card 1: Total Users -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Registered</span>
                    <h3 class="text-3xl font-black font-outfit text-slate-900">{{ \App\Models\User::count() }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <!-- Stat Card 2: Staff -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Moderation Staff</span>
                    <h3 class="text-3xl font-black font-outfit text-slate-900">
                        {{ \App\Models\User::whereIn('role', ['admin', 'moderator'])->count() }}
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>

            <!-- Stat Card 3: Banned (Guests) -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Guests / Banned</span>
                    <h3 class="text-3xl font-black font-outfit text-slate-900">
                        {{ \App\Models\User::where('role', 'guest')->count() }}
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/50">
                            <th class="py-4 px-6">User Details</th>
                            <th class="py-4 px-6">Reputation &amp; Badge</th>
                            <th class="py-4 px-6">System Role</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm text-slate-700">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- User Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <x-user-avatar :user="$user" class="h-11 w-11 rounded-full shadow-sm ring-1 ring-slate-100" />
                                        <div>
                                            <div class="font-bold text-slate-900 font-outfit">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400 mt-0.5">
                                                <span>{{ '@' . $user->username }}</span>
                                                <span class="mx-1">&bull;</span>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Reputation -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <x-reputation-badge :user="$user" />
                                    </div>
                                </td>

                                <!-- Current Role Form -->
                                <td class="py-4 px-6">
                                    @if($user->id === auth()->id())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-900 text-white capitalize shadow-sm">
                                            {{ $user->role }} (You)
                                        </span>
                                    @else
                                        <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-1.5 border border-slate-200 rounded-full bg-slate-50 text-slate-700 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all cursor-pointer capitalize">
                                                <option value="guest" {{ $user->role === 'guest' ? 'selected' : '' }}>Guest</option>
                                                <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                                                <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>

                                <!-- Actions (Ban) -->
                                <td class="py-4 px-6 text-right">
                                    @if($user->id !== auth()->id())
                                        <div class="flex items-center justify-end space-x-2">
                                            @if($user->role !== 'guest')
                                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to suspend this user? This demotes them to Guest.')">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-bold text-slate-600 hover:text-red-600 bg-slate-50 hover:bg-red-50/50 border border-slate-200 hover:border-red-200 px-3.5 py-1.5 rounded-full transition-all">
                                                        Suspend User
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                    Suspended
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Owner account</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($users->hasPages())
                <div class="px-6 py-5 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
