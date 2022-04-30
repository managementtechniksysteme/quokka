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
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Logbook $logbook): bool
    {
        return true;
    }

    public function delete(User $user, Logbook $logbook): bool
    {
        return true;
    }

    public function email(User $user, Logbook $logbook): bool
    {
        return true;
    }

    public function createPdf(User $user, Logbook $logbook): bool
    {
        return true;
    }
}
