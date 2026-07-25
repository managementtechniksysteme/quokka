<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskInvolvedNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

function notificationFor(User $user, array $overrides = []): DatabaseNotification
{
    $task = Task::factory()->create();

    return DatabaseNotification::create(array_merge([
        'id' => (string) Str::uuid(),
        'type' => TaskInvolvedNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $user->employee_id,
        'data' => ['id' => $task->id, 'created' => true, 'route' => route('tasks.show', $task)],
        'read_at' => null,
    ], $overrides));
}

test('index is shown for an authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('notifications.index'));

    $response->assertSuccessful();
    $response->assertViewIs('notification.index');
});

test('index shows only unread notifications by default', function () {
    $user = User::factory()->create();
    $unread = notificationFor($user);
    $read = notificationFor($user, ['read_at' => now()]);

    $response = $this->actingAs($user)->get(route('notifications.index'));

    $response->assertViewHas('notifications', function ($notifications) use ($unread, $read) {
        $ids = $notifications->pluck('id');

        return $ids->contains($unread->id) && ! $ids->contains($read->id);
    });
});

test('index with show-read includes read notifications too', function () {
    $user = User::factory()->create();
    $unread = notificationFor($user);
    $read = notificationFor($user, ['read_at' => now()]);

    $response = $this->actingAs($user)->get(route('notifications.index', ['show-read' => true]));

    $response->assertViewHas('notifications', function ($notifications) use ($unread, $read) {
        $ids = $notifications->pluck('id');

        return $ids->contains($unread->id) && $ids->contains($read->id);
    });
});

test('index only shows the acting user\'s own notifications', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $mine = notificationFor($user);
    notificationFor($other);

    $response = $this->actingAs($user)->get(route('notifications.index', ['show-read' => true]));

    $response->assertViewHas('notifications', function ($notifications) use ($mine) {
        return $notifications->pluck('id')->all() === [$mine->id];
    });
});

test('destroy marks your own notification as read', function () {
    $user = User::factory()->create();
    $notification = notificationFor($user);

    $response = $this->actingAs($user)->delete(route('notifications.destroy', $notification));

    $response->assertRedirect(route('notifications.index'));
    expect($notification->fresh()->read_at)->not->toBeNull();
    $this->assertModelExists($notification);
});

test('destroy is forbidden for someone else\'s notification', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $notification = notificationFor($other);

    $response = $this->actingAs($user)->delete(route('notifications.destroy', $notification));

    $response->assertForbidden();
    expect($notification->fresh()->read_at)->toBeNull();
});

test('clear marks all of the acting user\'s unread notifications as read', function () {
    $user = User::factory()->create();
    $first = notificationFor($user);
    $second = notificationFor($user);

    $response = $this->actingAs($user)->post(route('notifications.clear'));

    $response->assertRedirect(route('notifications.index'));
    expect($first->fresh()->read_at)->not->toBeNull();
    expect($second->fresh()->read_at)->not->toBeNull();
});

test('clear does not touch other users\' notifications', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersNotification = notificationFor($other);

    $this->actingAs($user)->post(route('notifications.clear'));

    expect($othersNotification->fresh()->read_at)->toBeNull();
});
