<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('notes.view');
    }

    public function view(User $user, Note $note): bool
    {
        return $user->can('notes.view') && $note->employee_id == $user->employee_id;
    }

    public function create(User $user): bool
    {
        return $user->can('notes.create');
    }

    public function update(User $user, Note $note): bool
    {
        return $user->can('notes.update') && $note->employee_id == $user->employee_id;
    }

    public function delete(User $user, Note $note): bool
    {
        return $user->can('notes.delete') && $note->employee_id == $user->employee_id;
    }

    public function email(User $user, Note $note): bool
    {
        return $user->can('notes.email') && $note->employee_id == $user->employee_id;
    }

    public function createPdf(User $user, Note $note): bool
    {
        return $user->can('notes.createpdf') && $note->employee_id == $user->employee_id;
    }

    public function downloadList(User $user): bool
    {
        return $user->can('notes.view');
    }
}
