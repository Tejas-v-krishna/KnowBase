<x-guest-layout>
    <!-- Illustration for the left panel -->
    <x-slot:illustration>
        <img src="{{ asset('images/login-illustration.png') }}"
             alt="Person exploring knowledge"
             class="w-full h-full object-cover">
    </x-slot:illustration>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <div class="mb-8">
        <h2 class="text-4xl font-outfit text-slate-900 mb-2 font-normal">Log in</h2>
        <p class="text-sm font-medium text-slate-500">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:underline underline-offset-2">Create an Account</a>
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address or Username -->
        <div>
            <label for="login" class="block text-sm font-semibold text-slate-900 mb-2">Email or Username</label>
            <input id="login" type="text" name="login" :value="old('login')" required autofocus class="block w-full px-5 py-3 border border-slate-200 rounded-full shadow-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-sm transition-all" placeholder="alex@example.com or alex_smith">
            <x-input-error :messages="$errors->get('login')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full px-5 py-3 border border-slate-200 rounded-full shadow-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none text-sm transition-all" placeholder="Password">
                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            
            <div class="flex justify-end mt-2">
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-slate-900 hover:underline underline-offset-2" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <button type="submit" class="w-full py-3.5 bg-black hover:bg-slate-800 text-white rounded-full font-bold text-sm transition-all shadow-md">
            Log in
        </button>

        <!-- Remember Me (Replaces Terms in mockup for Login) -->
        <div class="flex items-center">
            <div class="flex h-5 items-center">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900 bg-slate-50 cursor-pointer">
            </div>
            <div class="ml-2 text-sm">
                <label for="remember_me" class="font-medium text-slate-700 cursor-pointer select-none">Remember me</label>
            </div>
        </div>

        <!-- Social Login Separator -->
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-slate-100"></div>
            </div>
            <div class="relative flex justify-center text-sm font-medium leading-6">
                <span class="bg-white px-4 text-slate-400">or</span>
            </div>
        </div>

        <!-- Social Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-full hover:bg-slate-50 transition-colors text-xs font-semibold text-slate-600">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google">
                Continue with Google
            </button>
            <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-full hover:bg-slate-50 transition-colors text-xs font-semibold text-slate-600">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-4 h-4" alt="Facebook">
                Continue with Facebook
            </button>
        </div>

    </form>
</x-guest-layout>
