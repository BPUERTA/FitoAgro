<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Iniciar Sesión</h2>
        <p class="text-gray-600 mt-2">Bienvenido de nuevo</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Login (Email o CUIT) -->
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                Correo Electrónico o CUIT
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="tu@email.com o CUIT"
                    class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3">
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-green-600 hover:text-green-700 font-medium" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Iniciar Sesión
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            @if(setting('registrations_locked', '0') === '1')
                <span class="text-sm text-gray-500">Los registros nuevos están deshabilitados.</span>
            @else
                <span class="text-sm text-gray-600">¿No tienes una cuenta?</span>
                <a class="text-sm text-green-600 hover:text-green-700 font-semibold ml-1" href="{{ route('register') }}">
                    Regístrate gratis
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
