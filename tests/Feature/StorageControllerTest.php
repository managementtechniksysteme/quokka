<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Http\UploadedFile;

function storageUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function storagePathFor($media): string
{
    return $media->getPathRelativeToRoot();
}

test('an attachment is served for a user who can view its owning record', function () {
    $user = storageUser(['tasks.view.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);
    $comment = TaskComment::factory()->create(['task_id' => $task->id]);
    $comment->addMedia(UploadedFile::fake()->create('note.pdf', 10, 'application/pdf'))->toMediaCollection('attachments');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($comment->attachments()->first()));

    $response->assertSuccessful();
});

test('an attachment is forbidden for a user who cannot view its owning record', function () {
    $user = storageUser(['tasks.view.responsible']);
    $comment = TaskComment::factory()->create();
    $comment->addMedia(UploadedFile::fake()->create('note.pdf', 10, 'application/pdf'))->toMediaCollection('attachments');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($comment->attachments()->first()));

    $response->assertForbidden();
});

test('a user can view their own signature', function () {
    $user = storageUser();
    $user->addMedia(UploadedFile::fake()->image('signature.png'))->toMediaCollection('signature');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($user->signature()));

    $response->assertSuccessful();
});

test('a user cannot view another user\'s signature', function () {
    $user = storageUser();
    $other = User::factory()->create();
    $other->addMedia(UploadedFile::fake()->image('signature.png'))->toMediaCollection('signature');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($other->signature()));

    $response->assertForbidden();
});

test('a flow meter inspection report attachment is served for a user who can view the report', function () {
    $user = storageUser(['flow-meter-inspection-reports.view.own']);
    $report = \App\Models\FlowMeterInspectionReport::factory()->create(['employee_id' => $user->employee_id]);
    $report->addMedia(UploadedFile::fake()->create('reading.pdf', 10, 'application/pdf'))->toMediaCollection('attachments');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($report->attachments()->first()));

    $response->assertSuccessful();
});

test('a flow meter inspection report attachment is forbidden without view on the report', function () {
    $user = storageUser(['flow-meter-inspection-reports.view.own']);
    $report = \App\Models\FlowMeterInspectionReport::factory()->create();
    $report->addMedia(UploadedFile::fake()->create('reading.pdf', 10, 'application/pdf'))->toMediaCollection('attachments');

    $response = $this->actingAs($user)->get('/storage/'.storagePathFor($report->attachments()->first()));

    $response->assertForbidden();
});

test('a nonexistent file returns 404', function () {
    $user = storageUser();

    $response = $this->actingAs($user)->get('/storage/999999/does-not-exist.pdf');

    $response->assertNotFound();
});
