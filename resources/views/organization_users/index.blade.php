@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Usuarios de la Organización</h1>
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Usuarios de Organización</h2>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Rol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizationUsers as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">Admin Org: {{ $user->is_org_admin ? 'Sí' : 'No' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Sin usuarios</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        <h2 class="text-lg font-semibold mb-2">Usuarios de Clientes</h2>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Cliente</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientUsers as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->client_id }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Sin usuarios de clientes</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
