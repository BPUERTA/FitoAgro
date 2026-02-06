<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\Product;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use App\Models\WorkOrderProduct;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class WorkOrderService
{
    public function __construct(private WorkOrderProductService $productService)
    {
    }

    public function create(array $data, User $user): WorkOrder
    {
        return DB::transaction(function () use ($data, $user) {
            $workOrder = WorkOrder::create([
                'organization_id' => $user->organization_id,
                'client_id' => $data['client_id'],
                'registro_tecnico_id' => $data['registro_tecnico_id'] ?? null,
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'] ?? WorkOrder::PRIORITY_MEDIUM,
                'scheduled_start_at' => $data['scheduled_start_at'] ?? null,
                'scheduled_end_at' => $data['scheduled_end_at'] ?? null,
                'status' => $data['status'] ?? WorkOrder::STATUS_PENDIENTE,
                'created_by' => $this->nickname($user),
                'canceled_by' => $data['canceled_by'] ?? null,
                'canceled_reason' => $data['canceled_reason'] ?? null,
            ]);

            $farms = $data['farms'] ?? [];
            foreach ($farms as $farmData) {
                $this->createFarmLine($workOrder, $farmData);
            }

            $products = $data['products'] ?? [];
            $blockTotals = $this->blockTotalsFromFarms($farms);

            foreach ($products as $productData) {
                $productData['total_has_bloque'] = $blockTotals[$productData['bloque']] ?? $productData['total_has_bloque'];
                $this->createProductLine($workOrder, $productData);
            }

            foreach (array_keys($blockTotals) as $bloque) {
                $this->productService->syncBlockTotals($workOrder, $bloque);
            }

            return $workOrder;
        });
    }

    public function update(WorkOrder $workOrder, array $data, User $user): WorkOrder
    {
        return DB::transaction(function () use ($workOrder, $data, $user) {
            $workOrder->update([
                'client_id' => $data['client_id'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'] ?? $workOrder->priority,
                'scheduled_start_at' => $data['scheduled_start_at'] ?? null,
                'scheduled_end_at' => $data['scheduled_end_at'] ?? null,
                'status' => $data['status'] ?? $workOrder->status,
                'canceled_by' => $data['canceled_by'] ?? $workOrder->canceled_by,
                'canceled_reason' => $data['canceled_reason'] ?? $workOrder->canceled_reason,
            ]);

            $farms = $data['farms'] ?? [];
            $this->syncFarmLines($workOrder, $farms);

            $products = $data['products'] ?? [];
            $this->syncProductLines($workOrder, $products, $farms);

            return $workOrder;
        });
    }

    private function createFarmLine(WorkOrder $workOrder, array $farmData): WorkOrderFarm
    {
        $name = $farmData['exploitation_name'] ?? null;
        if (!$name && !empty($farmData['exploitation_id'])) {
            $name = Farm::find($farmData['exploitation_id'])?->name;
        }

        return WorkOrderFarm::create([
            'organization_id' => $workOrder->organization_id,
            'work_order_id' => $workOrder->id,
            'bloque' => $farmData['bloque'],
            'exploitation_id' => $farmData['exploitation_id'] ?? null,
            'exploitation_name' => $name ?? 'Sin nombre',
            'has' => $farmData['has'],
            'distancia_poblado' => $farmData['distancia_poblado'] ?? null,
            'fecha_aplicacion' => $farmData['fecha_aplicacion'] ?? null,
        ]);
    }

    private function createProductLine(WorkOrder $workOrder, array $productData): WorkOrderProduct
    {
        $productName = $productData['product_name'] ?? null;
        if (!$productName && !empty($productData['product_id'])) {
            $productName = Product::find($productData['product_id'])?->descripcion;
        }

        $payload = $this->productService->normalizeLine(array_merge($productData, [
            'organization_id' => $workOrder->organization_id,
            'work_order_id' => $workOrder->id,
            'product_name' => $productName ?? 'Sin nombre',
        ]));

        return WorkOrderProduct::create($payload);
    }

    private function syncFarmLines(WorkOrder $workOrder, array $farms): void
    {
        $keepIds = collect($farms)->pluck('id')->filter()->all();

        $workOrder->farms()
            ->when($keepIds, fn ($query) => $query->whereNotIn('id', $keepIds))
            ->delete();

        foreach ($farms as $farmData) {
            if (!empty($farmData['id'])) {
                $line = WorkOrderFarm::where('work_order_id', $workOrder->id)
                    ->where('id', $farmData['id'])
                    ->first();

                if ($line) {
                    $line->fill(Arr::only($farmData, [
                        'bloque',
                        'exploitation_id',
                        'exploitation_name',
                        'has',
                        'distancia_poblado',
                        'fecha_aplicacion',
                    ]));
                    $line->save();
                    continue;
                }
            }

            $this->createFarmLine($workOrder, $farmData);
        }
    }

    private function syncProductLines(WorkOrder $workOrder, array $products, array $farms): void
    {
        $keepIds = collect($products)->pluck('id')->filter()->all();

        $workOrder->products()
            ->when($keepIds, fn ($query) => $query->whereNotIn('id', $keepIds))
            ->delete();

        $blockTotals = $this->blockTotalsFromFarms($farms);

        foreach ($products as $productData) {
            $productData['total_has_bloque'] = $blockTotals[$productData['bloque']] ?? $productData['total_has_bloque'];
            $payload = $this->productService->normalizeLine($productData);

            if (!empty($payload['id'])) {
                $line = WorkOrderProduct::where('work_order_id', $workOrder->id)
                    ->where('id', $payload['id'])
                    ->first();

                if ($line) {
                    $line->fill($payload);
                    $line->save();
                    continue;
                }
            }

            $this->createProductLine($workOrder, $payload);
        }

        foreach (array_keys($blockTotals) as $bloque) {
            $this->productService->syncBlockTotals($workOrder, $bloque);
        }
    }

    private function blockTotalsFromFarms(array $farms): array
    {
        return collect($farms)
            ->groupBy('bloque')
            ->map(fn ($items) => (float) collect($items)->sum('has'))
            ->all();
    }

    private function nickname(User $user): string
    {
        return $user->nickname ?: ($user->name ?: $user->email);
    }
}
