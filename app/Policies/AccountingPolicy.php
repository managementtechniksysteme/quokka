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
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Accounting $address): bool
    {
        return true;
    }

    public function delete(User $user, Accounting $address): bool
    {
        return true;
    }

    public function email(User $user, Accounting $address): bool
    {
        return true;
    }

    public function createPdf(User $user, Accounting $address): bool
    {
        return true;
    }
}
