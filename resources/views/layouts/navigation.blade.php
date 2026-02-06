<nav x-data="{ open: false }" class="bg-white border-b border-green-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                @php
                    $clientsMenuActive = request()->routeIs('clients.*') || request()->routeIs('farms.*');
                    $contractorsMenuActive = request()->routeIs('contractors.*') || request()->routeIs('teams.*');
                    $clientsMenuClasses = $clientsMenuActive
                        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-600 transition duration-150 ease-in-out'
                        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
                    $contractorsMenuClasses = $contractorsMenuActive
                        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-600 transition duration-150 ease-in-out'
                        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
                    $showClientsMenu = Route::has('clients.index') || Route::has('farms.index');
                    $showContractorsMenu = Route::has('contractors.index') || Route::has('teams.index');
                @endphp

                <div class="hidden items-center space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if ($showClientsMenu)
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="{{ $clientsMenuClasses }}">
                                    {{ __('Productores') }}
                                    <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if (Route::has('clients.index'))
                                    <x-dropdown-link :href="route('clients.index')">
                                        {{ __('Clientes') }}
                                    </x-dropdown-link>
                                @endif
                                @if (Route::has('farms.index'))
                                    <x-dropdown-link :href="route('farms.index')">
                                        {{ __('Explotaciones') }}
                                    </x-dropdown-link>
                                @endif
                                @if (Route::has('client-groups.index'))
                                    <x-dropdown-link :href="route('client-groups.index')">
                                        {{ __('Grupos de Clientes') }}
                                    </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    @endif

                    @if ($showContractorsMenu)
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="{{ $contractorsMenuClasses }}">
                                    {{ __('Contratistas') }}
                                    <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if (Route::has('contractors.index'))
                                    <x-dropdown-link :href="route('contractors.index')">
                                        {{ __('Contratistas') }}
                                    </x-dropdown-link>
                                @endif
                                @if (Route::has('teams.index'))
                                    <x-dropdown-link :href="route('teams.index')">
                                        {{ __('Equipos') }}
                                    </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    @endif

                    @if (Route::has('professionals.index'))
                        <x-nav-link :href="route('professionals.index')" :active="request()->routeIs('professionals.*')">
                            {{ __('Profesionales') }}
                        </x-nav-link>
                    @endif

                    @if (Route::has('users.index'))
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Usuarios') }}
                        </x-nav-link>
                    @endif

                    @if (Route::has('products.index'))
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                            {{ __('Productos') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->is_admin && Route::has('organizations.index'))
                        <x-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                            {{ __('Organizaciones') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->is_admin && Route::has('plans.index'))
                        <x-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">
                            Planes
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->is_admin && Route::has('settings.index'))
                        <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                            Configuración
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->is_admin && Route::has('user-session-logs.index'))
                        <x-nav-link :href="route('user-session-logs.index')" :active="request()->routeIs('user-session-logs.*')">
                            Sesiones
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto de perfil" class="w-8 h-8 rounded-full object-cover mr-2">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        @if(!Auth::user()->is_admin && Auth::user()->organization)
                            <x-dropdown-link :href="route('organizations.show', Auth::user()->organization)">
                                Mi Organización
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('subscription.index')">
                                Cambiar Plan
                            </x-dropdown-link>
                        @endif
                        
                        <!-- Plan de Suscripción -->
                        <div class="px-4 py-2 text-xs text-gray-500 border-t border-gray-100">
                            Plan: <span class="font-semibold text-gray-700">{{ Auth::user()->organization->plan->name ?? 'Sin plan' }}</span>
                        </div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if ($showClientsMenu)
                <div class="px-4 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ __('Clientes') }}
                </div>
            @if (Route::has('clients.index'))
                <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')" class="ps-6">
                    {{ __('Clientes') }}
                </x-responsive-nav-link>
            @endif
            @if (Route::has('farms.index'))
                <x-responsive-nav-link :href="route('farms.index')" :active="request()->routeIs('farms.*')" class="ps-6">
                    {{ __('Explotaciones') }}
                </x-responsive-nav-link>
            @endif
            @if (Route::has('client-groups.index'))
                <x-responsive-nav-link :href="route('client-groups.index')" :active="request()->routeIs('client-groups.*')" class="ps-6">
                    {{ __('Grupos de Clientes') }}
                </x-responsive-nav-link>
            @endif
            @endif

            @if (Route::has('professionals.index'))
                <x-responsive-nav-link :href="route('professionals.index')" :active="request()->routeIs('professionals.*')">
                    {{ __('Profesionales') }}
                </x-responsive-nav-link>
            @endif

            @if ($showContractorsMenu)
                <div class="px-4 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ __('Contratistas') }}
                </div>
                @if (Route::has('contractors.index'))
                    <x-responsive-nav-link :href="route('contractors.index')" :active="request()->routeIs('contractors.*')" class="ps-6">
                        {{ __('Contratistas') }}
                    </x-responsive-nav-link>
                @endif
                @if (Route::has('teams.index'))
                    <x-responsive-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')" class="ps-6">
                        {{ __('Equipos') }}
                    </x-responsive-nav-link>
                @endif
            @endif
            @if (Route::has('users.index'))
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
            @endif

            @if (Route::has('products.index'))
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    {{ __('Productos') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->is_admin && Route::has('organizations.index'))
                <x-responsive-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                    {{ __('Organizaciones') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->is_admin && Route::has('plans.index'))
                <x-responsive-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">
                    Planes
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->is_admin && Route::has('settings.index'))
                <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                    Configuración
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->is_admin && Route::has('user-session-logs.index'))
                <x-responsive-nav-link :href="route('user-session-logs.index')" :active="request()->routeIs('user-session-logs.*')">
                    Sesiones
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                @if(!Auth::user()->is_admin && Auth::user()->organization)
                    <x-responsive-nav-link :href="route('organizations.show', Auth::user()->organization)">
                        Mi Organización
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
