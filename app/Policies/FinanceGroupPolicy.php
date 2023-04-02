<?php

namespace App\Policies;

use App\Models\FinanceGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinanceGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('finance-groups.view');
    }

    public function view(User $user, FinanceGroup $financeGroup): bool
    {
        return $user->can('finance-groups.view');
    }

    public function create(User $user): bool
    {
        return $user->can('finance-groups.create');
    }

    public function update(User $user, FinanceGroup $financeGroup): bool
    {
        return $user->can('finance-groups.update');
    }

    public function delete(User $user, FinanceGroup $financeGroup): bool
    {
        return $user->can('finance-groups.delete');
    }
}
