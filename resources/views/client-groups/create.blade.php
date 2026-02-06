<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Nuevo Grupo de Clientes</h1>
            <p class="text-sm text-gray-500">Definí los clientes y sus porcentajes.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client-groups.store') }}" method="POST" class="space-y-4">
            @csrf
            @include('client-groups._form', ['clients' => $clients])

            <div class="flex items-center gap-3">
                <a href="{{ route('client-groups.index') }}" class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Guardar</button>
            </div>
        </form>
    </div>
</x-app-layout>
