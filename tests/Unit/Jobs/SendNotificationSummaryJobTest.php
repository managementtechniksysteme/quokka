<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendNotificationSummaryJob;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NotificationSummaryNotification;
use App\Notifications\TaskInvolvedNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

function notificationOn(User $user, \Carbon\Carbon $createdAt, ?\Carbon\Carbon $readAt = null): DatabaseNotification
{
    $task = Task::factory()->create();

    $notification = DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => TaskInvolvedNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $user->employee_id,
        'data' => ['id' => $task->id, 'created' => true, 'route' => route('tasks.show', $task)],
        'read_at' => $readAt,
    ]);
    $notification->created_at = $createdAt;
    $notification->save();

    return $notification;
}

test('sends a summary to users with unread notifications from yesterday', function () {
    Notification::fake();
    $user = User::factory()->create();
    notificationOn($user, now()->yesterday());

    (new SendNotificationSummaryJob())->handle();

    Notification::assertSentTo($user, NotificationSummaryNotification::class);
});

test('does not send a summary to a user with only read notifications from yesterday', function () {
    Notification::fake();
    $user = User::factory()->create();
    notificationOn($user, now()->yesterday(), now());

    (new SendNotificationSummaryJob())->handle();

    Notification::assertNotSentTo($user, NotificationSummaryNotification::class);
});

test('does not send a summary to a user with unread notifications from today', function () {
    Notification::fake();
    $user = User::factory()->create();
    notificationOn($user, now());

    (new SendNotificationSummaryJob())->handle();

    Notification::assertNotSentTo($user, NotificationSummaryNotification::class);
});
