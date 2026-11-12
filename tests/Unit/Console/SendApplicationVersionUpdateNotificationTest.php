<?php

namespace Tests\Unit\Console;

use App\Models\User;
use App\Notifications\ApplicationVersionUpdateNotification;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushChannel;

test('notify:version sends the version update notification to every user', function () {
    Notification::fake();
    $first = User::factory()->create();
    $second = User::factory()->create();

    $this->artisan('notify:version')->assertExitCode(0);

    Notification::assertSentTo([$first, $second], ApplicationVersionUpdateNotification::class);
});

test('notify:version respects the users\' notification settings by default', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->artisan('notify:version')->assertExitCode(0);

    Notification::assertSentTo($user, ApplicationVersionUpdateNotification::class, fn ($notification) => ! $notification->shouldSend($user, MailChannel::class)
        && ! $notification->shouldSend($user, WebPushChannel::class)
        && $notification->shouldSend($user, DatabaseChannel::class));
});

test('notify:version --force sends via every channel regardless of the users\' notification settings', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->artisan('notify:version --force')->assertExitCode(0);

    Notification::assertSentTo($user, ApplicationVersionUpdateNotification::class, fn ($notification) => $notification->shouldSend($user, MailChannel::class)
        && $notification->shouldSend($user, WebPushChannel::class)
        && $notification->shouldSend($user, DatabaseChannel::class));
});
