<?php

namespace App\Policies;

use App\Models\Accounting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('accounting.view.own') || $user->can('accounting.view.other');
    }

    public function create(User $user): bool
    {
        return $user->can('accounting.create');
    }

    public function update(User $user, Accounting $accounting): bool
    {
        if($accounting->employee_id === $user->employee_id) {
            return $user->can('accounting.update.own');
        }

        return $user->can('accounting.update.other');
    }

    public function delete(User $user, Accounting $accounting): bool
    {
        if($accounting->employee_id === $user->employee_id) {
            return $user->can('accounting.delete.own');
        }

        return $user->can('accounting.delete.other');
    }

    public function email(User $user, Accounting $accounting): bool
    {
        return $user->can('accounting.email');
    }

    public function createPdf(User $user): bool
    {
        return $user->can('accounting.createpdf');
    }
}
