<x-app-layout>
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Profesional</h1>
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('professionals.update', $professional) }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo <span class="text-red-500">*</span></label>
            <input type="text" id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $professional->nombre_completo) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('nombre_completo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula <span class="text-red-500">*</span></label>
            <input type="text" id="matricula" name="matricula" value="{{ old('matricula', $professional->matricula) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('matricula')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="numero_registro" class="block text-sm font-medium text-gray-700">N° Registro <span class="text-gray-500 text-xs">(solo para recetas de venta)</span></label>
            <input type="text" id="numero_registro" name="numero_registro" value="{{ old('numero_registro', $professional->numero_registro) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('numero_registro')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="localidad" class="block text-sm font-medium text-gray-700">Localidad <span class="text-red-500">*</span></label>
            <input type="text" id="localidad" name="localidad" value="{{ old('localidad', $professional->localidad) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('localidad')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia <span class="text-red-500">*</span></label>
            <input type="text" id="provincia" name="provincia" value="{{ old('provincia', $professional->provincia) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('provincia')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $professional->activo) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                <span class="ml-2 text-sm text-gray-700">Activo</span>
            </label>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Actualizar</button>
            <a href="{{ route('professionals.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>

