<?php

namespace Tests\Unit\Policies;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskInvolvedNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

function ownNotification(User $user): DatabaseNotification
{
    $task = Task::factory()->create();

    return DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => TaskInvolvedNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $user->employee_id,
        'data' => ['id' => $task->id, 'created' => true, 'route' => route('tasks.show', $task)],
        'read_at' => null,
    ]);
}

test('delete is allowed for your own notification', function () {
    $user = User::factory()->create();

    expect($user->can('delete', ownNotification($user)))->toBeTrue();
});

test('delete is denied for someone else\'s notification', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect($user->can('delete', ownNotification($other)))->toBeFalse();
});
