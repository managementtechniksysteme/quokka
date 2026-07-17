<?php

namespace Tests\Feature;

use App\Events\MemoCreatedEvent;
use App\Events\MemoUpdatedEvent;
use App\Mail\MemoMail;
use App\Models\Memo;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

function memoUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function senderMemo(User $user, array $attributes = []): Memo
{
    return Memo::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function memoPayload(array $overrides = []): array
{
    return array_merge([
        'draft' => 0,
        'title' => 'Test memo',
        'meeting_held_on' => '2026-01-01',
        'comment' => 'Test comment',
    ], $overrides);
}

// index

test('index is shown for a user with a view permission', function () {
    $user = memoUser(['memos.view.sender']);

    $response = $this->actingAs($user)->get(route('memos.index'));

    $response->assertSuccessful();
    $response->assertViewIs('memo.index');
});

test('index is forbidden without any view permission', function () {
    $user = memoUser();

    $response = $this->actingAs($user)->get(route('memos.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = memoUser(['memos.create']);

    $response = $this->actingAs($user)->get(route('memos.create'));

    $response->assertSuccessful();
    $response->assertViewIs('memo.create');
});

test('store creates a published memo and dispatches the created event', function () {
    Event::fake([MemoCreatedEvent::class]);
    $user = memoUser(['memos.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('memos.store'), memoPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
    ]));

    $memo = Memo::sole();

    $response->assertRedirect(route('memos.show', $memo));
    expect($memo->employee_id)->toBe($user->employee_id);
    expect($memo->number)->toBe(1);
    Event::assertDispatched(MemoCreatedEvent::class);
});

test('store creating a draft memo does not dispatch the created event', function () {
    Event::fake([MemoCreatedEvent::class]);
    $user = memoUser(['memos.create']);
    $project = Project::factory()->create();

    $this->actingAs($user)->post(route('memos.store'), memoPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
        'draft' => 1,
    ]));

    Event::assertNotDispatched(MemoCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = memoUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('memos.store'), memoPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
    ]));

    $response->assertForbidden();
    expect(Memo::count())->toBe(0);
});

test('store numbers memos sequentially within a project', function () {
    $user = memoUser(['memos.create']);
    $project = Project::factory()->create();
    Memo::factory()->create(['project_id' => $project->id, 'number' => 1]);

    $this->actingAs($user)->post(route('memos.store'), memoPayload([
        'project_id' => $project->id,
        'employee_id' => $user->employee_id,
        'title' => 'A different title',
    ]));

    $memo = Memo::where('project_id', $project->id)->latest('number')->first();

    expect($memo->number)->toBe(2);
});

// show

test('show is allowed for a sent memo with view.sender permission', function () {
    $user = memoUser(['memos.view.sender']);
    $memo = senderMemo($user);

    $response = $this->actingAs($user)->get(route('memos.show', $memo));

    $response->assertSuccessful();
    $response->assertViewIs('memo.show');
});

test('show is forbidden for an unrelated memo without view.other permission', function () {
    $user = memoUser(['memos.view.sender']);
    $memo = Memo::factory()->create();

    $response = $this->actingAs($user)->get(route('memos.show', $memo));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = memoUser(['memos.update.sender']);
    $memo = senderMemo($user);

    $response = $this->actingAs($user)->get(route('memos.edit', $memo));

    $response->assertSuccessful();
    $response->assertViewIs('memo.edit');
});

// update

test('update persists changes to a published memo and dispatches the updated event', function () {
    Event::fake([MemoUpdatedEvent::class]);
    $user = memoUser(['memos.update.sender']);
    $memo = senderMemo($user, ['draft' => false]);

    $response = $this->actingAs($user)->put(route('memos.update', $memo), memoPayload([
        'project_id' => $memo->project_id,
        'employee_id' => $memo->employee_id,
        'title' => 'Updated title',
    ]));

    $response->assertRedirect(route('memos.show', $memo));
    expect($memo->fresh()->title)->toBe('Updated title');
    Event::assertDispatched(MemoUpdatedEvent::class);
});

test('update is forbidden without matching permission', function () {
    $user = memoUser();
    $memo = senderMemo($user);

    $response = $this->actingAs($user)->put(route('memos.update', $memo), memoPayload([
        'project_id' => $memo->project_id,
        'employee_id' => $memo->employee_id,
        'title' => 'Updated title',
    ]));

    $response->assertForbidden();
});

// destroy

test('destroy removes a memo', function () {
    $user = memoUser(['memos.delete.sender']);
    $memo = senderMemo($user);

    $response = $this->actingAs($user)->delete(route('memos.destroy', $memo));

    $response->assertRedirect(route('memos.index'));
    expect(Memo::find($memo->id))->toBeNull();
});

test('destroy is forbidden without matching permission', function () {
    $user = memoUser();
    $memo = senderMemo($user);

    $response = $this->actingAs($user)->delete(route('memos.destroy', $memo));

    $response->assertForbidden();
    expect(Memo::find($memo->id))->not->toBeNull();
});

// publish

test('publish is allowed with update permission and dispatches the created event', function () {
    Event::fake([MemoCreatedEvent::class]);
    $user = memoUser(['memos.update.sender']);
    $memo = senderMemo($user, ['draft' => true]);

    $response = $this->actingAs($user)->get(route('memos.publish', $memo));

    $response->assertRedirect();
    expect($memo->fresh()->draft)->toBeFalse();
    Event::assertDispatched(MemoCreatedEvent::class);
});

test('publish is forbidden without update permission', function () {
    $user = memoUser(['memos.view.sender', 'memos.delete.sender']);
    $memo = senderMemo($user, ['draft' => true]);

    $response = $this->actingAs($user)->get(route('memos.publish', $memo));

    $response->assertForbidden();
    expect($memo->fresh()->draft)->toBeTrue();
});

// email

test('email sends the memo mail for a published memo', function () {
    Mail::fake();
    $user = memoUser(['memos.email.sender']);
    $memo = senderMemo($user, ['draft' => false]);

    $this->actingAs($user)->post(route('memos.email', $memo), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(MemoMail::class);
});

test('email is forbidden for a draft memo', function () {
    Mail::fake();
    $user = memoUser(['memos.email.sender']);
    $memo = senderMemo($user, ['draft' => true]);

    $response = $this->actingAs($user)->post(route('memos.email', $memo), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    $response->assertForbidden();
    Mail::assertNothingSent();
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    $user = memoUser(['memos.createpdf.sender']);
    $memo = senderMemo($user, ['draft' => false]);

    $response = $this->actingAs($user)->get(route('memos.download', $memo));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('download is forbidden for a draft memo', function () {
    $user = memoUser(['memos.createpdf.sender']);
    $memo = senderMemo($user, ['draft' => true]);

    $response = $this->actingAs($user)->get(route('memos.download', $memo));

    $response->assertForbidden();
});
