<?php

namespace Tests\Feature;

use App\Events\DeliveryNoteSignedEvent;
use App\Events\TaskCreatedEvent;
use App\Models\DeliveryNote;
use App\Models\Task;
use App\Models\User;
use App\Notifications\DeliveryNoteSignedNotification;
use App\Notifications\TaskInvolvedNotification;
use App\Notifications\TaskMentionNotification;
use Illuminate\Support\Facades\Notification;

// Regression test for a bug where every event-driven notification was sent
// twice: listeners were registered both manually in AppServiceProvider and
// (redundantly) via Laravel's automatic event discovery. See
// App\Providers\AppServiceProvider::configureEvents().

test('a subscriber-style listener (Event::subscribe) fires exactly once per event', function () {
    Notification::fake();

    $responsible = User::factory()->create();
    $causer = User::factory()->create();
    $task = Task::factory()->create(['employee_id' => $responsible->employee_id, 'comment' => 'no mentions here']);

    event(new TaskCreatedEvent($task, $causer, false));

    Notification::assertSentToTimes($responsible, TaskInvolvedNotification::class, 1);
    Notification::assertNothingSentTo($causer);
});

test('a plain Event::listen listener fires exactly once per event', function () {
    Notification::fake();

    $employee = User::factory()->create();
    $deliveryNote = DeliveryNote::factory()->create(['employee_id' => $employee->employee_id]);

    event(new DeliveryNoteSignedEvent($deliveryNote));

    Notification::assertSentToTimes($employee, DeliveryNoteSignedNotification::class, 1);
});

test('every registered listener for a given event is bound exactly once', function () {
    $listeners = collect(app('events')->getRawListeners()[TaskCreatedEvent::class] ?? []);

    expect($listeners->unique()->count())->toBe($listeners->count());
});
