<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Equipo') }}
            </h2>
            <a href="{{ route('teams.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Nombre Equipo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Servicio -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Servicio <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="fumigacion" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('fumigacion', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Fumigación</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="fertilizacion_liquida" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('fertilizacion_liquida', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Fertilización Líquida</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="fertilizacion_solida" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('fertilizacion_solida', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Fertilización Sólida</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="siembra" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('siembra', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Siembra</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="cosecha" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('cosecha', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Cosecha</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="servicio[]" value="laboreo" 
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           {{ is_array(old('servicio')) && in_array('laboreo', old('servicio')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Laboreo</span>
                                </label>
                            </div>
                            @error('servicio')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo Servicio -->
                        <div class="mb-4">
                            <label for="tipo_servicio" class="block text-gray-700 text-sm font-bold mb-2">
                                Tipo Servicio
                            </label>
                            <input type="text" name="tipo_servicio" id="tipo_servicio" value="{{ old('tipo_servicio') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tipo_servicio') border-red-500 @enderror">
                            @error('tipo_servicio')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dominio -->
                        <div class="mb-4">
                            <label for="dominio" class="block text-gray-700 text-sm font-bold mb-2">
                                Dominio
                            </label>
                            <input type="text" name="dominio" id="dominio" value="{{ old('dominio') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('dominio') border-red-500 @enderror">
                            @error('dominio')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matrícula -->
                        <div class="mb-4">
                            <label for="matricula" class="block text-gray-700 text-sm font-bold mb-2">
                                Matrícula
                            </label>
                            <input type="text" name="matricula" id="matricula" value="{{ old('matricula') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('matricula') border-red-500 @enderror">
                            @error('matricula')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-4">
                            <label for="activo" class="flex items-center">
                                <input type="checkbox" name="activo" id="activo" value="1" 
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Equipo activo</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Crear Equipo
                            </button>
                            <a href="{{ route('teams.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleContratistas() {
            const tipoPropiedad = document.getElementById('tipo_propiedad').value;
            const contratistasField = document.getElementById('contratistas_field');
            const contratistasInput = document.getElementById('contratistas');
            
            if (tipoPropiedad === 'contratista') {
                contratistasField.style.display = 'block';
                contratistasInput.required = true;
            } else {
                contratistasField.style.display = 'none';
                contratistasInput.required = false;
                contratistasInput.value = '';
            }
        }
        
        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleContratistas();
        });
    </script>
</x-app-layout>

