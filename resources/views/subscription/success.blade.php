<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">¡Suscripción Exitosa!</h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Tu suscripción ha sido procesada correctamente. Ya puedes disfrutar de todos los beneficios de tu plan.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <h3 class="text-sm font-semibold text-green-800 mb-2">¿Qué sigue?</h3>
                <ul class="text-sm text-green-700 space-y-2 text-left max-w-md mx-auto">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Recibirás un email de confirmación en breve</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Tu plan se renovará automáticamente cada mes</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Puedes cancelar tu suscripción en cualquier momento</span>
                    </li>
                </ul>
            </div>

            <div class="flex gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Ir al Dashboard
                </a>
                <a href="{{ route('organizations.show', auth()->user()->organization) }}" class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    Ver Mi Organización
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
