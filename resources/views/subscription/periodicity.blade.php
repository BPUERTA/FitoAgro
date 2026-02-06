@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl p-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-4">Selecciona la periodicidad de tu suscripción</h1>
        <form id="periodicityForm" method="POST" action="{{ route('subscription.subscribe', ['plan' => $plan->id]) }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plan seleccionado</label>
                <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-gray-800 font-semibold">
                    {{ $plan->name }}<br>
                    <span class="text-sm font-normal text-gray-600">Mensual: ${{ number_format($plan->monthly_price, 2) }} / mes</span><br>
                    <span class="text-sm font-normal text-gray-600">Anual: ${{ number_format($plan->yearly_price, 2) }} / año</span>
                </div>
            </div>
            <div>
                <label for="periodicity" class="block text-sm font-medium text-gray-700 mb-1">Periodicidad</label>
                <select id="periodicity" name="periodicity" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="monthly">Mensual - ${{ number_format($plan->monthly_price, 2) }} / mes</option>
                    <option value="yearly">Anual - ${{ number_format($plan->yearly_price, 2) }} / año</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ir al pago
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    (function(){
        const form = document.getElementById('periodicityForm');
        if (form) {
            form.addEventListener('submit', function(e){
                // Mostrar alerta temporal para depuración
                alert('Enviando formulario de periodicidad...');
                // Dejar que el formulario se envíe normalmente
            });
        }
    })();
</script>
@endsection
