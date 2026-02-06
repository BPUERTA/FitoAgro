<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Nuevo Cliente</h1>
                    <p class="text-sm text-gray-500">Registra un nuevo cliente en el sistema</p>
                </div>

                <a href="{{ route('clients.index') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                    Volver
                </a>
            </div>

            <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                @csrf

                @if(!auth()->user()->organization_id)
                    <div>
                        <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Organización
                        </label>
                        <select 
                            id="organization_id" 
                            name="organization_id"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        >
                            <option value="">Selecciona una organización</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre / Razón Social
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        placeholder="Ej: Juan García"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="domicilio" class="block text-sm font-medium text-gray-700 mb-1">
                        Domicilio
                    </label>
                    <input 
                        type="text" 
                        id="domicilio" 
                        name="domicilio" 
                        value="{{ old('domicilio') }}"
                        placeholder="Ej: Calle Principal"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('domicilio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="altura" class="block text-sm font-medium text-gray-700 mb-1">
                        Altura
                    </label>
                    <input 
                        type="text" 
                        id="altura" 
                        name="altura" 
                        value="{{ old('altura') }}"
                        placeholder="Ej: 123"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('altura')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="localidad" class="block text-sm font-medium text-gray-700 mb-1">
                        Localidad
                    </label>
                    <input 
                        type="text" 
                        id="localidad" 
                        name="localidad" 
                        value="{{ old('localidad') }}"
                        placeholder="Ej: Buenos Aires"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('localidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provincia" class="block text-sm font-medium text-gray-700 mb-1">
                        Provincia
                    </label>
                    <input 
                        type="text" 
                        id="provincia" 
                        name="provincia" 
                        value="{{ old('provincia') }}"
                        placeholder="Ej: Buenos Aires"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('provincia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">
                        País
                    </label>
                    <input 
                        type="text" 
                        id="pais" 
                        name="pais" 
                        value="{{ old('pais') }}"
                        placeholder="Ej: Argentina"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('pais')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cuit" class="block text-sm font-medium text-gray-700 mb-1">
                        CUIT/L
                    </label>
                    <input 
                        type="text" 
                        id="cuit" 
                        name="cuit" 
                        value="{{ old('cuit') }}"
                        required
                        placeholder="Ej: 20-12345678-9"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('cuit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        placeholder="Ej: cliente@email.com"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select 
                        id="status" 
                        name="status"
                        required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    >
                        <option value="">Selecciona un estado</option>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('clients.index') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Crear cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
