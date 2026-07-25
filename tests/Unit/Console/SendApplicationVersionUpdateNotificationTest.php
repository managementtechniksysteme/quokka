<?php

namespace Tests\Unit\Console;

use App\Models\User;
use App\Notifications\ApplicationVersionUpdateNotification;
use Illuminate\Support\Facades\Notification;

test('notify:version sends the version update notification to every user', function () {
    Notification::fake();
    $first = User::factory()->create();
    $second = User::factory()->create();

    $this->artisan('notify:version')->assertExitCode(0);

    Notification::assertSentTo([$first, $second], ApplicationVersionUpdateNotification::class);
});
