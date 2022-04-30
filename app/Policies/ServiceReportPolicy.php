<?php

namespace App\Policies;

use App\Models\ServiceReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function delete(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function email(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function createPdf(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function emailSignatureRequest(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function emailDownloadRequest(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }

    public function sign(User $user, ServiceReport $serviceReport): bool
    {
        return true;
    }
}
