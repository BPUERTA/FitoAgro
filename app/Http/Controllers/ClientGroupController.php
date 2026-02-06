<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientGroup;
use App\Models\ClientGroupMember;
use App\Models\Farm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientGroupController extends Controller
{
    public function index(): View
    {
        $organizationId = Auth::user()->organization_id;

        $groups = ClientGroup::where('organization_id', $organizationId)
            ->with('members.client')
            ->orderBy('name')
            ->get();

        return view('client-groups.index', compact('groups'));
    }

    public function create(): View
    {
        $organizationId = Auth::user()->organization_id;
        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('client-groups.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $organizationId = Auth::user()->organization_id;

        $data = $this->validateGroup($request);

        $group = ClientGroup::create([
            'organization_id' => $organizationId,
            'name' => $data['name'],
            'note' => $data['note'] ?? null,
        ]);

        $this->syncMembers($group, $data['members']);

        return redirect()->route('client-groups.index')
            ->with('success', 'Grupo de clientes creado.');
    }

    public function edit(ClientGroup $clientGroup): View
    {
        $organizationId = Auth::user()->organization_id;
        if ($clientGroup->organization_id !== $organizationId) {
            abort(403);
        }

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $clientGroup->load('members');

        return view('client-groups.edit', compact('clientGroup', 'clients'));
    }

    public function update(Request $request, ClientGroup $clientGroup): RedirectResponse
    {
        $organizationId = Auth::user()->organization_id;
        if ($clientGroup->organization_id !== $organizationId) {
            abort(403);
        }

        $data = $this->validateGroup($request);

        $clientGroup->update([
            'name' => $data['name'],
            'note' => $data['note'] ?? null,
        ]);

        $this->syncMembers($clientGroup, $data['members']);

        return redirect()->route('client-groups.index')
            ->with('success', 'Grupo de clientes actualizado.');
    }

    public function destroy(ClientGroup $clientGroup): RedirectResponse
    {
        $organizationId = Auth::user()->organization_id;
        if ($clientGroup->organization_id !== $organizationId) {
            abort(403);
        }

        $farmsCount = Farm::where('client_group_id', $clientGroup->id)->count();
        if ($farmsCount > 0) {
            return redirect()->route('client-groups.index')
                ->with('error', 'No se puede eliminar: hay explotaciones vinculadas.');
        }

        $clientGroup->delete();

        return redirect()->route('client-groups.index')
            ->with('success', 'Grupo de clientes eliminado.');
    }

    private function validateGroup(Request $request): array
    {
        $organizationId = Auth::user()->organization_id;
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'members' => 'required|array|min:1',
            'members.*.client_id' => 'required|exists:clients,id',
            'members.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $clientIds = collect($data['members'])->pluck('client_id');
        if ($clientIds->count() !== $clientIds->unique()->count()) {
            return back()->withErrors(['members' => 'No puede repetir clientes en el mismo grupo.'])->throwResponse();
        }

        $sum = collect($data['members'])->sum(fn ($m) => (float) $m['percentage']);
        if (abs($sum - 100.0) > 0.01) {
            return back()->withErrors(['members' => 'La suma de porcentajes debe ser 100%.'])->throwResponse();
        }

        $clientIds = collect($data['members'])->pluck('client_id')->unique()->values();
        $count = Client::whereIn('id', $clientIds)
            ->where('organization_id', $organizationId)
            ->count();
        if ($count !== $clientIds->count()) {
            return back()->withErrors(['members' => 'Hay clientes que no pertenecen a tu organización.'])->throwResponse();
        }

        return $data;
    }

    private function syncMembers(ClientGroup $group, array $members): void
    {
        ClientGroupMember::where('client_group_id', $group->id)->delete();

        foreach ($members as $member) {
            ClientGroupMember::create([
                'client_group_id' => $group->id,
                'client_id' => $member['client_id'],
                'percentage' => $member['percentage'],
            ]);
        }
    }
}
