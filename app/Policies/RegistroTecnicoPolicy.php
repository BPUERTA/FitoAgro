<?php

namespace App\Policies;

use App\Models\RegistroTecnico;
use App\Models\User;

class RegistroTecnicoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->exists;
    }

    public function view(User $user, RegistroTecnico $registroTecnico): bool
    {
        return $user->is_admin || $registroTecnico->organization_id === $user->organization_id;
    }

    public function create(User $user): bool
    {
        return $user->exists;
    }

    public function update(User $user, RegistroTecnico $registroTecnico): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($registroTecnico->organization_id !== $user->organization_id) {
            return false;
        }

        return !$registroTecnico->isClosedOrCanceled();
    }

    public function delete(User $user, RegistroTecnico $registroTecnico): bool
    {
        return (bool) $user->is_admin;
    }
}
