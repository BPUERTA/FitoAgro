<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Nuevo Plan
            </h2>
            <a href="{{ route('plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('plans.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                    Nombre del Plan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                                    Descripción
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Precio Mensual -->
                            <div>
                                <label for="monthly_price" class="block text-gray-700 text-sm font-bold mb-2">
                                    Precio Mensual
                                </label>
                                <input type="number" name="monthly_price" id="monthly_price" value="{{ old('monthly_price') }}" step="0.01" min="0"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('monthly_price') border-red-500 @enderror"
                                       oninput="calculateYearlyPrice()">
                                <p class="text-xs text-gray-500 mt-1">Deja en blanco si no aplica</p>
                                @error('monthly_price')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descuento Anual -->
                            <div>
                                <label for="annual_discount" class="block text-gray-700 text-sm font-bold mb-2">
                                    Descuento por Pago Anual (%)
                                </label>
                                <input type="number" name="annual_discount" id="annual_discount" value="{{ old('annual_discount') }}" step="0.01" min="0" max="100"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('annual_discount') border-red-500 @enderror"
                                       oninput="calculateYearlyPrice()">
                                <p class="text-xs text-gray-500 mt-1">Porcentaje de descuento aplicado al pago anual</p>
                                @error('annual_discount')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Precio Anual -->
                            <div>
                                <label for="yearly_price" class="block text-gray-700 text-sm font-bold mb-2">
                                    Precio Anual
                                </label>
                                <input type="number" name="yearly_price" id="yearly_price" value="{{ old('yearly_price') }}" step="0.01" min="0"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('yearly_price') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Se calcula automáticamente o puedes editarlo manualmente</p>
                                @error('yearly_price')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Días de Prueba -->
                            <div>
                                <label for="trial_days" class="block text-gray-700 text-sm font-bold mb-2">
                                    Días de Prueba Gratuita
                                </label>
                                <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days') }}" min="0"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('trial_days') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Cantidad de días de prueba gratuita (0 = sin prueba)</p>
                                @error('trial_days')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Moneda -->
                            <div class="md:col-span-2">
                                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">
                                    Moneda <span class="text-red-500">*</span>
                                </label>
                                <select name="currency" id="currency" required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('currency') border-red-500 @enderror">
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>Dólares Estadounidenses (USD)</option>
                                    <option value="ARS" {{ old('currency') == 'ARS' ? 'selected' : '' }}>Pesos Argentinos (ARS)</option>
                                </select>
                                @error('currency')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Límites -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-700 mb-3 mt-4">Límites del Plan</h3>
                                <p class="text-sm text-gray-600 mb-4">Deja en blanco para ilimitado</p>
                            </div>

                            <!-- Máximo Usuarios -->
                            <div>
                                <label for="max_users" class="block text-gray-700 text-sm font-bold mb-2">
                                    Máximo de Usuarios
                                </label>
                                <input type="number" name="max_users" id="max_users" value="{{ old('max_users') }}" min="1"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_users') border-red-500 @enderror">
                                @error('max_users')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Máximo Órdenes de Trabajo -->
                            <div>
                                <label for="max_work_orders" class="block text-gray-700 text-sm font-bold mb-2">
                                    Máximo de Órdenes de Trabajo
                                </label>
                                <input type="number" name="max_work_orders" id="max_work_orders" value="{{ old('max_work_orders') }}" min="1"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_work_orders') border-red-500 @enderror">
                                @error('max_work_orders')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Máximo Explotaciones -->
                            <div>
                                <label for="max_farms" class="block text-gray-700 text-sm font-bold mb-2">
                                    Máximo de Explotaciones
                                </label>
                                <input type="number" name="max_farms" id="max_farms" value="{{ old('max_farms') }}" min="1"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_farms') border-red-500 @enderror">
                                @error('max_farms')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Máximo de Clientes -->
                            <div>
                                <label for="max_clients" class="block text-gray-700 text-sm font-bold mb-2">
                                    Máximo de Clientes
                                </label>
                                <input type="number" name="max_clients" id="max_clients" value="{{ old('max_clients') }}" min="1"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_clients') border-red-500 @enderror">
                                @error('max_clients')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Permitir crear usuarios para clientes -->
                            <div class="md:col-span-2 mt-2">
                                <label for="clients_can_create_users" class="flex items-center">
                                    <input type="checkbox" name="clients_can_create_users" id="clients_can_create_users" value="1" {{ old('clients_can_create_users') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Permitir que los clientes creen usuarios</span>
                                </label>
                            </div>

                            <!-- Estado -->
                            <div class="md:col-span-2 mt-4">
                                <label for="status" class="flex items-center">
                                    <input type="checkbox" name="status" id="status" value="1" checked
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Plan activo</span>
                                </label>
                            </div>

                            <!-- Publicado -->
                            <div class="md:col-span-2">
                                <label for="published" class="flex items-center">
                                    <input type="checkbox" name="published" id="published" value="1" {{ old('published') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Publicar en el panel de inicio</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Crear Plan
                            </button>
                            <a href="{{ route('plans.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancelar
                            </a>
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
