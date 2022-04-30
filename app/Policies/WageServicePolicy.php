<?php

namespace App\Policies;

use App\Models\WageService;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WageServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('wage-services.view');
    }

    public function view(User $user, WageService $wageService): bool
    {
        return $user->can('wage-services.view');
    }

    public function create(User $user): bool
    {
        return $user->can('wage-services.create');
    }

    public function update(User $user, WageService $wageService): bool
    {
        return $user->can('wage-services.update');
    }

    public function delete(User $user, WageService $wageService): bool
    {
        return $user->can('wage-services.delete');
    }

    public function email(User $user, WageService $wageService): bool
    {
        return $user->can('wage-services.email');
    }

    public function createPdf(User $user, WageService $wageService): bool
    {
        return $user->can('wage-services.createpdf');
    }
}
