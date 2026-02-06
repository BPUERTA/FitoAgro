<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Configuración General
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 px-6 py-4">
                    <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md bg-gray-100 text-gray-900" data-tab="branding">Branding</button>
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50" data-tab="home">Home</button>
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50" data-tab="colors">Colores</button>
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50" data-tab="contact">Contacto</button>
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50" data-tab="subscriptions">Suscripciones</button>
                        <button type="button" class="tab-btn px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50" data-tab="system">Sistema</button>
                    </nav>
                </div>

                <div class="p-6 space-y-6">
                    <section class="tab-panel" id="tab-branding">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Título del sitio</label>
                                <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tagline</label>
                                <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="site_description" rows="2" class="mt-1 w-full rounded-lg border-gray-300">{{ $settings['site_description'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Logo principal</label>
                                <input type="file" name="logo" class="mt-1 w-full text-sm">
                                @if(!empty($settings['logo']))
                                    <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" class="mt-3 h-12">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ancho del logo (px)</label>
                                <input type="number" name="logo_width" value="{{ $settings['logo_width'] ?? '' }}" min="40" max="400" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Ej: 140">
                                <p class="text-xs text-gray-500 mt-1">Se aplica al logo del menú.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Logo alternativo</label>
                                <input type="file" name="logo_alt" class="mt-1 w-full text-sm">
                                @if(!empty($settings['logo_alt']))
                                    <img src="{{ asset('storage/'.$settings['logo_alt']) }}" alt="Logo alternativo" class="mt-3 h-12">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Favicon</label>
                                <input type="file" name="favicon" class="mt-1 w-full text-sm">
                                @if(!empty($settings['favicon']))
                                    <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" class="mt-3 h-8 w-8">
                                @endif
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imagen de login</label>
                                <input type="file" name="login_image" class="mt-1 w-full text-sm">
                                @if(!empty($settings['login_image']))
                                    <img src="{{ asset('storage/'.$settings['login_image']) }}" alt="Login" class="mt-3 rounded" style="height: 96px; width: auto; max-width: 100%; object-fit: cover;">
                                    <div class="mt-2 flex items-center gap-2">
                                        <input type="checkbox" id="remove_login_image" name="remove_login_image" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <label for="remove_login_image" class="text-xs text-red-600">Eliminar</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <section class="tab-panel hidden" id="tab-home">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Titular principal</label>
                                <input type="text" name="home_headline" value="{{ $settings['home_headline'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Subtítulo</label>
                                <textarea name="home_subheadline" rows="2" class="mt-1 w-full rounded-lg border-gray-300">{{ $settings['home_subheadline'] ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imágenes de home (3)</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                    @foreach([1,2,3] as $idx)
                                        @php $key = 'home_image_' . $idx; @endphp
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Imagen {{ $idx }}</label>
                                            <input type="file" name="home_image_{{ $idx }}" class="w-full text-sm">
                                            @if(!empty($settings[$key]))
                                                <img src="{{ asset('storage/'.$settings[$key]) }}" alt="Home {{ $idx }}" class="mt-2 h-20 rounded">
                                                <div class="mt-2 flex items-center gap-2">
                                                    <input type="checkbox" id="remove_home_image_{{ $idx }}" name="remove_home_image_{{ $idx }}" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    <label for="remove_home_image_{{ $idx }}" class="text-xs text-red-600">Eliminar</label>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="tab-panel hidden" id="tab-colors">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color primario</label>
                                <input type="text" name="brand_primary" value="{{ $settings['brand_primary'] ?? '' }}" placeholder="#16a34a" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color secundario</label>
                                <input type="text" name="brand_secondary" value="{{ $settings['brand_secondary'] ?? '' }}" placeholder="#0f7a37" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                        </div>
                    </section>

                    <section class="tab-panel hidden" id="tab-contact">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email de contacto</label>
                                <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                        </div>
                    </section>

                    <section class="tab-panel hidden" id="tab-subscriptions">
                        <div class="space-y-6">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-800">Registro de usuarios y organizaciones</h4>
                                <p class="text-xs text-gray-500 mt-1">Controla si se permiten nuevas altas desde el login/registro.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="registrations_locked" name="registrations_locked" value="1" class="mt-1 h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    {{ ($settings['registrations_locked'] ?? '0') === '1' ? 'checked' : '' }}>
                                <label for="registrations_locked" class="text-sm font-medium text-gray-700">
                                    Bloquear nuevos registros (usuarios y organizaciones)
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">Si está activado, el botón de registro se oculta en el login y se bloquea el acceso a /register.</p>
                        </div>
                    </section>

                    <section class="tab-panel hidden" id="tab-system">
                        <div class="mb-4 flex items-center gap-3">
                            <input type="checkbox" id="maintenance_enabled" name="maintenance_enabled" value="1" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                {{ ($settings['maintenance_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <label for="maintenance_enabled" class="text-sm font-medium text-gray-700">
                                Activar mantenimiento (solo superadmin)
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mensaje de mantenimiento</label>
                            <textarea name="maintenance_message" rows="2" class="mt-1 w-full rounded-lg border-gray-300">{{ $settings['maintenance_message'] ?? '' }}</textarea>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Imagen de mantenimiento</label>
                            <input type="file" name="maintenance_image" class="mt-1 w-full text-sm">
                            @if(!empty($settings['maintenance_image']))
                                <img src="{{ asset('storage/'.$settings['maintenance_image']) }}" alt="Mantenimiento" class="mt-2 h-20 rounded">
                                <div class="mt-2 flex items-center gap-2">
                                    <input type="checkbox" id="remove_maintenance_image" name="remove_maintenance_image" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="remove_maintenance_image" class="text-xs text-red-600">Eliminar imagen actual</label>
                                </div>
                            @endif
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Logo de mantenimiento</label>
                            <input type="file" name="maintenance_logo" class="mt-1 w-full text-sm">
                            @if(!empty($settings['maintenance_logo']))
                                <img src="{{ asset('storage/'.$settings['maintenance_logo']) }}" alt="Logo mantenimiento" class="mt-2 h-16">
                                <div class="mt-2 flex items-center gap-2">
                                    <input type="checkbox" id="remove_maintenance_logo" name="remove_maintenance_logo" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="remove_maintenance_logo" class="text-xs text-red-600">Eliminar logo actual</label>
                                </div>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha/hora de activación (opcional)</label>
                                <input type="datetime-local" name="maintenance_start_at" value="{{ isset($settings['maintenance_start_at']) ? \Illuminate\Support\Carbon::parse($settings['maintenance_start_at'])->format('Y-m-d\\TH:i') : '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha/hora de desactivación (opcional)</label>
                                <input type="datetime-local" name="maintenance_end_at" value="{{ isset($settings['maintenance_end_at']) ? \Illuminate\Support\Carbon::parse($settings['maintenance_end_at'])->format('Y-m-d\\TH:i') : '' }}" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">Backups</h4>
                                    <p class="text-xs text-gray-500">Descargar copia de la base de datos en .sql</p>
                                </div>
                                <a href="{{ route('settings.backup') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                                    Descargar backup
                                </a>
                            </div>
                        </div>

                    </section>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">
                    <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const tabs = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');

        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                tabs.forEach(b => b.classList.remove('bg-gray-100', 'text-gray-900'));
                tabs.forEach(b => b.classList.add('text-gray-600'));
                btn.classList.add('bg-gray-100', 'text-gray-900');

                panels.forEach(panel => panel.classList.add('hidden'));
                document.getElementById('tab-' + target).classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>
