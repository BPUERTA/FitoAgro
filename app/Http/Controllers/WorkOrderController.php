<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Farm;
use App\Models\Product;
use App\Models\RegistroTecnico;
use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use App\Services\WorkOrderFarmService;
use App\Services\WorkOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function __construct(
        private WorkOrderService $workOrderService,
        private WorkOrderFarmService $farmService
    ) {
    }

    public function index(Request $request)
    {
        $organizationId = Auth::user()->organization_id;

        $workOrders = WorkOrder::forOrganization($organizationId)
            ->with(['client', 'farms'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                return $query->where('priority', $priority);
            })
            ->when($request->client_id, function ($query, $clientId) {
                return $query->where('client_id', $clientId);
            })
            ->latest()
            ->paginate(15);

        return view('work-orders.index', compact('workOrders'));
    }

    public function create(Request $request)
    {
        $organizationId = Auth::user()->organization_id;

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::where('status', true)
            ->orderBy('descripcion')
            ->get();

        $priorities = [
            WorkOrder::PRIORITY_LOW => 'Baja',
            WorkOrder::PRIORITY_MEDIUM => 'Media',
            WorkOrder::PRIORITY_HIGH => 'Alta',
            WorkOrder::PRIORITY_URGENT => 'Urgente',
        ];

        $prefillClientId = null;
        $prefillFarm = null;
        $prefillRegistroTecnicoId = null;

        if ($request->filled('registro_tecnico_id')) {
            $registro = RegistroTecnico::with('farm')->findOrFail($request->input('registro_tecnico_id'));
            $this->authorize('view', $registro);

            if ($registro->status === RegistroTecnico::STATUS_CANCELADO) {
                abort(403, 'No puedes generar una OT desde un registro cancelado.');
            }

            if ($organizationId && $registro->organization_id !== $organizationId) {
                abort(403, 'No puedes usar un registro de otra organización.');
            }

            $prefillRegistroTecnicoId = $registro->id;
            $prefillClientId = $registro->client_id;

            if ($registro->farm) {
                $prefillFarm = [
                    'exploitation_id' => $registro->farm->id,
                    'exploitation_name' => $registro->farm->name,
                    'has' => $registro->farm->has,
                    'distancia_poblado' => $registro->farm->distancia_poblado,
                ];
            }
        }

        return view('work-orders.create', compact('clients', 'products', 'priorities', 'prefillClientId', 'prefillFarm', 'prefillRegistroTecnicoId'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateWorkOrder($request);

        $organizationId = Auth::user()->organization_id;
        $client = Client::findOrFail($validated['client_id']);
        if ($organizationId && $client->organization_id != $organizationId) {
            abort(403, 'No puedes crear una orden para un cliente de otra organizacion');
        }

        $farmIds = collect($validated['farms'])->pluck('exploitation_id')->filter()->unique()->all();
        if ($organizationId && $farmIds) {
            $farmCount = Farm::whereIn('id', $farmIds)
                ->where('organization_id', $organizationId)
                ->where('client_id', $client->id)
                ->count();
            if ($farmCount !== count($farmIds)) {
                abort(403, 'No puedes asociar explotaciones de otra organizacion');
            }
        }

        $registroTecnico = null;
        if (!empty($validated['registro_tecnico_id'])) {
            $registroTecnico = RegistroTecnico::with('farm')->findOrFail($validated['registro_tecnico_id']);
            if ($organizationId && $registroTecnico->organization_id !== $organizationId) {
                abort(403, 'No puedes asociar un registro de otra organizacion');
            }
            if ($registroTecnico->status === RegistroTecnico::STATUS_CANCELADO) {
                abort(403, 'No puedes generar una OT desde un registro cancelado');
            }
            if ($registroTecnico->client_id && $registroTecnico->client_id !== $client->id) {
                abort(403, 'El registro tecnico no coincide con el cliente seleccionado');
            }
            if ($registroTecnico->farm_id && !in_array($registroTecnico->farm_id, $farmIds, true)) {
                abort(403, 'La OT debe incluir la explotacion vinculada al registro tecnico');
            }
        }

        $user = Auth::user();
        if (($validated['status'] ?? null) === WorkOrder::STATUS_CANCELADO) {
            $validated['canceled_by'] = $this->nickname($user);
        }

        $workOrder = $this->workOrderService->create($validated, $user);

        if ($registroTecnico && $registroTecnico->status !== RegistroTecnico::STATUS_EN_PROCESO) {
            $registroTecnico->status = RegistroTecnico::STATUS_EN_PROCESO;
            $registroTecnico->save();
        }

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Orden de trabajo creada exitosamente.');
    }

    public function show(WorkOrder $workOrder)
    {
        $this->authorize('view', $workOrder);

        $workOrder->load(['client', 'farms', 'products', 'organization']);

        $productsByBlock = $workOrder->products->groupBy('bloque');

        return view('work-orders.show', compact('workOrder', 'productsByBlock'));
    }

    public function pdf(WorkOrder $workOrder)
    {
        $this->authorize('view', $workOrder);

        $workOrder->load(['client', 'farms', 'products', 'organization']);

        $productsByBlock = $workOrder->products->groupBy('bloque');

        try {
            $pdf = Pdf::loadView('work-orders.pdf', compact('workOrder', 'productsByBlock'));
            $pdf->setPaper('a4', 'portrait');
            return $pdf->stream('orden-' . ($workOrder->code ?? $workOrder->id) . '.pdf');
        } catch (\Throwable $e) {
            return view('work-orders.pdf', compact('workOrder', 'productsByBlock'));
        }
    }

    public function edit(WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);

        $organizationId = Auth::user()->organization_id;

        $clients = Client::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::where('status', true)
            ->orderBy('descripcion')
            ->get();

        $priorities = [
            WorkOrder::PRIORITY_LOW => 'Baja',
            WorkOrder::PRIORITY_MEDIUM => 'Media',
            WorkOrder::PRIORITY_HIGH => 'Alta',
            WorkOrder::PRIORITY_URGENT => 'Urgente',
        ];

        $workOrder->load(['farms', 'products']);

        $existingFarms = $workOrder->farms->map(function ($farm) {
            return [
                'id' => $farm->id,
                'bloque' => $farm->bloque,
                'exploitation_id' => $farm->exploitation_id,
                'exploitation_name' => $farm->exploitation_name,
                'has' => $farm->has,
                'distancia_poblado' => $farm->distancia_poblado,
                'fecha_aplicacion' => $farm->fecha_aplicacion?->toDateString(),
                'line_status' => $farm->line_status,
            ];
        });

        $existingProducts = $workOrder->products->map(function ($product) {
            return [
                'id' => $product->id,
                'bloque' => $product->bloque,
                'total_has_bloque' => $product->total_has_bloque,
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'dosis' => $product->dosis,
                'um_dosis' => $product->um_dosis,
                'total_linea_producto' => $product->total_linea_producto,
                'um_total' => $product->um_total,
                'nota' => $product->nota,
                'precio_unitario' => $product->precio_unitario,
                'total_costo_linea' => $product->total_costo_linea,
            ];
        });

        return view('work-orders.edit', compact('workOrder', 'clients', 'products', 'priorities', 'existingFarms', 'existingProducts'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);

        $validated = $this->validateWorkOrder($request);

        $organizationId = Auth::user()->organization_id;
        $client = Client::findOrFail($validated['client_id']);
        if ($organizationId && $client->organization_id != $organizationId) {
            abort(403, 'No puedes asignar un cliente de otra organizacion');
        }

        $farmIds = collect($validated['farms'])->pluck('exploitation_id')->filter()->unique()->all();
        if ($organizationId && $farmIds) {
            $farmCount = Farm::whereIn('id', $farmIds)
                ->where('organization_id', $organizationId)
                ->where('client_id', $client->id)
                ->count();
            if ($farmCount !== count($farmIds)) {
                abort(403, 'No puedes asociar explotaciones de otra organizacion');
            }
        }

        $user = Auth::user();
        if (($validated['status'] ?? null) === WorkOrder::STATUS_CANCELADO) {
            $validated['canceled_by'] = $this->nickname($user);
        }

        $this->workOrderService->update($workOrder, $validated, $user);

        $this->syncRegistroTecnicoStatus($workOrder);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Orden de trabajo actualizada exitosamente.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $this->authorize('delete', $workOrder);

        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', 'Orden de trabajo eliminada exitosamente.');
    }

    public function getFarmsByClient(Client $client)
    {
        $organizationId = Auth::user()->organization_id;

        if ($client->organization_id !== $organizationId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $farms = $client->farms()->where('status', true)->get(['id', 'name', 'has', 'distancia_poblado']);

        return response()->json($farms);
    }

    public function applyFarm(Request $request, WorkOrder $workOrder, WorkOrderFarm $farm)
    {
        if ($farm->work_order_id !== $workOrder->id) {
            abort(404);
        }

        $this->authorize('apply', $farm);

        $validated = $request->validate([
            'fecha_aplicacion' => 'required|date',
        ]);

        $this->farmService->apply($farm, $validated['fecha_aplicacion'], Auth::user());

        $this->syncRegistroTecnicoStatus($workOrder);

        return response()->json(['status' => 'ok']);
    }

    private function validateWorkOrder(Request $request): array
    {
        return $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'scheduled_start_at' => 'nullable|date',
            'scheduled_end_at' => 'nullable|date|after_or_equal:scheduled_start_at',
            'status' => 'nullable|in:pendiente,abierto,cerrado,cancelado',
            'canceled_reason' => 'required_if:status,cancelado|nullable|string',
            'registro_tecnico_id' => 'nullable|exists:registro_tecnicos,id',
            'farms' => 'required|array|min:1',
            'farms.*.id' => 'nullable|integer',
            'farms.*.bloque' => 'required|string|max:10',
            'farms.*.exploitation_id' => 'nullable|exists:farms,id',
            'farms.*.exploitation_name' => 'required|string|max:255',
            'farms.*.has' => 'required|numeric|min:0.01',
            'farms.*.distancia_poblado' => 'nullable|numeric|min:0',
            'farms.*.fecha_aplicacion' => 'nullable|date',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|integer',
            'products.*.bloque' => 'required_with:products|string|max:10',
            'products.*.total_has_bloque' => 'required_with:products|numeric|min:0.01',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.product_name' => 'nullable|string|max:255',
            'products.*.dosis' => 'required_with:products|numeric|min:0.01',
            'products.*.um_dosis' => 'required_with:products|string|max:20',
            'products.*.total_linea_producto' => 'nullable|numeric|min:0.01',
            'products.*.um_total' => 'required_with:products|string|max:20',
            'products.*.nota' => 'nullable|string|max:255',
            'products.*.precio_unitario' => 'nullable|numeric|min:0',
        ]);
    }

    private function syncRegistroTecnicoStatus(WorkOrder $workOrder): void
    {
        if (!$workOrder->registro_tecnico_id) {
            return;
        }

        $registro = RegistroTecnico::with('workOrders')->find($workOrder->registro_tecnico_id);
        if (!$registro || $registro->status === RegistroTecnico::STATUS_CANCELADO) {
            return;
        }

        $hasOpen = $registro->workOrders
            ->contains(fn ($order) => $order->status !== WorkOrder::STATUS_CERRADO);

        if ($hasOpen) {
            if ($registro->status !== RegistroTecnico::STATUS_EN_PROCESO) {
                $registro->status = RegistroTecnico::STATUS_EN_PROCESO;
                $registro->save();
            }
            return;
        }

        if ($registro->status !== RegistroTecnico::STATUS_CERRADO) {
            $registro->status = RegistroTecnico::STATUS_CERRADO;
            $registro->closed_by = $this->nickname(Auth::user());
            $registro->save();
        }
    }

    private function nickname($user): string
    {
        return $user->nickname ?: ($user->name ?: $user->email);
    }
}
