<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrderProduct;

class WorkOrderProductPolicy
{
    public function view(User $user, WorkOrderProduct $product): bool
    {
        return $user->is_admin || $product->organization_id === $user->organization_id;
    }

    public function update(User $user, WorkOrderProduct $product): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($product->organization_id !== $user->organization_id) {
            return false;
        }

        return !$product->workOrder->isClosedOrCanceled();
    }
}
