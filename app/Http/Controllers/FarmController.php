<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $farms = \App\Models\Farm::with('client')->orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $farms = \App\Models\Farm::with('client')
                ->where('organization_id', $user->organization_id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $farms = collect();
        }
        return view('farms.index', compact('farms'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Si es super admin, mostrar todos los clientes; si no, solo los de su organización
        if ($user->is_admin) {
            $clients = \App\Models\Client::orderBy('name')->get();
            $clientGroups = \App\Models\ClientGroup::orderBy('name')->get();
        } else {
            $clients = \App\Models\Client::where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get();
            $clientGroups = \App\Models\ClientGroup::where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get();
        }
        
        $alertsAllowed = $this->alertsAllowed($user);

        return view('farms.create', compact('clients', 'clientGroups', 'alertsAllowed'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'client_group_id' => ['nullable', 'exists:client_groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'has' => ['required', 'numeric', 'min:0'],
            'distancia_poblado' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:0,1,true,false'],
            'alert_ndvi' => ['nullable', 'boolean'],
            'alert_ndmi' => ['nullable', 'boolean'],
            'alert_nbr' => ['nullable', 'boolean'],
        ]);

        if (empty($data['client_id']) && empty($data['client_group_id'])) {
            return back()->withErrors([
                'client_id' => 'Debe seleccionar un cliente o un grupo de clientes.',
            ])->withInput();
        }
        
        // Verificar que el cliente pertenece a la organización del usuario
        $client = null;
        if (!empty($data['client_group_id'])) {
            $group = \App\Models\ClientGroup::with('members')->findOrFail($data['client_group_id']);
            if (!$user->is_admin && $group->organization_id != $user->organization_id) {
                abort(403, 'No puedes usar un grupo de otra organización');
            }

            if (!empty($data['client_id'])) {
                $client = \App\Models\Client::findOrFail($data['client_id']);
                if ($group->organization_id !== $client->organization_id) {
                    abort(403, 'El grupo seleccionado no pertenece a la misma organización.');
                }
            } else {
                $member = $group->members->sortByDesc('percentage')->first();
                if (!$member) {
                    return back()->withErrors([
                        'client_group_id' => 'El grupo seleccionado no tiene clientes.',
                    ])->withInput();
                }
                $client = \App\Models\Client::findOrFail($member->client_id);
                $data['client_id'] = $client->id;
            }
        } else {
            $client = \App\Models\Client::findOrFail($data['client_id']);
        }

        if (!$user->is_admin && $client->organization_id != $user->organization_id) {
            abort(403, 'No puedes crear una explotación para un cliente de otra organización');
        }
        
        $data['status'] = isset($data['status']) && ($data['status'] === '1' || $data['status'] === 1 || $data['status'] === true) ? 1 : 0;
        $data['organization_id'] = $client->organization_id;
        $data = $this->filterAlertSettings($data, $user);
        $data['has'] = (float) $data['has'];

        $farm = \App\Models\Farm::create($data);
        
        // Si es petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Explotación creada exitosamente',
                'farm' => $farm->load('client')
            ]);
        }
        
        return redirect()->route('farms.index')->with('success', 'Explotación creada exitosamente');
    }

    public function show($id)
    {
        $user = auth()->user();
        $farm = \App\Models\Farm::with(['lots', 'clientGroup.members.client'])->findOrFail($id);
        if (!$user->is_admin && $farm->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('farms.show', compact('farm'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $farm = \App\Models\Farm::with(['client', 'lots'])->findOrFail($id);
        if (!$user->is_admin && $farm->organization_id != $user->organization_id) {
            abort(403);
        }
        
        // Si es super admin, mostrar todos los clientes; si no, solo los de su organización
        if ($user->is_admin) {
            $clients = \App\Models\Client::orderBy('name')->get();
            $clientGroups = \App\Models\ClientGroup::orderBy('name')->get();
        } else {
            $clients = \App\Models\Client::where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get();
            $clientGroups = \App\Models\ClientGroup::where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get();
        }
        
        $alertsAllowed = $this->alertsAllowed($user);
        $orgFarms = collect();
        $orgFarmsPayload = [];
        if ($farm->organization_id) {
            $orgFarms = \App\Models\Farm::with('client')
                ->where('organization_id', $farm->organization_id)
                ->where('id', '!=', $farm->id)
                ->whereNotNull('polygon_coordinates')
                ->get(['id', 'name', 'polygon_coordinates', 'client_id']);
            $orgFarmsPayload = $orgFarms->map(function ($farm) {
                return [
                    'id' => $farm->id,
                    'name' => $farm->name,
                    'client_name' => $farm->client?->name,
                    'polygon' => $farm->polygon_coordinates ? json_decode($farm->polygon_coordinates, true) : null,
                ];
            })->values()->all();
        }

        return view('farms.edit', compact('farm', 'clients', 'clientGroups', 'alertsAllowed', 'orgFarmsPayload'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $farm = \App\Models\Farm::findOrFail($id);
        if (!$user->is_admin && $farm->organization_id != $user->organization_id) {
            abort(403);
        }
        
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'client_group_id' => ['nullable', 'exists:client_groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'has' => ['required', 'numeric', 'min:0'],
            'distancia_poblado' => ['nullable', 'numeric', 'min:0'],
            'status' => ['boolean'],
            'polygon_coordinates' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'alert_ndvi' => ['nullable', 'boolean'],
            'alert_ndmi' => ['nullable', 'boolean'],
            'alert_nbr' => ['nullable', 'boolean'],
        ]);

        if (empty($data['client_id']) && empty($data['client_group_id'])) {
            return back()->withErrors([
                'client_id' => 'Debe seleccionar un cliente o un grupo de clientes.',
            ])->withInput();
        }
        
        // Verificar que el cliente pertenece a la organización del usuario
        $client = null;
        if (!empty($data['client_group_id'])) {
            $group = \App\Models\ClientGroup::with('members')->findOrFail($data['client_group_id']);
            if (!$user->is_admin && $group->organization_id != $user->organization_id) {
                abort(403, 'No puedes usar un grupo de otra organización');
            }

            if (!empty($data['client_id'])) {
                $client = \App\Models\Client::findOrFail($data['client_id']);
                if ($group->organization_id !== $client->organization_id) {
                    abort(403, 'El grupo seleccionado no pertenece a la misma organización.');
                }
            } else {
                if ($farm->client_id && $farm->client && $farm->client->organization_id === $group->organization_id) {
                    $client = $farm->client;
                    $data['client_id'] = $client->id;
                } else {
                    $member = $group->members->sortByDesc('percentage')->first();
                    if (!$member) {
                        return back()->withErrors([
                            'client_group_id' => 'El grupo seleccionado no tiene clientes.',
                        ])->withInput();
                    }
                    $client = \App\Models\Client::findOrFail($member->client_id);
                    $data['client_id'] = $client->id;
                }
            }
        } else {
            $client = \App\Models\Client::findOrFail($data['client_id']);
        }

        if (!$user->is_admin && $client->organization_id != $user->organization_id) {
            abort(403, 'No puedes asignar una explotación a un cliente de otra organización');
        }
        
        $data['status'] = $request->has('status') ? true : false;
        $data = $this->filterAlertSettings($data, $user);
        $data['has'] = (float) $data['has'];

        $lotsTotal = (float) $farm->lots()->sum('has');
        if ($lotsTotal > $data['has']) {
            $excess = $lotsTotal - $data['has'];
            $lotsWithoutPolygon = $farm->lots()
                ->whereNull('polygon_coordinates')
                ->orderBy('id', 'desc')
                ->get();

            foreach ($lotsWithoutPolygon as $lot) {
                if ($excess <= 0) {
                    break;
                }
                $current = (float) $lot->has;
                if ($current <= 0) {
                    continue;
                }
                $reduce = min($current, $excess);
                $lot->has = max(0, $current - $reduce);
                $lot->save();
                $excess -= $reduce;
            }

            if ($excess > 0) {
                return back()->withErrors([
                    'has' => 'El total de hectáreas no puede ser menor a la suma de los lotes (' . number_format($lotsTotal, 2) . ').',
                ])->withInput();
            }
        }

        $farm->update($data);
        return redirect()->route('farms.edit', $farm->id)->with('success', 'Explotación actualizada exitosamente');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $farm = \App\Models\Farm::findOrFail($id);
        if (!$user->is_admin && $farm->organization_id != $user->organization_id) {
            abort(403);
        }
        $farm->delete();
        return redirect()->route('farms.index')->with('success', 'Explotación eliminada');
    }

    private function alertsAllowed($user): bool
    {
        if ($user->is_admin) {
            return true;
        }

        $planName = $user->organization?->plan?->name;
        if (!$planName) {
            return false;
        }

        return in_array(mb_strtolower($planName), ['profesional', 'professional', 'empresa', 'enterprise'], true);
    }

    private function filterAlertSettings(array $data, $user): array
    {
        if (!$this->alertsAllowed($user)) {
            unset($data['alert_ndvi'], $data['alert_ndmi'], $data['alert_nbr']);
            return $data;
        }

        $data['alert_ndvi'] = !empty($data['alert_ndvi']);
        $data['alert_ndmi'] = !empty($data['alert_ndmi']);
        $data['alert_nbr'] = !empty($data['alert_nbr']);

        return $data;
    }
}
