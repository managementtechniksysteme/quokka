<?php

namespace Tests\Feature;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Mail\TaskMail;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;

function taskUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function responsibleTask(User $user, array $attributes = []): Task
{
    return Task::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function taskPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Test task',
        'priority' => 'medium',
        'status' => 'new',
        'billed' => 'no',
        'private' => 0,
        'comment' => 'Test comment',
    ], $overrides);
}

// index

test('index is shown for a user with a view permission', function () {
    $user = taskUser(['tasks.view.responsible']);

    $response = $this->actingAs($user)->get(route('tasks.index'));

    $response->assertSuccessful();
    $response->assertViewIs('task.index');
});

test('index is forbidden without any view permission', function () {
    $user = taskUser();

    $response = $this->actingAs($user)->get(route('tasks.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = taskUser(['tasks.create']);

    $response = $this->actingAs($user)->get(route('tasks.create'));

    $response->assertSuccessful();
    $response->assertViewIs('task.create');
});

test('store creates a task', function () {
    Event::fake([TaskCreatedEvent::class]);
    $user = taskUser(['tasks.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('tasks.store'), taskPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
    ]));

    $task = Task::sole();

    $response->assertRedirect(route('tasks.show', $task));
    expect($task->employee_id)->toBe($user->employee_id);
    expect($task->status)->toBe('new');
    Event::assertDispatched(TaskCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = taskUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('tasks.store'), taskPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
    ]));

    $response->assertForbidden();
    expect(Task::count())->toBe(0);
});

test('store creating a finished task sets ends_on automatically', function () {
    $user = taskUser(['tasks.create']);
    $project = Project::factory()->create();

    $this->actingAs($user)->post(route('tasks.store'), taskPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
        'status' => 'finished',
    ]));

    $task = Task::sole();

    expect($task->ends_on)->not->toBeNull();
});

// show

test('show is allowed for a responsible task with view.responsible permission', function () {
    $user = taskUser(['tasks.view.responsible']);
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->get(route('tasks.show', $task));

    $response->assertSuccessful();
    $response->assertViewIs('task.show');
});

test('show is forbidden for another task without view.other permission', function () {
    $user = taskUser(['tasks.view.responsible']);
    $task = Task::factory()->create();

    $response = $this->actingAs($user)->get(route('tasks.show', $task));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = taskUser(['tasks.update.responsible']);
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->get(route('tasks.edit', $task));

    $response->assertSuccessful();
    $response->assertViewIs('task.edit');
});

// update

test('update persists changes to a task', function () {
    Event::fake([TaskUpdatedEvent::class]);
    $user = taskUser(['tasks.update.responsible']);
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->put(route('tasks.update', $task), taskPayload([
        'project_id' => $task->project_id,
        'employee_id' => $task->employee_id,
        'name' => 'Updated name',
    ]));

    $response->assertRedirect(route('tasks.show', $task));
    expect($task->fresh()->name)->toBe('Updated name');
    Event::assertDispatched(TaskUpdatedEvent::class);
});

test('update is forbidden without matching permission', function () {
    $user = taskUser();
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->put(route('tasks.update', $task), taskPayload([
        'project_id' => $task->project_id,
        'employee_id' => $task->employee_id,
        'name' => 'Updated name',
    ]));

    $response->assertForbidden();
});

test('update marking a task finished sets ends_on automatically', function () {
    $user = taskUser(['tasks.update.responsible']);
    $task = responsibleTask($user, ['status' => 'new']);

    $this->actingAs($user)->put(route('tasks.update', $task), taskPayload([
        'project_id' => $task->project_id,
        'employee_id' => $task->employee_id,
        'status' => 'finished',
    ]));

    expect($task->fresh()->ends_on)->not->toBeNull();
});

// destroy

test('destroy removes a task', function () {
    $user = taskUser(['tasks.delete.responsible']);
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

    $response->assertRedirect(route('tasks.index'));
    expect(Task::find($task->id))->toBeNull();
});

// finish

