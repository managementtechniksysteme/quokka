<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('people.view');
    }

    public function view(User $user, Person $person): bool
    {
        return $user->can('people.view');
    }

    public function create(User $user): bool
    {
        return $user->can('people.create');
    }

    public function update(User $user, Person $person): bool
    {
        return $user->can('people.update');
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->can('people.delete');
    }

    public function email(User $user, Person $person): bool
    {
        return $user->can('people.email');
    }

    public function createPdf(User $user, Person $person): bool
    {
        return $user->can('people.createpdf');
    }
}
