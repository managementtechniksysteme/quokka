<?php

namespace Tests\Feature;

use App\Mail\NoteMail;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;

function noteUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownNote(User $user, array $attributes = []): Note
{
    return Note::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// index

test('index is shown for a user with view permission', function () {
    $user = noteUser(['notes.view']);

    $response = $this->actingAs($user)->get(route('notes.index'));

    $response->assertSuccessful();
    $response->assertViewIs('note.index');
});

test('index is forbidden without view permission', function () {
    $user = noteUser();

    $response = $this->actingAs($user)->get(route('notes.index'));

    $response->assertForbidden();
});

test('index only lists the acting user\'s own notes', function () {
    $user = noteUser(['notes.view']);
    $own = ownNote($user, ['title' => 'My Own Note']);
    Note::factory()->create(['title' => 'Someone Else\'s Note']);

    $response = $this->actingAs($user)->get(route('notes.index'));

    $response->assertSee('My Own Note');
    $response->assertDontSee('Someone Else\'s Note');
});

// create

test('create form is shown for a user with create permission', function () {
    $user = noteUser(['notes.create']);

    $response = $this->actingAs($user)->get(route('notes.create'));

    $response->assertSuccessful();
    $response->assertViewIs('note.create');
});

test('create is forbidden without create permission', function () {
    $user = noteUser();

    $response = $this->actingAs($user)->get(route('notes.create'));

    $response->assertForbidden();
});

test('create with a valid template preloads that note', function () {
    $user = noteUser(['notes.create', 'notes.view']);
    $template = ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.create', ['template' => $template->id]));

    $response->assertSuccessful();
    $response->assertViewHas('note', function ($note) use ($template) {
        return $note->id === $template->id;
    });
});

test('create with a nonexistent template fails validation', function () {
    $user = noteUser(['notes.create']);

    $response = $this->actingAs($user)->get(route('notes.create', ['template' => 999999]));

    $response->assertSessionHasErrors('template');
});

test('create with a template the user cannot view redirects with a danger message', function () {
    $user = noteUser(['notes.create']);
    $template = Note::factory()->create();

    $response = $this->actingAs($user)->get(route('notes.create', ['template' => $template->id]));

    $response->assertRedirect(route('notes.create'));
    $response->assertSessionHas('danger');
});

// store

test('store creates a note owned by the acting user', function () {
    $user = noteUser(['notes.create']);

    $response = $this->actingAs($user)->post(route('notes.store'), [
        'title' => 'A new note',
        'comment' => 'Some content',
    ]);

    $note = Note::where('title', 'A new note')->sole();
    $response->assertRedirect(route('notes.show', $note));
    expect($note->employee_id)->toBe($user->employee_id);
});

test('store requires a comment', function () {
    $user = noteUser(['notes.create']);

    $response = $this->actingAs($user)->post(route('notes.store'), ['title' => 'No comment']);

    $response->assertSessionHasErrors('comment');
});

test('store attaches uploaded files', function () {
    $user = noteUser(['notes.create']);

    $this->actingAs($user)->post(route('notes.store'), [
        'comment' => 'With an attachment',
        'new_attachments' => [UploadedFile::fake()->create('photo.jpg', 10, 'image/jpeg')],
    ]);

    $note = Note::where('comment', 'With an attachment')->sole();
    expect($note->attachments())->toHaveCount(1);
});

// show

test('show is allowed for your own note with view permission', function () {
    $user = noteUser(['notes.view']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.show', $note));

    $response->assertSuccessful();
    $response->assertViewIs('note.show');
});

test('show is forbidden for someone else\'s note', function () {
    $user = noteUser(['notes.view']);
    $note = Note::factory()->create();

    $response = $this->actingAs($user)->get(route('notes.show', $note));

    $response->assertForbidden();
});

// edit

test('edit is shown for your own note with update permission', function () {
    $user = noteUser(['notes.update']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.edit', $note));

    $response->assertSuccessful();
    $response->assertViewIs('note.edit');
});

// update

test('update persists changes to your own note', function () {
    $user = noteUser(['notes.update']);
    $note = ownNote($user, ['comment' => 'Old comment']);

    $response = $this->actingAs($user)->put(route('notes.update', $note), ['comment' => 'New comment']);

    $response->assertRedirect(route('notes.show', $note));
    expect($note->fresh()->comment)->toBe('New comment');
});

test('update is forbidden for someone else\'s note', function () {
    $user = noteUser(['notes.update']);
    $note = Note::factory()->create();

    $response = $this->actingAs($user)->put(route('notes.update', $note), ['comment' => 'New comment']);

    $response->assertForbidden();
});

test('update removes attachments', function () {
    $user = noteUser(['notes.update']);
    $note = ownNote($user);
    $media = $note->addMedia(UploadedFile::fake()->create('photo.jpg', 10, 'image/jpeg'))->toMediaCollection('attachments');

    $this->actingAs($user)->put(route('notes.update', $note), [
        'comment' => $note->comment,
        'remove_attachments' => [$media->id],
    ]);

    expect($note->fresh()->attachments())->toHaveCount(0);
});

// destroy

test('destroy removes your own note', function () {
    $user = noteUser(['notes.delete']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->delete(route('notes.destroy', $note));

    $response->assertRedirect(route('notes.index'));
    $this->assertModelMissing($note);
});

test('destroy is forbidden for someone else\'s note', function () {
    $user = noteUser(['notes.delete']);
    $note = Note::factory()->create();

    $response = $this->actingAs($user)->delete(route('notes.destroy', $note));

    $response->assertForbidden();
    $this->assertModelExists($note);
});

// email

test('showEmail is shown for your own note with email permission', function () {
    $user = noteUser(['notes.email']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.email', $note));

    $response->assertSuccessful();
    $response->assertViewIs('note.email');
});

test('email sends the note mail', function () {
    Mail::fake();
    $user = noteUser(['notes.email']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->post(route('notes.email', $note), [
        'email_to' => [['email' => 'someone@example.com']],
    ]);

    $response->assertRedirect(route('notes.index'));
    Mail::assertQueued(NoteMail::class);
});

test('email with redirect=show redirects back to the note', function () {
    Mail::fake();
    $user = noteUser(['notes.email']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->post(route('notes.email', $note), [
        'email_to' => [['email' => 'someone@example.com']],
        'redirect' => 'show',
    ]);

    $response->assertRedirect(route('notes.show', $note));
});

// download (real pdflatex, no mocking)

test('download renders a real pdf for your own note', function () {
    $user = noteUser(['notes.createpdf']);
    $note = ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.download', $note));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList renders a real pdf of the acting user\'s own notes', function () {
    $user = noteUser(['notes.view']);
    ownNote($user);

    $response = $this->actingAs($user)->get(route('notes.download-list'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList is forbidden without view permission', function () {
    $user = noteUser();

    $response = $this->actingAs($user)->get(route('notes.download-list'));

    $response->assertForbidden();
});
