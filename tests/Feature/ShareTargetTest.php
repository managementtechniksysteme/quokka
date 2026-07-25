<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('receive is forbidden without notes.create permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('share-target'), [
        'photos' => [UploadedFile::fake()->image('photo.jpg')],
    ]);

    $response->assertForbidden();
});

test('receive requires at least one photo', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.create');

    $response = $this->actingAs($user)->post(route('share-target'), []);

    $response->assertSessionHasErrors('photos');
});

test('receive creates a note owned by the acting user with the shared photos attached', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.create');

    $response = $this->actingAs($user)->post(route('share-target'), [
        'photos' => [UploadedFile::fake()->image('photo1.jpg'), UploadedFile::fake()->image('photo2.jpg')],
    ]);

    $note = Note::sole();
    expect($note->employee_id)->toBe($user->employee_id);
    expect($note->attachments())->toHaveCount(2);
    $response->assertRedirect(route('notes.show', $note));
});

test('receive sets a singular success message for a single photo', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.create');

    $this->actingAs($user)->post(route('share-target'), [
        'photos' => [UploadedFile::fake()->image('photo.jpg')],
    ]);

    expect(Note::sole()->comment)->toBe('1 Foto hinzugefügt.');
});
