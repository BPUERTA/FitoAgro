<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Plan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('plans.update', $plan) }}">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Plan</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Precio y Ciclo -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="monthly_price" class="block text-sm font-medium text-gray-700">Precio Mensual</label>
                                <input type="number" name="monthly_price" id="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       oninput="calculateYearlyPrice()">
                                <p class="mt-1 text-xs text-gray-500">Deja en blanco si no aplica</p>
                                @error('monthly_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="annual_discount" class="block text-sm font-medium text-gray-700">Descuento por Pago Anual (%)</label>
                                <input type="number" name="annual_discount" id="annual_discount" value="{{ old('annual_discount', $plan->annual_discount) }}" step="0.01" min="0" max="100"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       oninput="calculateYearlyPrice()">
                                <p class="mt-1 text-xs text-gray-500">Porcentaje de descuento aplicado al pago anual</p>
                                @error('annual_discount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="yearly_price" class="block text-sm font-medium text-gray-700">Precio Anual</label>
                                <input type="number" name="yearly_price" id="yearly_price" value="{{ old('yearly_price', $plan->yearly_price) }}" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Se calcula automáticamente o puedes editarlo manualmente</p>
                                @error('yearly_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="trial_days" class="block text-sm font-medium text-gray-700">Días de Prueba Gratuita</label>
                                <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days', $plan->trial_days) }}" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Cantidad de días de prueba gratuita (0 = sin prueba)</p>
                                @error('trial_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Moneda -->
                        <div class="mb-4">
                            <label for="currency" class="block text-sm font-medium text-gray-700">Moneda</label>
                            <select name="currency" id="currency" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="USD" {{ old('currency', $plan->currency) == 'USD' ? 'selected' : '' }}>Dólares Estadounidenses (USD)</option>
                                <option value="ARS" {{ old('currency', $plan->currency) == 'ARS' ? 'selected' : '' }}>Pesos Argentinos (ARS)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Límites -->
                        <div class="mb-4 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Límites del Plan</h3>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="max_users" class="block text-sm font-medium text-gray-700">Máximo Usuarios</label>
                                    <input type="number" name="max_users" id="max_users" value="{{ old('max_users', $plan->max_users) }}" min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           required>
                                    @error('max_users')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="max_work_orders" class="block text-sm font-medium text-gray-700">Máximo Órdenes de Trabajo</label>
                                    <input type="number" name="max_work_orders" id="max_work_orders" value="{{ old('max_work_orders', $plan->max_work_orders) }}" min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('max_work_orders')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="max_farms" class="block text-sm font-medium text-gray-700">Máximo Explotaciones</label>

                                    <input type="number" name="max_farms" id="max_farms" value="{{ old('max_farms', $plan->max_farms) }}" min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('max_farms')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Máximo de Clientes -->
                                <div>
                                    <label for="max_clients" class="block text-sm font-medium text-gray-700">Máximo de Clientes</label>
                                    <input type="number" name="max_clients" id="max_clients" value="{{ old('max_clients', $plan->max_clients) }}" min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('max_clients')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Permitir crear usuarios para clientes -->
                                <div class="col-span-2 mt-2">
                                    <label for="clients_can_create_users" class="flex items-center">
                                        <input type="checkbox" name="clients_can_create_users" id="clients_can_create_users" value="1" {{ old('clients_can_create_users', $plan->clients_can_create_users) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Permitir que los clientes creen usuarios</span>
                                    </label>
                                </div>
                            </div>

                        <!-- Estado -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="status" value="1" {{ old('status', $plan->status) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Plan Activo</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Publicado -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="published" value="1" {{ old('published', $plan->published) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Publicar en el panel de inicio</span>
                            </label>
                            @error('published')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('plans.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Actualizar Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateYearlyPrice() {
            const monthlyPrice = parseFloat(document.getElementById('monthly_price').value) || 0;
            const discount = parseFloat(document.getElementById('annual_discount').value) || 0;
            
            if (monthlyPrice > 0) {
                const yearlyBeforeDiscount = monthlyPrice * 12;
                const yearlyPrice = yearlyBeforeDiscount * (1 - discount / 100);
                document.getElementById('yearly_price').value = yearlyPrice.toFixed(2);
            } else {
                document.getElementById('yearly_price').value = '';
            }
        }
    </script>
</x-app-layout>
