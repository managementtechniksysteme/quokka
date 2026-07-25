<?php

namespace Tests\Unit\Traits;

use App\Models\NotificationType;
use App\Models\User;
use App\Traits\TargetsNotification;
use Illuminate\Notifications\Channels\MailChannel;
use NotificationChannels\WebPush\WebPushChannel;

class FakeTargetsNotification
{
    use TargetsNotification;
}

function targetsNotificationType(): NotificationType
{
    return NotificationType::factory()->create(['type' => FakeTargetsNotification::class]);
}

function grantChannel(User $user, NotificationType $type, string $pivotType): void
{
    $relation = $pivotType === 'email' ? $user->notificationsViaEmail() : $user->notificationsViaWebPush();

    $relation->attach($type->id, ['notification_target_type' => $pivotType]);
}

test('shouldSend is true when no causer is set, regardless of channel opt-in', function () {
    $notification = new FakeTargetsNotification();
    $user = User::factory()->create();

    expect($notification->shouldSend($user, 'database'))->toBeTrue();
});

test('shouldSend is true for a non-User notifiable, regardless of causer/channel state', function () {
    $notification = new FakeTargetsNotification();
    $notification->user = User::factory()->create();
    $notification->notifySelf = false;

    expect($notification->shouldSend('not-a-user', 'database'))->toBeTrue();
});

test('shouldSend respects notifySelf when the notifiable is the causer, for every channel', function () {
    $user = User::factory()->create();

    $notification = new FakeTargetsNotification();
    $notification->user = $user;
    $notification->notifySelf = false;

    expect($notification->shouldSend($user, 'database'))->toBeFalse()
        ->and($notification->shouldSend($user, MailChannel::class))->toBeFalse()
        ->and($notification->shouldSend($user, WebPushChannel::class))->toBeFalse();
});

test('shouldSend allows notifying the causer when notifySelf is true', function () {
    $user = User::factory()->create();

    $notification = new FakeTargetsNotification();
    $notification->user = $user;
    $notification->notifySelf = true;

    expect($notification->shouldSend($user, 'database'))->toBeTrue();
});

test('shouldSend does not apply notifySelf to a different notifiable', function () {
    $causer = User::factory()->create();
    $otherUser = User::factory()->create();

    $notification = new FakeTargetsNotification();
    $notification->user = $causer;
    $notification->notifySelf = false;

    expect($notification->shouldSend($otherUser, 'database'))->toBeTrue();
});

test('mail channel is sent only when the user opted in for this notification type', function () {
    $optedIn = User::factory()->create();
    $optedOut = User::factory()->create();
    $type = targetsNotificationType();
    grantChannel($optedIn, $type, 'email');

    $notification = new FakeTargetsNotification();

    expect($notification->shouldSend($optedIn, MailChannel::class))->toBeTrue()
        ->and($notification->shouldSend($optedOut, MailChannel::class))->toBeFalse();
});

test('webpush channel is sent only when the user opted in for this notification type', function () {
    $optedIn = User::factory()->create();
    $optedOut = User::factory()->create();
    $type = targetsNotificationType();
    grantChannel($optedIn, $type, 'webpush');

    $notification = new FakeTargetsNotification();

    expect($notification->shouldSend($optedIn, WebPushChannel::class))->toBeTrue()
        ->and($notification->shouldSend($optedOut, WebPushChannel::class))->toBeFalse();
});

test('mail opt-in for one notification type does not grant webpush, and vice versa', function () {
    $user = User::factory()->create();
    $type = targetsNotificationType();
    grantChannel($user, $type, 'email');

    $notification = new FakeTargetsNotification();

    expect($notification->shouldSend($user, MailChannel::class))->toBeTrue()
        ->and($notification->shouldSend($user, WebPushChannel::class))->toBeFalse();
});

test('database channel always fires regardless of mail/webpush opt-in state', function () {
    $optedOut = User::factory()->create();

    $notification = new FakeTargetsNotification();

    expect($notification->shouldSend($optedOut, 'database'))->toBeTrue();
});

test('opt-in for a different notification type does not grant this one', function () {
    $user = User::factory()->create();
    $otherType = NotificationType::factory()->create(['type' => 'App\Notifications\SomeOtherNotification']);
    grantChannel($user, $otherType, 'email');

    $notification = new FakeTargetsNotification();

    expect($notification->shouldSend($user, MailChannel::class))->toBeFalse();
});
