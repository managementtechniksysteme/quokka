<?php

namespace App\Policies;

use App\Models\FlowMeterInspectionReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlowMeterInspectionReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('flow-meter-inspection-reports.view.own') ||
            $user->can('flow-meter-inspection-reports.view.other');
    }

    public function view(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.view.own');
        }

        return $user->can('flow-meter-inspection-reports.view.other');
    }

    public function create(User $user): bool
    {
        return $user->can('flow-meter-inspection-reports.create');
    }

    public function update(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->status === 'finished') {
            return false;
        }

        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.update.own');
        }

        return $user->can('flow-meter-inspection-reports.update.other');
    }

    public function delete(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->status !== 'new') {
            return false;
        }

        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.delete.own');
        }

        return $user->can('flow-meter-inspection-reports.delete.other');
    }

    public function email(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.email.own');
        }

        return $user->can('flow-meter-inspection-reports.email.other');
    }

    public function createPdf(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.createpdf.own');
        }

        return $user->can('flow-meter-inspection-reports.createpdf.other');
    }

    public function emailSignatureRequest(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->status !== 'new') {
            return false;
        }

        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.send-signature-request.own');
        }

        return $user->can('flow-meter-inspection-reports.send-signature-request.other');
    }

    public function emailDownloadRequest(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->status === 'new') {
            return false;
        }

        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.send-download-request.own');
        }

        return $user->can('flow-meter-inspection-reports.send-download-request.other');
    }

    public function sign(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        if($flowMeterInspectionReport->status !== 'new') {
            return false;
        }

        if($flowMeterInspectionReport->employee_id === $user->employee_id) {
            return $user->can('flow-meter-inspection-reports.get-signature.own');
        }

        return $user->can('flow-meter-inspection-reports.get-signature.other');
    }

    public function approve(User $user, FlowMeterInspectionReport $flowMeterInspectionReport): bool
    {
        return $user->can('flow-meter-inspection-reports.approve');
    }
}
