<?php

namespace Tests\Feature;

use App\Events\CommentCreatedEvent;
use App\Events\CommentUpdatedEvent;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Support\Facades\Event;

function commentUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownTask(User $user, array $attributes = []): Task
{
    return Task::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// create

test('create form is shown for a user who can view the task and create comments', function () {
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.create']);
    $task = ownTask($user);

    $response = $this->actingAs($user)->get(route('comments.create', ['task' => $task->id]));

    $response->assertSuccessful();
    $response->assertViewIs('comment.create');
});

test('create form is forbidden without comments.create permission', function () {
    $user = commentUser(['tasks.view.responsible']);
    $task = ownTask($user);

    $response = $this->actingAs($user)->get(route('comments.create', ['task' => $task->id]));

    $response->assertForbidden();
});

// store

test('store creates a comment on the task', function () {
    Event::fake([CommentCreatedEvent::class]);
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.create']);
    $task = ownTask($user);

    $response = $this->actingAs($user)->post(route('comments.store'), [
        'task_id' => $task->id,
        'comment' => 'A test comment',
    ]);

    $comment = TaskComment::sole();

    $response->assertRedirect(route('tasks.show', $task));
    expect($comment->comment)->toBe('A test comment');
    expect($comment->task_id)->toBe($task->id);
    expect($comment->employee_id)->toBe($user->employee_id);
    expect($comment->created_at->eq($comment->updated_at))->toBeTrue();
    Event::assertDispatched(CommentCreatedEvent::class);
});

test('store is forbidden without comments.create permission', function () {
    $user = commentUser(['tasks.view.responsible']);
    $task = ownTask($user);

    $response = $this->actingAs($user)->post(route('comments.store'), [
        'task_id' => $task->id,
        'comment' => 'A test comment',
    ]);

    $response->assertForbidden();
    expect(TaskComment::count())->toBe(0);
});

test('store is forbidden without view permission on the task', function () {
    $user = commentUser(['tasks.comments.create']);
    $task = Task::factory()->create();

    $response = $this->actingAs($user)->post(route('comments.store'), [
        'task_id' => $task->id,
        'comment' => 'A test comment',
    ]);

    $response->assertForbidden();
    expect(TaskComment::count())->toBe(0);
});

// edit

test('edit form is shown for the comment', function () {
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.update.own']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->get(route('comments.edit', $comment));

    $response->assertSuccessful();
    $response->assertViewIs('comment.edit');
});

// update

test('update persists changes to an own comment', function () {
    Event::fake([CommentUpdatedEvent::class]);
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.update.own']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->put(route('comments.update', $comment), [
        'task_id' => $task->id,
        'comment' => 'Updated comment',
    ]);

    $response->assertRedirect(route('tasks.show', $task));
    expect($comment->fresh()->comment)->toBe('Updated comment');
    Event::assertDispatched(CommentUpdatedEvent::class);
});

test('update is forbidden for an own comment without update.own permission', function () {
    $user = commentUser(['tasks.view.responsible']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->put(route('comments.update', $comment), [
        'task_id' => $task->id,
        'comment' => 'Updated comment',
    ]);

    $response->assertForbidden();
    expect($comment->fresh()->comment)->not->toBe('Updated comment');
});

test('update is forbidden for another employee\'s comment without update.other permission', function () {
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.update.own']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id]);

    $response = $this->actingAs($user)->put(route('comments.update', $comment), [
        'task_id' => $task->id,
        'comment' => 'Updated comment',
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes an own comment', function () {
    $user = commentUser(['tasks.view.responsible', 'tasks.comments.delete.own']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

    $response->assertRedirect(route('tasks.show', $task));
    expect(TaskComment::find($comment->id))->toBeNull();
});

test('destroy is forbidden for an own comment without delete.own permission', function () {
    $user = commentUser(['tasks.view.responsible']);
    $task = ownTask($user);
    $comment = TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

    $response->assertForbidden();
    expect(TaskComment::find($comment->id))->not->toBeNull();
});
