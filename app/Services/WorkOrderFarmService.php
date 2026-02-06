<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use App\Models\WorkOrderStatusLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WorkOrderFarmService
{
    public function apply(WorkOrderFarm $farm, string $fechaAplicacion, User $user): WorkOrderFarm
    {
        return DB::transaction(function () use ($farm, $fechaAplicacion, $user) {
            $lockedFarm = WorkOrderFarm::whereKey($farm->id)->lockForUpdate()->firstOrFail();
            $workOrder = WorkOrder::whereKey($lockedFarm->work_order_id)->lockForUpdate()->firstOrFail();

            $lockedFarm->fecha_aplicacion = Carbon::parse($fechaAplicacion)->toDateString();
            $lockedFarm->save();

            if ($workOrder->status === WorkOrder::STATUS_CANCELADO) {
                return $lockedFarm;
            }

            $hasPending = $workOrder->farms()
                ->where('line_status', WorkOrderFarm::STATUS_PENDIENTE)
                ->exists();

            if (!$hasPending && $workOrder->status !== WorkOrder::STATUS_CERRADO) {
                $fromStatus = $workOrder->status;
                $workOrder->status = WorkOrder::STATUS_CERRADO;
                $workOrder->closed_by = $this->nickname($user);
                $workOrder->save();

                WorkOrderStatusLog::create([
                    'organization_id' => $workOrder->organization_id,
                    'work_order_id' => $workOrder->id,
                    'from_status' => $fromStatus,
                    'to_status' => $workOrder->status,
                    'changed_by' => $this->nickname($user),
                ]);
            }

            return $lockedFarm;
        });
    }

    private function nickname(User $user): string
    {
        return $user->nickname ?: ($user->name ?: $user->email);
    }
}
