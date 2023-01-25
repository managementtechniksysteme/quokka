<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('addresses.view');
    }

    public function view(User $user, Address $address): bool
    {
        return $user->can('addresses.view');
    }

    public function create(User $user): bool
    {
        return $user->can('addresses.create');
    }

    public function update(User $user, Address $address): bool
    {
        return $user->can('addresses.update');
    }

    public function delete(User $user, Address $address): bool
    {
        return $user->can('addresses.delete');
    }

    public function email(User $user, Address $address): bool
    {
        return $user->can('addresses.email');
    }

    public function createPdf(User $user, Address $address): bool
    {
        return $user->can('addresses.createpdf');
    }
}
