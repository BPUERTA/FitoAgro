<x-app-layout>
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nuevo Usuario</h1>
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('users.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
            <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('apellido')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="password" name="password" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Crear</button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>

