<?php

namespace App\Policies;

use App\Models\MaterialService;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('material-services.view');
    }

    public function view(User $user, MaterialService $materialService): bool
    {
        return $user->can('material-services.view');
    }

    public function create(User $user): bool
    {
        return $user->can('material-services.create');
    }

    public function update(User $user, MaterialService $materialService): bool
    {
        return $user->can('material-services.update');
    }

    public function delete(User $user, MaterialService $materialService): bool
    {
        return $user->can('material-services.delete');
    }

    public function email(User $user, MaterialService $materialService): bool
    {
        return $user->can('material-services.email');
    }

    public function createPdf(User $user, MaterialService $materialService): bool
    {
        return $user->can('material-services.createpdf');
    }
}
