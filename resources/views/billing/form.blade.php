<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Datos de Facturación</h1>
                <p class="text-sm text-gray-500">Completa los datos fiscales de tu organización</p>
            </div>

            <!-- Panel de datos de registro no editables -->
            <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Datos de registro</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500">Nombre</span>
                        <span class="block font-medium text-gray-700">{{ $user->nombre }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Apellido</span>
                        <span class="block font-medium text-gray-700">{{ $user->apellido }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Email</span>
                        <span class="block font-medium text-gray-700">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Organización</span>
                        <span class="block font-medium text-gray-700">{{ $organization->name }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('billing.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                    <input type="text" id="billing_name" name="billing_name" value="{{ old('billing_name', $organization->billing_name) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Ej: Mi Empresa S.A." />
                </div>
                <div>
                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" id="billing_address" name="billing_address" value="{{ old('billing_address', $organization->billing_address) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Ej: Calle 123, Ciudad" />
                </div>
                <div>
                    <label for="billing_cuit" class="block text-sm font-medium text-gray-700 mb-1">CUIT</label>
                    <input type="text" id="billing_cuit" name="billing_cuit" value="{{ old('billing_cuit', $organization->billing_cuit) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Ej: 30-12345678-9" />
                </div>
                <div>
                    <label for="billing_iva" class="block text-sm font-medium text-gray-700 mb-1">Condición IVA</label>
                    <select id="billing_iva" name="billing_iva" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Selecciona una opción</option>
                        @foreach($ivaOptions as $iva)
                            <option value="{{ $iva }}" {{ old('billing_iva', $organization->billing_iva) == $iva ? 'selected' : '' }}>{{ $iva }}</option>
                        @endforeach
                    </select>
                </div>
                @php
                    $planInicial = request('plan_id') ?? old('plan_id', $organization->plan_id);
                @endphp
                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                    <select id="plan_id" name="plan_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Selecciona un plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ (old('plan_id', $planInicial) == $plan->id) ? 'selected' : '' }}>
                                {{ $plan->name }} - ${{ number_format($plan->monthly_price, 2) }} / mes | ${{ number_format($plan->yearly_price, 2) }} / año
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Guardar y continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
