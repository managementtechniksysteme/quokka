<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskCommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Task $task): bool
    {
        return $user->can('view', $task);
    }

    public function view(User $user, TaskComment $comment): bool
    {
        return $user->can('view', $comment->task);
    }

    public function create(User $user, Task $task): bool
    {
        return $user->can('view', $task) && $user->can('tasks.comments.create');
    }

    public function update(User $user, TaskComment $comment): bool
    {

        if($comment->employee_id === $user->employee_id) {
            return $user->can('view', $comment->task) && $user->can('tasks.comments.update.own');
        }

        return $user->can('view', $comment->task) && $user->can('tasks.comments.update.other');
    }

    public function delete(User $user, TaskComment $comment): bool
    {
        if($comment->employee_id === $user->employee_id) {
            return $user->can('view', $comment->task) && $user->can('tasks.comments.delete.own');
        }

        return $user->can('view', $comment->task) && $user->can('tasks.comments.delete.other');
    }
}
