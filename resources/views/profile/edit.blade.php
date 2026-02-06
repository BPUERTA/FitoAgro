<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if(!auth()->user()->is_admin && auth()->user()->organization)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Información de la Organización
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Información sobre tu organización y plan de suscripción.
                                </p>
                            </header>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre de la Organización</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ auth()->user()->organization->name }}</div>
                                </div>

                                @if(auth()->user()->organization->plan)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Plan Actual</label>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ auth()->user()->organization->plan->name }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <div class="mt-1">
                                        @if(auth()->user()->organization->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @elseif(auth()->user()->organization->status === 'inactive')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ ucfirst(auth()->user()->organization->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(auth()->user()->organization->trial_start_date && auth()->user()->organization->trial_end_date)
                                    <div class="border-t pt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Período de Prueba</label>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Inicio:</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ auth()->user()->organization->trial_start_date->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Fin:</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ auth()->user()->organization->trial_end_date->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(auth()->user()->organization->paid_plan_start_date && auth()->user()->organization->paid_plan_end_date)
                                    <div class="border-t pt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan Pago</label>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Inicio:</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ auth()->user()->organization->paid_plan_start_date->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Fin:</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ auth()->user()->organization->paid_plan_end_date->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="border-t pt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Miembros del Equipo</label>
                                    <div class="text-sm text-gray-900">
                                        {{ auth()->user()->organization->users->count() }} miembro(s)
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
