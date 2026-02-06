<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use App\Models\WorkOrderProduct;

class WorkOrderProductService
{
    public function normalizeLine(array $data): array
    {
        if (!empty($data['total_has_bloque'])) {
            $totalHas = (float) $data['total_has_bloque'];

            if (empty($data['dosis']) && isset($data['total_linea_producto'])) {
                $data['dosis'] = (float) $data['total_linea_producto'] / $totalHas;
            }

            if (!empty($data['dosis'])) {
                $data['total_linea_producto'] = (float) $data['dosis'] * $totalHas;
            }
        }

        if (!empty($data['precio_unitario']) && isset($data['total_linea_producto'])) {
            $data['total_costo_linea'] = (float) $data['total_linea_producto'] * (float) $data['precio_unitario'];
        } else {
            $data['total_costo_linea'] = null;
        }

        return $data;
    }

    public function syncBlockTotals(WorkOrder $workOrder, string $bloque): void
    {
        $totalHas = (float) $workOrder->farms()
            ->where('bloque', $bloque)
            ->sum('has');

        $products = $workOrder->products()->where('bloque', $bloque)->get();

        foreach ($products as $product) {
            $product->total_has_bloque = $totalHas;
            if ($product->dosis !== null) {
                $product->total_linea_producto = $product->dosis * $totalHas;
            }
            if ($product->precio_unitario !== null) {
                $product->total_costo_linea = $product->total_linea_producto * $product->precio_unitario;
            }
            $product->save();
        }
    }

    public function upsertLine(WorkOrder $workOrder, array $data): WorkOrderProduct
    {
        $data = $this->normalizeLine($data);

        return WorkOrderProduct::updateOrCreate(
            [
                'organization_id' => $workOrder->organization_id,
                'work_order_id' => $workOrder->id,
                'bloque' => $data['bloque'],
                'product_id' => $data['product_id'],
            ],
            $data
        );
    }
}
