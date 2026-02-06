<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->exists;
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->is_admin || $workOrder->organization_id === $user->organization_id;
    }

    public function create(User $user): bool
    {
        return $user->exists;
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($workOrder->organization_id !== $user->organization_id) {
            return false;
        }

        return !$workOrder->isClosedOrCanceled();
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return (bool) $user->is_admin;
    }
}
