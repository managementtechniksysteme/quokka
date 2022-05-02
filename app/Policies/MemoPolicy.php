<?php

namespace App\Policies;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('memos.view.sender') ||
            $user->can('memos.view.recipient') ||
            $user->can('memos.view.present') ||
            $user->can('memos.view.notified') ||
            $user->can('memos.view.other');
    }

    public function view(User $user, Memo $memo): bool
    {
        $sender = false;
        $recipient = false;
        $present = false;
        $notified = false;
        $other = false;

        if($memo->employeeComposer->person_id === $user->employee_id) {
            $sender = $user->can('memos.view.sender');
        }

        if($memo->personRecipient && $memo->personRecipient->id === $user->employee_id) {
            $recipient = $user->can('memos.view.recipient');
        }

        if($memo->presentPeople->contains($user->employee_id)) {
            $present = $user->can('memos.view.present');
        }

        if($memo->notifiedPeople->contains($user->employee_id)) {
            $notified = $user->can('memos.view.notified');
        }

        if($memo->employeeComposer->person_id !== $user->employee_id &&
            ($memo->personRecipient && $memo->personRecipient->id !== $user->employee_id) &&
            $memo->presentPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id)) {
            $other = $user->can('memos.view.other');
        }

        return $sender || $recipient || $present || $notified || $other;
    }

    public function create(User $user): bool
    {
        return $user->can('memos.create');
    }

    public function update(User $user, Memo $memo): bool
    {
        $sender = false;
        $recipient = false;
        $present = false;
        $notified = false;
        $other = false;

        if($memo->employeeComposer->person_id === $user->employee_id) {
            $sender = $user->can('memos.update.sender');
        }

        if($memo->personRecipient && $memo->personRecipient->id === $user->employee_id) {
            $recipient = $user->can('memos.update.recipient');
        }

        if($memo->presentPeople->contains($user->employee_id)) {
            $present = $user->can('memos.update.present');
        }

        if($memo->notifiedPeople->contains($user->employee_id)) {
            $notified = $user->can('memos.update.notified');
        }

        if($memo->employeeComposer->person_id !== $user->employee_id &&
            ($memo->personRecipient && $memo->personRecipient->id !== $user->employee_id) &&
            $memo->presentPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id)) {
            $other = $user->can('memos.update.other');
        }

        return $sender || $recipient || $present || $notified || $other;
    }

    public function delete(User $user, Memo $memo): bool
    {
        $sender = false;
        $recipient = false;
        $present = false;
        $notified = false;
        $other = false;

        if($memo->employeeComposer->person_id === $user->employee_id) {
            $sender = $user->can('memos.delete.sender');
        }

        if($memo->personRecipient && $memo->personRecipient->id === $user->employee_id) {
            $recipient = $user->can('memos.delete.recipient');
        }

        if($memo->presentPeople->contains($user->employee_id)) {
            $present = $user->can('memos.delete.present');
        }

        if($memo->notifiedPeople->contains($user->employee_id)) {
            $notified = $user->can('memos.delete.notified');
        }

        if($memo->employeeComposer->person_id !== $user->employee_id &&
            ($memo->personRecipient && $memo->personRecipient->id !== $user->employee_id) &&
            $memo->presentPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id)) {
            $other = $user->can('memos.delete.other');
        }

        return $sender || $recipient || $present || $notified || $other;
    }

    public function email(User $user, Memo $memo): bool
    {
        $sender = false;
        $recipient = false;
        $present = false;
        $notified = false;
        $other = false;

        if($memo->employeeComposer->person_id === $user->employee_id) {
            $sender = $user->can('memos.email.sender');
        }

        if($memo->personRecipient && $memo->personRecipient->id === $user->employee_id) {
            $recipient = $user->can('memos.email.recipient');
        }

        if($memo->presentPeople->contains($user->employee_id)) {
            $present = $user->can('memos.email.present');
        }

        if($memo->notifiedPeople->contains($user->employee_id)) {
            $notified = $user->can('memos.email.notified');
        }

        if($memo->employeeComposer->person_id !== $user->employee_id &&
            ($memo->personRecipient && $memo->personRecipient->id !== $user->employee_id) &&
            $memo->presentPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id)) {
            $other = $user->can('memos.email.other');
        }

        return $sender || $recipient || $present || $notified || $other;
    }

    public function createPdf(User $user, Memo $memo): bool
    {
        $sender = false;
        $recipient = false;
        $present = false;
        $notified = false;
        $other = false;

        if($memo->employeeComposer->person_id === $user->employee_id) {
            $sender = $user->can('memos.createpdf.sender');
        }

        if($memo->personRecipient && $memo->personRecipient->id === $user->employee_id) {
            $recipient = $user->can('memos.createpdf.recipient');
        }

        if($memo->presentPeople->contains($user->employee_id)) {
            $present = $user->can('memos.createpdf.present');
        }

        if($memo->notifiedPeople->contains($user->employee_id)) {
            $notified = $user->can('memos.createpdf.notified');
        }

        if($memo->employeeComposer->person_id !== $user->employee_id &&
            ($memo->personRecipient && $memo->personRecipient->id !== $user->employee_id) &&
            $memo->presentPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id) &&
            $memo->notifiedPeople->doesntContain($user->employee_id)) {
            $other = $user->can('memos.createpdf.other');
        }

        return $sender || $recipient || $present || $notified || $other;
    }
}