test('finish is allowed with update permission', function () {
    $user = taskUser(['tasks.update.responsible']);
    $task = responsibleTask($user, ['status' => 'new']);

    $response = $this->actingAs($user)->get(route('tasks.finish', $task));

    $response->assertRedirect();
    expect($task->fresh()->status)->toBe('finished');
    expect($task->fresh()->ends_on)->not->toBeNull();
});

test('finish is forbidden without update permission', function () {
    $user = taskUser(['tasks.view.responsible', 'tasks.delete.responsible']);
    $task = responsibleTask($user, ['status' => 'new']);

    $response = $this->actingAs($user)->get(route('tasks.finish', $task));

    $response->assertForbidden();
    expect($task->fresh()->status)->toBe('new');
});

// activity log regression

test('finishing a task writes an activity log entry with the new attribute_changes shape', function () {
    $user = taskUser(['tasks.update.responsible']);
    $task = responsibleTask($user, ['status' => 'new']);

    $this->actingAs($user)->get(route('tasks.finish', $task));

    $activity = Activity::where('subject_type', Task::class)
        ->where('subject_id', $task->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('new');
});

// batching regression: LogBatch was removed entirely in spatie/laravel-activitylog v5
// (TaskController::update() used to call LogBatch::startBatch()/endBatch(), which no
// longer exist - fixed by watermarking Activity::max('id') and stamping everything
// created since with a shared batch_uuid). Verify a save touching both a plain field
// and involved employees still produces one merged feed entry, not two separate ones.
test('updating multiple things in one save merges into a single activity feed entry', function () {
    $user = taskUser(['tasks.update.responsible', 'tasks.view.responsible']);
    $task = responsibleTask($user);
    $involved = Employee::factory()->create();

    $this->actingAs($user)->put(route('tasks.update', $task), taskPayload([
        'project_id' => $task->project_id,
        'employee_id' => $task->employee_id,
        'name' => 'Updated name',
        'involved_ids' => [$involved->person_id],
    ]));

    $activities = Activity::where('subject_type', Task::class)->where('subject_id', $task->id)->get();

    // both the automatic field-change log and the manual updatedInvolvedEmployees log fired...
    expect($activities->count())->toBeGreaterThanOrEqual(2);
    // ...but share one batch_uuid, exactly like LogBatch used to guarantee.
    expect($activities->pluck('batch_uuid')->unique())->toHaveCount(1);
    expect($activities->first()->batch_uuid)->not->toBeNull();

    $response = $this->actingAs($user)->get(route('tasks.show', $task));
    $feedActivities = $response->viewData('activities')->getCollection()
        ->filter(fn ($item) => $item instanceof Activity);

    expect($feedActivities)->toHaveCount(1);
    expect($feedActivities->first()->attribute_changes['attributes'])->toHaveKeys(['name', 'involved_ids']);
});

// email

test('email sends the task mail', function () {
    Mail::fake();
    $user = taskUser(['tasks.email.responsible']);
    $task = responsibleTask($user);

    $this->actingAs($user)->post(route('tasks.email', $task), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(TaskMail::class);
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    $user = taskUser(['tasks.createpdf.responsible']);
    $task = responsibleTask($user);

    $response = $this->actingAs($user)->get(route('tasks.download', $task));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList renders a real pdf for an authorized user', function () {
    $user = taskUser(['tasks.view.responsible']);
    responsibleTask($user);

    $response = $this->actingAs($user)->get(route('tasks.download-list'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList is forbidden without view permission', function () {
    $user = taskUser();

    $response = $this->actingAs($user)->get(route('tasks.download-list'));

    $response->assertForbidden();
});

// involved employees

test('store attaches involved employees, excluding the responsible one if present', function () {
    $user = taskUser(['tasks.create']);
    $project = Project::factory()->create();
    $involved = Employee::factory()->create();

    $this->actingAs($user)->post(route('tasks.store'), taskPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
        'involved_ids' => [$user->employee_id, $involved->person_id],
    ]));

    $task = Task::sole();

    expect($task->involvedEmployees()->count())->toBe(1);
    expect($task->involvedEmployees()->first()->person_id)->toBe($involved->person_id);
});
