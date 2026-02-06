<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrderFarm;

class WorkOrderFarmPolicy
{
    public function view(User $user, WorkOrderFarm $farm): bool
    {
        return $user->is_admin || $farm->organization_id === $user->organization_id;
    }

    public function update(User $user, WorkOrderFarm $farm): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($farm->organization_id !== $user->organization_id) {
            return false;
        }

        return !$farm->workOrder->isClosedOrCanceled();
    }

    public function apply(User $user, WorkOrderFarm $farm): bool
    {
        return $this->update($user, $farm);
    }
}
