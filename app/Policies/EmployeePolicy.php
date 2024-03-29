<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('employees.view');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->can('employees.view');
    }

    public function create(User $user): bool
    {
        return $user->can('employees.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->can('employees.update');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('employees.delete');
    }

    public function email(User $user, Employee $employee): bool
    {
        return $user->can('employees.email');
    }

    public function createPdf(User $user, Employee $employee): bool
    {
        return $user->can('employees.createpdf');
    }

    public function impersonate(User $user, Employee $employee): bool
    {
        // get original user
        $originalUser = Session::has('impersonatorId') ? User::find(Session::get('impersonatorId')) : $user;

        if($originalUser->employee_id === $employee->person_id) {
            return false;
        }

        return $originalUser->can('employees.impersonate');
    }
}
