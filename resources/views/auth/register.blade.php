<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Crear Cuenta</h2>
        <p class="text-gray-600 mt-2">Comienza tu experiencia con FitoAgro</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        @if(isset($planId) && $planId)
            <input type="hidden" name="plan_id" value="{{ $planId }}" />
        @endif

        <!-- Nombre y Apellido en dos columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nombre -->
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <!-- Apellido -->
            <div>
                <x-input-label for="apellido" value="Apellido" />
                <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
            </div>
        </div>

        <!-- Correo Electrónico -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Nombre de la Organización -->
        <div>
            <x-input-label for="organization" value="Nombre de la Organización" />
            <x-text-input id="organization" class="block mt-1 w-full" type="text" name="organization" :value="old('organization')" required autocomplete="organization" placeholder="Ej: Mi Empresa Agrícola" />
            <x-input-error :messages="$errors->get('organization')" class="mt-2" />
        </div>

        <!-- Contraseña y Confirmar en dos columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Contraseña -->
            <div>
                <x-input-label for="password" value="Contraseña" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex flex-col space-y-4 pt-2">
            <x-primary-button class="w-full justify-center py-3 text-base">
                Crear Cuenta
            </x-primary-button>

            <div class="text-center">
                <span class="text-sm text-gray-600">¿Ya tienes una cuenta?</span>
                <a class="text-sm text-green-600 hover:text-green-700 font-semibold ml-1" href="{{ route('login') }}">
                    Inicia sesión aquí
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>