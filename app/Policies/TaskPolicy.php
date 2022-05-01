<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('tasks.view.responsible') ||
            $user->can('tasks.view.involved') ||
            $user->can('tasks.view.other') ||
            $user->can('tasks.view.private.responsible') ||
            $user->can('tasks.view.private.involved') ||
            $user->can('tasks.view.private.other');
    }

    public function view(User $user, Task $task): bool
    {
        $responsible = false;
        $involved = false;
        $other = false;
        $privateResponsible = false;
        $privateInvolved = false;
        $privateOther = false;

        if(!$task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $responsible = $user->can('tasks.view.responsible');
        }

        if(!$task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('tasks.view.involved');
        }

        if(!$task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('tasks.view.other');
        }

        if($task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $privateResponsible = $user->can('tasks.view.private.responsible');
        }

        if($task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $privateInvolved = $user->can('tasks.view.private.involved');
        }

        if($task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $privateOther = $user->can('tasks.view.private.other');
        }

        return $responsible || $involved || $other || $privateResponsible || $privateInvolved || $privateOther;
    }

    public function create(User $user): bool
    {
        return $user->can('tasks.create') || $user->can('tasks.create.private');
    }

    public function update(User $user, Task $task): bool
    {
        $responsible = false;
        $involved = false;
        $other = false;
        $privateResponsible = false;
        $privateInvolved = false;
        $privateOther = false;

        if(!$task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $responsible = $user->can('tasks.update.responsible');
        }

        if(!$task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('tasks.update.involved');
        }

        if(!$task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('tasks.update.other');
        }

        if($task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $privateResponsible = $user->can('tasks.update.private.responsible');
        }

        if($task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $privateInvolved = $user->can('tasks.update.private.involved');
        }

        if($task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $privateOther = $user->can('tasks.update.private.other');
        }

        return $responsible || $involved || $other || $privateResponsible || $privateInvolved || $privateOther;
    }

    public function delete(User $user, Task $task): bool
    {
        $responsible = false;
        $involved = false;
        $other = false;
        $privateResponsible = false;
        $privateInvolved = false;
        $privateOther = false;

        if(!$task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $responsible = $user->can('tasks.delete.responsible');
        }

        if(!$task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('tasks.delete.involved');
        }

        if(!$task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('tasks.delete.other');
        }

        if($task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $privateResponsible = $user->can('tasks.delete.private.responsible');
        }

        if($task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $privateInvolved = $user->can('tasks.delete.private.involved');
        }

        if($task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $privateOther = $user->can('tasks.delete.private.other');
        }

        return $responsible || $involved || $other || $privateResponsible || $privateInvolved || $privateOther;
    }

    public function email(User $user, Task $task): bool
    {
        $responsible = false;
        $involved = false;
        $other = false;
        $privateResponsible = false;
        $privateInvolved = false;
        $privateOther = false;

        if(!$task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $responsible = $user->can('tasks.email.responsible');
        }

        if(!$task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('tasks.email.involved');
        }

        if(!$task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('tasks.email.other');
        }

        if($task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $privateResponsible = $user->can('tasks.email.private.responsible');
        }

        if($task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $privateInvolved = $user->can('tasks.email.private.involved');
        }

        if($task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $privateOther = $user->can('tasks.email.private.other');
        }

        return $responsible || $involved || $other || $privateResponsible || $privateInvolved || $privateOther;
    }

    public function createPdf(User $user, Task $task): bool
    {
        $responsible = false;
        $involved = false;
        $other = false;
        $privateResponsible = false;
        $privateInvolved = false;
        $privateOther = false;

        if(!$task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $responsible = $user->can('tasks.createpdf.responsible');
        }

        if(!$task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $involved = $user->can('tasks.createpdf.involved');
        }

        if(!$task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $other = $user->can('tasks.createpdf.other');
        }

        if($task->private && $task->responsibleEmployee->person_id === $user->employee_id) {
            $privateResponsible = $user->can('tasks.createpdf.private.responsible');
        }

        if($task->private && $task->involvedEmployees->contains($user->employee_id)) {
            $privateInvolved = $user->can('tasks.createpdf.private.involved');
        }

        if($task->private &&
            $task->responsibleEmployee->person_id !== $user->employee_id &&
            $task->involvedEmployees->doesntContain($user->employee_id)) {
            $privateOther = $user->can('tasks.createpdf.private.other');
        }

        return $responsible || $involved || $other || $privateResponsible || $privateInvolved || $privateOther;
    }
}
