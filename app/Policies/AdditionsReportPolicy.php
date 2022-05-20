<?php

namespace App\Policies;

use App\Models\AdditionsReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdditionsReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('additions-reports.view.own') ||
            $user->can('additions-reports.view.involved') ||
            $user->can('additions-reports.view.other');
    }

    public function view(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.view.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.view.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.view.other');
        }

        return $own || $involved || $other;
    }

    public function create(User $user): bool
    {
        return $user->can('additions-reports.create');
    }

    public function update(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->status === 'finished') {
            return false;
        }

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.update.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.update.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.update.other');
        }

        return $own || $involved || $other;
    }

    public function delete(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->status !== 'new') {
            return false;
        }

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.delete.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.delete.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.delete.other');
        }

        return $own || $involved || $other;
    }

    public function email(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.email.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.email.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.email.other');
        }

        return $own || $involved || $other;
    }

    public function createPdf(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.createpdf.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.createpdf.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.createpdf.other');
        }

        return $own || $involved || $other;
    }

    public function emailSignatureRequest(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->status !== 'new') {
            return false;
        }

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.send-signature-request.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.send-signature-request.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.send-signature-request.other');
        }

        return $own || $involved || $other;
    }

    public function emailDownloadRequest(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->status === 'new') {
            return false;
        }

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.send-download-request.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.send-download-request.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.send-download-request.other');
        }

        return $own || $involved || $other;
    }

    public function sign(User $user, AdditionsReport $additionsReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($additionsReport->status !== 'new') {
            return false;
        }

        if($additionsReport->employee->person_id === $user->employee_id) {
            $own = $user->can('additions-reports.get-signature.own');
        }

        if($additionsReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('additions-reports.get-signature.involved');
        }

        if($additionsReport->employee->person_id !== $user->employee_id &&
            $additionsReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('additions-reports.get-signature.other');
        }

        return $own || $involved || $other;
    }

    public function approve(User $user, AdditionsReport $additionsReport): bool
    {
        return $user->can('additions-reports.approve');
    }
}
