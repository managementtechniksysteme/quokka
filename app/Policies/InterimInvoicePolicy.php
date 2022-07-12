<?php

namespace App\Policies;

use App\Models\InterimInvoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Request;

class InterimInvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view', Request::route()->project) &&
            $user->can('interim-invoices.view');
    }

    public function view(User $user, InterimInvoice $interimInvoice): bool
    {
        return $user->can('view', $interimInvoice->project) &&
            $user->can('interim-invoices.view');
    }

    public function create(User $user): bool
    {
        return $user->can('view', Request::route()->project) &&
            $user->can('interim-invoices.create');
    }

    public function update(User $user, InterimInvoice $interimInvoice): bool
    {
        return $user->can('view', $interimInvoice->project) &&
            $user->can('interim-invoices.update');
    }

    public function delete(User $user, InterimInvoice $interimInvoice): bool
    {
        \Illuminate\Support\Facades\Log::info($interimInvoice);
        \Illuminate\Support\Facades\Log::info($interimInvoice->project);

        return $user->can('view', $interimInvoice->project) &&
            $user->can('interim-invoices.delete');
    }
}
