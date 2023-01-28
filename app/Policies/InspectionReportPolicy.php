<?php

namespace App\Policies;

use App\Models\InspectionReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InspectionReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('inspection-reports.view.own') || $user->can('inspection-reports.view.other');
    }

    public function view(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.view.own');
        }

        return $user->can('inspection-reports.view.other');
    }

    public function create(User $user): bool
    {
        return $user->can('inspection-reports.create');
    }

    public function update(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->status === 'finished') {
            return false;
        }

        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.update.own');
        }

        return $user->can('inspection-reports.update.other');
    }

    public function delete(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->status === 'finished') {
            return false;
        }

        if($inspectionReport->status === 'signed') {
            return $user->can('inspection-reports.approve');
        }

        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.delete.own');
        }

        return $user->can('inspection-reports.delete.other');
    }

    public function email(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.email.own');
        }

        return $user->can('inspection-reports.email.other');
    }

    public function createPdf(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.createpdf.own');
        }

        return $user->can('inspection-reports.createpdf.other');
    }

    public function emailSignatureRequest(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->status !== 'new') {
            return false;
        }

        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.send-signature-request.own');
        }

        return $user->can('inspection-reports.send-signature-request.other');
    }

    public function emailDownloadRequest(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->status === 'new') {
            return false;
        }

        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.send-download-request.own');
        }

        return $user->can('inspection-reports.send-download-request.other');
    }

    public function sign(User $user, InspectionReport $inspectionReport): bool
    {
        if($inspectionReport->status !== 'new') {
            return false;
        }

        if($inspectionReport->employee_id === $user->employee_id) {
            return $user->can('inspection-reports.get-signature.own');
        }

        return $user->can('inspection-reports.get-signature.other');
    }

    public function approve(User $user, InspectionReport $inspectionReport): bool
    {
        return $user->can('inspection-reports.approve');
    }
}
