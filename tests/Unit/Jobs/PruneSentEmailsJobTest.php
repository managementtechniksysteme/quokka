<?php

namespace Tests\Unit\Jobs;

use App\Jobs\PruneSentEmailsJob;
use App\Models\ApplicationSettings;
use Spatie\Activitylog\Models\Activity;

function sentEmailActivity(\Carbon\Carbon $createdAt): Activity
{
    $activity = Activity::create([
        'log_name' => 'default',
        'description' => 'emailSent',
        'event' => 'emailSent',
        'properties' => ['subject' => 'Test'],
    ]);
    $activity->created_at = $createdAt;
    $activity->save();

    return $activity;
}

test('deletes sent-email activities older than a month when prune_sent_emails is enabled', function () {
    ApplicationSettings::get()->update(['prune_sent_emails' => true]);
    ApplicationSettings::refreshCache();

    $old = sentEmailActivity(now()->subMonths(2));
    $recent = sentEmailActivity(now()->subDays(2));

    (new PruneSentEmailsJob())->handle();

    $this->assertModelMissing($old);
    $this->assertModelExists($recent);
});

test('does nothing when prune_sent_emails is disabled', function () {
    ApplicationSettings::get()->update(['prune_sent_emails' => false]);
    ApplicationSettings::refreshCache();

    $old = sentEmailActivity(now()->subMonths(2));

    (new PruneSentEmailsJob())->handle();

    $this->assertModelExists($old);
});
