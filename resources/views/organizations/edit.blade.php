<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Editar Organización</h1>
                    <p class="text-sm text-gray-500">Actualiza los datos de la organización</p>
                </div>

                <a href="{{ route('organizations.index') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                    Volver
                </a>
            </div>

            <form action="{{ route('organizations.update', $organization) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $organization->name) }}"
                        required
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
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $organization->plan_id) == $plan->id ? 'selected' : '' }}>
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
                        <option value="active" {{ old('status', $organization->status) === 'active' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="suspended" {{ old('status', $organization->status) === 'suspended' ? 'selected' : '' }}>
                            Suspendido
                        </option>
                        <option value="inactive" {{ old('status', $organization->status) === 'inactive' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(auth()->user()->is_admin)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Gestión de Fechas (Solo Superadmin)</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-900 mb-3">Período de Prueba</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="trial_start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Inicio
                                        </label>
                                        <input 
                                            type="date" 
                                            id="trial_start_date" 
                                            name="trial_start_date" 
                                            value="{{ old('trial_start_date', $organization->trial_start_date?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        />
                                        @error('trial_start_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="trial_end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Finalización
                                        </label>
                                        <input 
                                            type="date" 
                                            id="trial_end_date" 
                                            name="trial_end_date" 
                                            value="{{ old('trial_end_date', $organization->trial_end_date?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        />
                                        @error('trial_end_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-green-900 mb-3">Plan Pago</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="paid_plan_start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Inicio
                                        </label>
                                        <input 
                                            type="date" 
                                            id="paid_plan_start_date" 
                                            name="paid_plan_start_date" 
                                            value="{{ old('paid_plan_start_date', $organization->paid_plan_start_date?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        />
                                        @error('paid_plan_start_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="paid_plan_end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Finalización
                                        </label>
                                        <input 
                                            type="date" 
                                            id="paid_plan_end_date" 
                                            name="paid_plan_end_date" 
                                            value="{{ old('paid_plan_end_date', $organization->paid_plan_end_date?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        />
                                        @error('paid_plan_end_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('organizations.index') }}"
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

