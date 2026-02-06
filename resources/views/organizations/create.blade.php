<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Nueva Organización</h1>
                    <p class="text-sm text-gray-500">Registra una nueva empresa asesora en el sistema</p>
                </div>

                <a href="{{ route('organizations.index') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                    Volver
                </a>
            </div>

            <form action="{{ route('organizations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        placeholder="Ej: Consultoría Agrícola XYZ"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Plan de Suscripción
                    </label>
                    <select 
                        id="plan_id" 
                        name="plan_id"
                        required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    >
                        <option value="">Selecciona un plan</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - ${{ number_format($plan->monthly_price, 2) }} {{ $plan->currency }}/mes
                        </option>
                        @endforeach
                    </select>
                    @error('plan_id')
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
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>
                            Suspendido
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
                    <a href="{{ route('organizations.index') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Crear organización
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
