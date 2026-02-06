<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Planes de Suscripción</h1>
            <p class="mt-2 text-gray-600">Elige el plan perfecto para tu organización</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Plan actual -->
        @if($currentOrganization && $currentOrganization->plan)
            <div class="mb-8 bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Tu Plan Actual</h3>
                        <p class="text-2xl font-bold text-blue-700 mt-2">{{ $currentOrganization->plan->name }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            {{ number_format($currentOrganization->plan->monthly_price, 2) }} {{ $currentOrganization->plan->currency }}/mes
                        </p>
                        
                        @if($currentOrganization->trial_end_date && $currentOrganization->trial_end_date->isFuture())
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Periodo de prueba hasta {{ $currentOrganization->trial_end_date->format('d/m/Y') }}
                            </div>
                        @endif

                        @if($currentOrganization->paid_plan_end_date)
                            <p class="text-sm text-blue-600 mt-2">
                                Próxima renovación: {{ $currentOrganization->paid_plan_end_date->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    @if($currentOrganization->mercadopago_subscription_id)
                        <form action="{{ route('subscription.cancel') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar tu suscripción?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">
                                Cancelar Suscripción
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <!-- Planes disponibles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-xl shadow-sm border-2 {{ $currentOrganization && $currentOrganization->plan_id === $plan->id ? 'border-green-500' : 'border-gray-200' }} overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <!-- Badge de plan actual -->
                        @if($currentOrganization && $currentOrganization->plan_id === $plan->id)
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Plan Actual
                                </span>
                            </div>
                        @endif

                        <!-- Nombre del plan -->
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        
                        @if($plan->description)
                            <p class="mt-2 text-sm text-gray-600">{{ $plan->description }}</p>
                        @endif

                        <!-- Precio -->
                        <div class="mt-6">
                            <div class="flex items-baseline">
                                <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->monthly_price, 2) }}</span>
                                <span class="ml-2 text-gray-600">{{ $plan->currency }}/mes</span>
                            </div>
                            
                            @if($plan->yearly_price && $plan->annual_discount > 0)
                                <div class="mt-2 text-sm">
                                    <span class="text-green-600 font-semibold">
                                        {{ number_format($plan->yearly_price, 2) }} {{ $plan->currency }}/año
                                    </span>
                                    <span class="text-gray-500 ml-1">(Ahorra {{ number_format($plan->annual_discount, 0) }}%)</span>
                                </div>
                            @endif
                        </div>

                        <!-- Período de prueba -->
                        @if($plan->trial_days > 0)
                            <div class="mt-4 flex items-center text-sm text-blue-600">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $plan->trial_days }} días de prueba gratis
                            </div>
                        @endif

                        <!-- Características -->
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">
                                    {{ $plan->max_users ? $plan->max_users . ' usuario' . ($plan->max_users > 1 ? 's' : '') : 'Usuarios ilimitados' }}
                                </span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">
                                    {{ $plan->max_work_orders ? $plan->max_work_orders . ' orden' . ($plan->max_work_orders > 1 ? 'es' : '') : 'Órdenes ilimitadas' }}
                                </span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">
                                    {{ $plan->max_farms ? $plan->max_farms . ' explotación' . ($plan->max_farms > 1 ? 'es' : '') : 'Explotaciones ilimitadas' }}
                                </span>
                            </li>
                        </ul>

                        <!-- Botón de suscripción -->
                        <div class="mt-8">
                            @if($currentOrganization && $currentOrganization->plan_id === $plan->id)
                                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-600 rounded-lg font-semibold cursor-not-allowed">
                                    Plan Actual
                                </button>
                            @else
                                <form action="{{ route('subscription.subscribe', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                        @if($plan->trial_days > 0)
                                            Probar Gratis
                                        @else
                                            Suscribirse Ahora
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($plans->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">No hay planes disponibles en este momento</p>
            </div>
        @endif
    </div>
</x-app-layout>
