<?php

namespace App\Policies;

use App\Models\ConstructionReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstructionReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('construction-reports.view.own') ||
            $user->can('construction-reports.view.involved') ||
            $user->can('construction-reports.view.other');
    }

    public function view(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.view.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.view.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.view.other');
        }

        return $own || $involved || $other;
    }

    public function create(User $user): bool
    {
        return $user->can('construction-reports.create');
    }

    public function update(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->status === 'finished') {
            return false;
        }

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.update.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.update.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.update.other');
        }

        return $own || $involved || $other;
    }

    public function delete(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->status === 'finished') {
            return false;
        }

        if($constructionReport->status === 'signed') {
            return $user->can('construction-reports.approve');
        }

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.delete.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.delete.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.delete.other');
        }

        return $own || $involved || $other;
    }

    public function email(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.email.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.email.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.email.other');
        }

        return $own || $involved || $other;
    }

    public function createPdf(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.createpdf.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.createpdf.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.createpdf.other');
        }

        return $own || $involved || $other;
    }

    public function emailSignatureRequest(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->status !== 'new') {
            return false;
        }

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.send-signature-request.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.send-signature-request.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.send-signature-request.other');
        }

        return $own || $involved || $other;
    }

    public function emailDownloadRequest(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->status === 'new') {
            return false;
        }

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.send-download-request.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.send-download-request.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.send-download-request.other');
        }

        return $own || $involved || $other;
    }

    public function sign(User $user, ConstructionReport $constructionReport): bool
    {
        $own = false;
        $involved = false;
        $other = false;

        if($constructionReport->status !== 'new') {
            return false;
        }

        if($constructionReport->employee->person_id === $user->employee_id) {
            $own = $user->can('construction-reports.get-signature.own');
        }

        if($constructionReport->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('construction-reports.get-signature.involved');
        }

        if($constructionReport->employee->person_id !== $user->employee_id &&
            $constructionReport->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('construction-reports.get-signature.other');
        }

        return $own || $involved || $other;
    }

    public function approve(User $user, ConstructionReport $constructionReport): bool
    {
        return $user->can('construction-reports.approve');
    }
}
