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
        return $user->can('service-reports.view.own') || $user->can('service-reports.view.other');
    }

    public function view(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.view.own');
        }

        return $user->can('service-reports.view.other');
    }

    public function create(User $user): bool
    {
        return $user->can('service-reports.create');
    }

    public function update(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->status === 'finished') {
            return false;
        }

        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.update.own');
        }

        return $user->can('service-reports.update.other');
    }

    public function delete(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->status === 'finished') {
            return false;
        }

        if($serviceReport->status === 'signed') {
            return $user->can('service-reports.approve');
        }

        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.delete.own');
        }

        return $user->can('service-reports.delete.other');
    }

    public function email(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.email.own');
        }

        return $user->can('service-reports.email.other');
    }

    public function createPdf(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.createpdf.own');
        }

        return $user->can('service-reports.createpdf.other');
    }

    public function downloadList(User $user): bool
    {
        return $user->can('service-reports.view.own') || $user->can('service-reports.view.other');
    }

    public function emailSignatureRequest(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->status !== 'new') {
            return false;
        }

        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.send-signature-request.own');
        }

        return $user->can('service-reports.send-signature-request.other');
    }

    public function emailDownloadRequest(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->status === 'new') {
            return false;
        }

        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.send-download-request.own');
        }

        return $user->can('service-reports.send-download-request.other');
    }

    public function sign(User $user, ServiceReport $serviceReport): bool
    {
        if($serviceReport->status !== 'new') {
            return false;
        }

        if($serviceReport->employee_id === $user->employee_id) {
            return $user->can('service-reports.get-signature.own');
        }

        return $user->can('service-reports.get-signature.other');
    }

    public function approve(User $user, ServiceReport $serviceReport): bool
    {
        return $user->can('service-reports.approve');
    }
}
