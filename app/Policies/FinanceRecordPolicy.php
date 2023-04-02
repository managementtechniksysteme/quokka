<?php

namespace App\Policies;

use App\Models\FinanceRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinanceRecordPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('finance-records.view');
    }

    public function view(User $user, FinanceRecord $financeRecord): bool
    {
        return $user->can('finance-records.view');
    }

    public function create(User $user): bool
    {
        return $user->can('finance-records.create');
    }

    public function update(User $user, FinanceRecord $financeRecord): bool
    {
        return $user->can('finance-records.update');
    }

    public function delete(User $user, FinanceRecord $financeRecord): bool
    {
        return $user->can('finance-records.delete');
    }
}
