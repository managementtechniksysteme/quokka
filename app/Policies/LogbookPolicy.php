<?php

namespace App\Policies;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogbookPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('logbook.view.own') || $user->can('logbook.view.other');
    }

    public function create(User $user): bool
    {
        return $user->can('logbook.create');
    }

    public function update(User $user, Logbook $logbook): bool
    {
        if($logbook->employee_id === $user->employee_id) {
            return $user->can('logbook.update.own');
        }

        return $user->can('logbook.update.other');
    }

    public function delete(User $user, Logbook $logbook): bool
    {
        if($logbook->employee_id === $user->employee_id) {
            return $user->can('logbook.delete.own');
        }

        return $user->can('logbook.delete.other');
    }

    public function email(User $user, Logbook $logbook): bool
    {
        return $user->can('logbook.email');
    }

    public function createPdf(User $user, Logbook $logbook): bool
    {
        return $user->can('logbook.createpdf');
    }
}
