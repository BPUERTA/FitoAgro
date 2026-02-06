<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Contratista') }}
            </h2>
            <a href="{{ route('contractors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('contractors.store') }}">
                        @csrf

                        <!-- Nombre / Razón Social -->
                        <div class="mb-4">
                            <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">
                                Nombre / Razón Social <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline capitalize @error('nombre') border-red-500 @enderror" 
                                   required>
                            @error('nombre')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CUIT -->
                        <div class="mb-4">
                            <label for="cuit" class="block text-gray-700 text-sm font-bold mb-2">
                                CUIT <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="cuit" id="cuit" value="{{ old('cuit') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cuit') border-red-500 @enderror" 
                                   placeholder="XX-XXXXXXXX-X"
                                   required>
                            @error('cuit')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-4">
                            <label for="activo" class="flex items-center">
                                <input type="checkbox" name="activo" id="activo" value="1" 
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Contratista activo</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Crear Contratista
                            </button>
                            <a href="{{ route('contractors.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
