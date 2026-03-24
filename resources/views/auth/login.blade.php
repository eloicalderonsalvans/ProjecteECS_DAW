<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white">Benvingut de nou</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Introdueix les teves credencials per accedir.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Correu Electrònic</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="w-full px-4 py-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#0a0f1d] text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm" 
                placeholder="hola@fitxaring.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm font-semibold text-red-600 dark:text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300">Contrasenya</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors" href="{{ route('password.request') }}">
                        He oblidat la contrasenya
                    </a>
                @endif
            </div>

            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="w-full px-4 py-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#0a0f1d] text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm" 
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm font-semibold text-red-600 dark:text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center py-2">
            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 bg-white dark:bg-[#0a0f1d] transition-all cursor-pointer">
            <label for="remember_me" class="ml-3 block text-sm font-medium text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                Mantén la sessió iniciada
            </label>
        </div>

        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-extrabold text-white bg-blue-600 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:-translate-y-0.5 mt-2">
            Iniciar Sessió
        </button>
    </form>
</x-guest-layout>
