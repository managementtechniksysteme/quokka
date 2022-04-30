<?php

namespace App\Policies;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Memo $memo): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Memo $memo): bool
    {
        return true;
    }

    public function delete(User $user, Memo $memo): bool
    {
        return true;
    }

    public function email(User $user, Memo $memo): bool
    {
        return true;
    }

    public function createPdf(User $user, Memo $memo): bool
    {
        return true;
    }
}
