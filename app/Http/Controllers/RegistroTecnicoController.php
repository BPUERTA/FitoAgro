<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Farm;
use App\Models\FarmLot;
use App\Models\RegistroTecnico;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RegistroTecnicoController extends Controller
{
    public function index(Request $request)
    {
        if (!$this->tableReady()) {
            $organizationId = Auth::user()->organization_id;
            $clients = Client::where('organization_id', $organizationId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
            $statuses = $this->statusLabels();
            $registroTecnicos = new LengthAwarePaginator([], 0, 15);
            $tableMissing = true;

            return view('registro-tecnicos.index', compact('registroTecnicos', 'clients', 'statuses', 'tableMissing'));
        }

        $organizationId = Auth::user()->organization_id;

        $registroTecnicos = RegistroTecnico::forOrganization($organizationId)
            ->with(['client', 'farm', 'lot'])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->client_id, fn ($query, $clientId) => $query->where('client_id', $clientId))
            ->latest()
            ->paginate(15);

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $statuses = $this->statusLabels();

        $tableMissing = false;

        return view('registro-tecnicos.index', compact('registroTecnicos', 'clients', 'statuses', 'tableMissing'));
    }

    public function create()
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $organizationId = Auth::user()->organization_id;

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $clientGroups = \App\Models\ClientGroup::where('organization_id', $organizationId)
            ->orderBy('name')
            ->get();

        $farms = Farm::with('lots')
            ->where('organization_id', $organizationId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $objectiveOptions = $this->objectiveLabels();

        $farmsMap = Farm::with('client', 'lots')
            ->where('organization_id', $organizationId)
            ->where('status', true)
            ->whereNotNull('polygon_coordinates')
            ->get();

        $farmsMapPayload = $farmsMap->map(function ($farm) {
            $lots = $farm->lots
                ->whereNotNull('polygon_coordinates')
                ->map(function ($lot) {
                    return [
                        'id' => $lot->id,
                        'name' => $lot->name,
                        'polygon' => $lot->polygon_coordinates ? json_decode($lot->polygon_coordinates, true) : null,
                    ];
                })
                ->values();
            return [
                'id' => $farm->id,
                'name' => $farm->name,
                'client_id' => $farm->client_id,
                'client_group_id' => $farm->client_group_id,
                'localidad' => $farm->client?->localidad,
                'polygon' => $farm->polygon_coordinates ? json_decode($farm->polygon_coordinates, true) : null,
                'lat' => $farm->lat,
                'lng' => $farm->lng,
                'lots' => $lots,
            ];
        })->values();

        return view('registro-tecnicos.create', compact('clients', 'clientGroups', 'farms', 'objectiveOptions', 'farmsMapPayload'));
    }

    public function store(Request $request)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $validated = $this->validateRegistro($request);

        $organizationId = Auth::user()->organization_id;
        if (!$organizationId) {
            abort(403, 'No tienes una organización activa.');
        }

        $clientId = $validated['client_id'] ?? null;
        $farmId = $validated['farm_id'] ?? null;
        $lotId = $validated['lot_id'] ?? null;

        $client = null;
        if ($clientId) {
            $client = Client::findOrFail($clientId);
            if ($client->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar un cliente de otra organización.');
            }
        }

        $farm = null;
        if ($farmId) {
            $farm = Farm::findOrFail($farmId);
            if ($farm->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar una explotación de otra organización.');
            }
            if ($client && $farm->client_id !== $client->id) {
                abort(403, 'La explotación seleccionada no pertenece al cliente indicado.');
            }
        }

        $lot = null;
        if ($lotId) {
            $lot = FarmLot::findOrFail($lotId);
            $farm = $farm ?: $lot->farm;
            if (!$farm || $farm->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar un lote de otra organización.');
            }
            if ($farmId && $lot->farm_id !== $farmId) {
                $farmId = $lot->farm_id;
            }
            if ($client && $farm->client_id !== $client->id) {
                $clientId = $farm->client_id;
                $client = $farm->client;
            }
            $farmId = $farm->id;
            $clientId = $farm->client_id;
        }

        if (!$farm && !empty($validated['lat']) && !empty($validated['lng'])) {
            [$farmResolved, $lotResolved] = $this->resolveFarmLotByPoint($organizationId, (float) $validated['lat'], (float) $validated['lng']);
            if ($farmResolved) {
                $farm = $farmResolved;
                $farmId = $farmResolved->id;
                $clientId = $farmResolved->client_id;
                if ($lotResolved) {
                    $lotId = $lotResolved->id;
                }
            }
        }

        $photos = $this->storePhotos($request->file('photos'));
        $audioPath = $this->storeAudio($request->file('audio'));

        $user = Auth::user();

        $registro = RegistroTecnico::create([
            'organization_id' => $organizationId,
            'client_id' => $clientId,
            'farm_id' => $farmId,
            'lot_id' => $lotId,
            'status' => RegistroTecnico::STATUS_ABIERTO,
            'objectives' => $validated['objectives'] ?? [],
            'note' => $validated['note'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'photos' => $photos,
            'audio_path' => $audioPath,
            'created_by' => $this->nickname($user),
        ]);

        return redirect()->route('registro-tecnicos.show', $registro)
            ->with('success', 'Registro Técnico creado exitosamente.');
    }

    public function show(RegistroTecnico $registroTecnico)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $this->authorize('view', $registroTecnico);

        $registroTecnico->load(['client', 'farm.clientGroup.members.client', 'lot', 'workOrders']);

        $objectiveOptions = $this->objectiveLabels();
        $statusLabels = $this->statusLabels();

        $organizationId = $registroTecnico->organization_id ?? Auth::user()->organization_id;
        $farmsMap = Farm::with('client', 'lots')
            ->where('organization_id', $organizationId)
            ->where('status', true)
            ->whereNotNull('polygon_coordinates')
            ->get();

        $farmsMapPayload = $farmsMap->map(function ($farm) {
            $lots = $farm->lots
                ->whereNotNull('polygon_coordinates')
                ->map(function ($lot) {
                    return [
                        'id' => $lot->id,
                        'name' => $lot->name,
                        'polygon' => $lot->polygon_coordinates ? json_decode($lot->polygon_coordinates, true) : null,
                    ];
                })
                ->values();
            return [
                'id' => $farm->id,
                'name' => $farm->name,
                'client_id' => $farm->client_id,
                'localidad' => $farm->client?->localidad,
                'polygon' => $farm->polygon_coordinates ? json_decode($farm->polygon_coordinates, true) : null,
                'lat' => $farm->lat,
                'lng' => $farm->lng,
                'lots' => $lots,
            ];
        })->values();

        return view('registro-tecnicos.show', compact('registroTecnico', 'objectiveOptions', 'statusLabels', 'farmsMapPayload'));
    }

    public function edit(RegistroTecnico $registroTecnico)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $this->authorize('update', $registroTecnico);

        $organizationId = Auth::user()->organization_id;

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $clientGroups = \App\Models\ClientGroup::where('organization_id', $organizationId)
            ->orderBy('name')
            ->get();

        $farms = Farm::with('lots')
            ->where('organization_id', $organizationId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $objectiveOptions = $this->objectiveLabels();
        $statusLabels = $this->statusLabels();

        $farmsMap = Farm::with('client', 'lots')
            ->where('organization_id', $organizationId)
            ->where('status', true)
            ->whereNotNull('polygon_coordinates')
            ->get();

        $farmsMapPayload = $farmsMap->map(function ($farm) {
            $lots = $farm->lots
                ->whereNotNull('polygon_coordinates')
                ->map(function ($lot) {
                    return [
                        'id' => $lot->id,
                        'name' => $lot->name,
                        'polygon' => $lot->polygon_coordinates ? json_decode($lot->polygon_coordinates, true) : null,
                    ];
                })
                ->values();
            return [
                'id' => $farm->id,
                'name' => $farm->name,
                'client_id' => $farm->client_id,
                'client_group_id' => $farm->client_group_id,
                'localidad' => $farm->client?->localidad,
                'polygon' => $farm->polygon_coordinates ? json_decode($farm->polygon_coordinates, true) : null,
                'lat' => $farm->lat,
                'lng' => $farm->lng,
                'lots' => $lots,
            ];
        })->values();

        return view('registro-tecnicos.edit', compact('registroTecnico', 'clients', 'clientGroups', 'farms', 'objectiveOptions', 'statusLabels', 'farmsMapPayload'));
    }

    public function update(Request $request, RegistroTecnico $registroTecnico)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $this->authorize('update', $registroTecnico);

        $validated = $this->validateRegistro($request, true);

        $organizationId = Auth::user()->organization_id;

        $clientId = $validated['client_id'] ?? null;
        $farmId = $validated['farm_id'] ?? null;
        $lotId = $validated['lot_id'] ?? null;

        $client = null;
        if ($clientId) {
            $client = Client::findOrFail($clientId);
            if ($client->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar un cliente de otra organización.');
            }
        }

        $farm = null;
        if ($farmId) {
            $farm = Farm::findOrFail($farmId);
            if ($farm->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar una explotación de otra organización.');
            }
            if ($client && $farm->client_id !== $client->id) {
                abort(403, 'La explotación seleccionada no pertenece al cliente indicado.');
            }
        }

        $lot = null;
        if ($lotId) {
            $lot = FarmLot::findOrFail($lotId);
            $farm = $farm ?: $lot->farm;
            if (!$farm || $farm->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar un lote de otra organización.');
            }
            if ($farmId && $lot->farm_id !== $farmId) {
                $farmId = $lot->farm_id;
            }
            if ($client && $farm->client_id !== $client->id) {
                $clientId = $farm->client_id;
                $client = $farm->client;
            }
            $farmId = $farm->id;
            $clientId = $farm->client_id;
        }

        if (!$farm && !empty($validated['lat']) && !empty($validated['lng'])) {
            [$farmResolved, $lotResolved] = $this->resolveFarmLotByPoint($organizationId, (float) $validated['lat'], (float) $validated['lng']);
            if ($farmResolved) {
                $farm = $farmResolved;
                $farmId = $farmResolved->id;
                $clientId = $farmResolved->client_id;
                if ($lotResolved) {
                    $lotId = $lotResolved->id;
                }
            }
        }

        $photos = $this->storePhotos($request->file('photos'), $registroTecnico->photos ?? []);
        $audioPath = $registroTecnico->audio_path;
        $newAudio = $this->storeAudio($request->file('audio'));
        if ($newAudio) {
            $audioPath = $newAudio;
        }

        $user = Auth::user();
        $status = $validated['status'] ?? $registroTecnico->status;

        $payload = [
            'client_id' => $clientId,
            'farm_id' => $farmId,
            'lot_id' => $lotId,
            'status' => $status,
            'objectives' => $validated['objectives'] ?? [],
            'note' => $validated['note'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'photos' => $photos,
            'audio_path' => $audioPath,
            'canceled_reason' => $validated['canceled_reason'] ?? $registroTecnico->canceled_reason,
        ];

        if ($status === RegistroTecnico::STATUS_CANCELADO) {
            $payload['canceled_by'] = $this->nickname($user);
        }

        if ($status === RegistroTecnico::STATUS_CERRADO) {
            $payload['closed_by'] = $this->nickname($user);
        }

        $registroTecnico->update($payload);

        return redirect()->route('registro-tecnicos.show', $registroTecnico)
            ->with('success', 'Registro Técnico actualizado exitosamente.');
    }

    public function destroy(RegistroTecnico $registroTecnico)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $this->authorize('delete', $registroTecnico);

        $registroTecnico->delete();

        return redirect()->route('registro-tecnicos.index')
            ->with('success', 'Registro Técnico eliminado exitosamente.');
    }

    public function createWorkOrder(RegistroTecnico $registroTecnico)
    {
        if (!$this->tableReady()) {
            return redirect()->route('dashboard')
                ->with('error', 'Debes ejecutar las migraciones para usar Registro Técnico.');
        }

        $this->authorize('view', $registroTecnico);

        if ($registroTecnico->status === RegistroTecnico::STATUS_CANCELADO) {
            abort(403, 'No puedes generar una OT desde un registro cancelado.');
        }

        return redirect()->route('work-orders.create', [
            'registro_tecnico_id' => $registroTecnico->id,
        ]);
    }

    private function validateRegistro(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'client_id' => 'nullable|exists:clients,id',
            'farm_id' => 'nullable|exists:farms,id',
            'lot_id' => 'nullable|exists:farm_lots,id',
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'note' => 'nullable|string',
            'objectives' => 'nullable|array',
            'objectives.*' => 'in:maleza,plaga,enfermedad',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ];

        if ($isUpdate) {
            $rules['status'] = 'nullable|in:abierto,en_proceso,cerrado,cancelado';
            $rules['canceled_reason'] = 'required_if:status,cancelado|nullable|string';
        }

        $rules['lat'][] = 'required_without_all:client_id,farm_id,lot_id,lng';
        $rules['lng'][] = 'required_without_all:client_id,farm_id,lot_id,lat';

        return $request->validate($rules);
    }

    private function tableReady(): bool
    {
        return Schema::hasTable('registro_tecnicos');
    }

    private function storePhotos(?array $files, array $existing = []): array
    {
        if (!$files) {
            return $existing;
        }

        foreach ($files as $file) {
            $existing[] = $file->store('registro-tecnico/photos', 'public');
        }

        return $existing;
    }

    private function storeAudio($file): ?string
    {
        if (!$file) {
            return null;
        }

        return $file->store('registro-tecnico/audio', 'public');
    }

    private function resolveFarmLotByPoint(int $organizationId, float $lat, float $lng): array
    {
        $farms = Farm::with('lots')
            ->where('organization_id', $organizationId)
            ->whereNotNull('polygon_coordinates')
            ->get();

        foreach ($farms as $farm) {
            $coords = json_decode($farm->polygon_coordinates, true);
            if (!is_array($coords) || count($coords) < 3) {
                continue;
            }

            $polygon = [];
            foreach ($coords as $point) {
                if (!isset($point['lat'], $point['lng'])) {
                    continue 2;
                }
                $polygon[] = [(float) $point['lat'], (float) $point['lng']];
            }

            if ($this->pointInPolygon($lat, $lng, $polygon)) {
                $lotMatch = null;
                foreach ($farm->lots as $lot) {
                    $lotCoords = $lot->polygon_coordinates ? json_decode($lot->polygon_coordinates, true) : null;
                    if (!is_array($lotCoords) || count($lotCoords) < 3) {
                        continue;
                    }
                    $lotPolygon = [];
                    foreach ($lotCoords as $point) {
                        if (!isset($point['lat'], $point['lng'])) {
                            continue 2;
                        }
                        $lotPolygon[] = [(float) $point['lat'], (float) $point['lng']];
                    }
                    if ($this->pointInPolygon($lat, $lng, $lotPolygon)) {
                        $lotMatch = $lot;
                        break;
                    }
                }

                return [$farm, $lotMatch];
            }
        }

        return [null, null];
    }

    private function pointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $inside = false;
        $count = count($polygon);
        $j = $count - 1;

        for ($i = 0; $i < $count; $i++) {
            [$yi, $xi] = $polygon[$i];
            [$yj, $xj] = $polygon[$j];

            $intersect = (($yi > $lat) !== ($yj > $lat));
            if ($intersect) {
                $den = ($yj - $yi);
                if ($den == 0.0) {
                    $j = $i;
                    continue;
                }
                $x = ($xj - $xi) * ($lat - $yi) / $den + $xi;
                if ($lng < $x) {
                    $inside = !$inside;
                }
            }

            $j = $i;
        }

        return $inside;
    }

    private function objectiveLabels(): array
    {
        return [
            RegistroTecnico::OBJECTIVE_MALEZA => 'Maleza',
            RegistroTecnico::OBJECTIVE_PLAGA => 'Plaga',
            RegistroTecnico::OBJECTIVE_ENFERMEDAD => 'Enfermedad',
        ];
    }

    private function statusLabels(): array
    {
        return [
            RegistroTecnico::STATUS_ABIERTO => 'Abierto',
            RegistroTecnico::STATUS_EN_PROCESO => 'En Proceso',
            RegistroTecnico::STATUS_CERRADO => 'Cerrado',
            RegistroTecnico::STATUS_CANCELADO => 'Cancelado',
        ];
    }

    private function nickname($user): string
    {
        return $user->nickname ?: ($user->name ?: $user->email);
    }
}
