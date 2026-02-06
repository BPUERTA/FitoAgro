<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Editar Cliente</h1>
                    <p class="text-sm text-gray-500">Actualiza los datos del cliente</p>
                </div>

                <a href="{{ route('clients.index') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                    Volver
                </a>
            </div>

            <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @if(!auth()->user()->organization_id)
                    <div>
                        <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Organización
                        </label>
                        <select 
                            id="organization_id" 
                            name="organization_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        >
                            <option value="">Selecciona una organización</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ $client->organization_id === $org->id ? 'selected' : '' }}>
                                    {{ $org->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                @include('clients._form', ['client' => $client])

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('clients.index') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

