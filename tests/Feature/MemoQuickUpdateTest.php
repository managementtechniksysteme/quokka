<?php

namespace Tests\Feature;

use App\Events\MemoUpdatedEvent;
use App\Models\Memo;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Event;

function memoEditor(bool $mayUpdate = true): User
{
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.sender');

    if ($mayUpdate) {
        grantPermission($user, 'memos.update.sender');
    }

    return $user;
}

function editorMemo(User $user, array $attributes = []): Memo
{
    return Memo::factory()->create(array_merge(['employee_id' => $user->employee_id, 'draft' => false], $attributes));
}

test('memo quick update replaces and empties the present people', function () {
    $user = memoEditor();
    $memo = editorMemo($user);
    $memo->presentPeople()->attach(Person::factory()->count(2)->create(), ['person_type' => 'present']);
    $newPerson = Person::factory()->create();

    $this->actingAs($user)->patchJson(route('memos.quick-update', $memo), ['present_ids' => [$newPerson->id]])
        ->assertOk()
        ->assertJson(['field' => 'present_ids', 'value' => [$newPerson->id], 'changed' => true]);

    expect($memo->fresh()->presentPeople->pluck('id')->all())->toBe([$newPerson->id]);

    $this->patchJson(route('memos.quick-update', $memo), ['present_ids' => []])->assertOk();

    expect($memo->fresh()->presentPeople)->toHaveCount(0);
});

test('memo quick update never lists the recipient on the distribution list', function () {
    $user = memoEditor();
    $recipient = Person::factory()->create();
    $memo = editorMemo($user, ['person_id' => $recipient->id]);
    $colleague = Person::factory()->create();

    $this->actingAs($user)->patchJson(route('memos.quick-update', $memo), ['notified_ids' => [$recipient->id, $colleague->id]])
        ->assertOk()
        ->assertJson(['field' => 'notified_ids', 'value' => [$colleague->id]]);

    expect($memo->fresh()->notifiedPeople->pluck('id')->all())->toBe([$colleague->id]);
});

test('memo quick update notifies about a change to a published memo only', function () {
    Event::fake([MemoUpdatedEvent::class]);
    $user = memoEditor();
    $published = editorMemo($user);
    $draft = editorMemo($user, ['draft' => true]);
    $person = Person::factory()->create();

    $this->actingAs($user)->patchJson(route('memos.quick-update', $draft), ['present_ids' => [$person->id]])->assertOk();
    Event::assertNotDispatched(MemoUpdatedEvent::class);

    $this->patchJson(route('memos.quick-update', $published), ['present_ids' => [$person->id]])->assertOk();
    Event::assertDispatched(MemoUpdatedEvent::class, 1);

    $this->patchJson(route('memos.quick-update', $published), ['present_ids' => [$person->id]])
        ->assertOk()
        ->assertJson(['changed' => false]);
    Event::assertDispatched(MemoUpdatedEvent::class, 1);
});

test('memo quick update is forbidden without the update permission', function () {
    $user = memoEditor(mayUpdate: false);
    $memo = editorMemo($user);

    $this->actingAs($user)->patchJson(route('memos.quick-update', $memo), ['present_ids' => [Person::factory()->create()->id]])
        ->assertForbidden();

    expect($memo->fresh()->presentPeople)->toHaveCount(0);
});

test('memo quick update rejects unknown people, unknown fields and several fields at once', function (array $payload) {
    $user = memoEditor();
    $memo = editorMemo($user);

    $this->actingAs($user)->patchJson(route('memos.quick-update', $memo), $payload)->assertUnprocessable();
})->with([
    'unknown person' => [['present_ids' => [999999]]],
    'unknown field' => [['title' => 'Renamed']],
    'two fields' => [['present_ids' => [], 'notified_ids' => []]],
    'no field' => [[]],
]);

test('memo show offers in-place people editing only to users who may update the memo', function () {
    $editor = memoEditor();
    $viewer = memoEditor(mayUpdate: false);

    $this->actingAs($editor)->get(route('memos.show', editorMemo($editor)))
        ->assertSuccessful()
        ->assertSee('<quick-edit-people', false);

    $this->actingAs($viewer)->get(route('memos.show', editorMemo($viewer)))
        ->assertSuccessful()
        ->assertDontSee('<quick-edit-people', false);
});
