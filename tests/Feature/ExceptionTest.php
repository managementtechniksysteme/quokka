<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function writeExceptionLog(string $content = 'Some stack trace'): string
{
    $uuid = (string) Str::uuid();

    Storage::disk('local')->put("exceptions/{$uuid}.log", $content);

    return $uuid;
}

afterEach(function () {
    Storage::disk('local')->deleteDirectory('exceptions');
});

test('index is forbidden without exceptions.view permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('exceptions.index'));

    $response->assertForbidden();
});

test('index lists logged exception files', function () {
    $user = User::factory()->create();
    grantPermission($user, 'exceptions.view');
    $uuid = writeExceptionLog();

    $response = $this->actingAs($user)->get(route('exceptions.index'));

    $response->assertSuccessful();
    $response->assertViewIs('exception.index');
    $response->assertSee($uuid);
});

test('index filters exception files by search term', function () {
    $user = User::factory()->create();
    grantPermission($user, 'exceptions.view');
    $matching = writeExceptionLog();
    writeExceptionLog();

    $response = $this->actingAs($user)->get(route('exceptions.index', ['search' => $matching]));

    $response->assertSuccessful();
    $response->assertSee($matching);
});

test('show is forbidden without exceptions.view permission', function () {
    $user = User::factory()->create();
    $uuid = writeExceptionLog();

    $response = $this->actingAs($user)->get(route('exceptions.show', ['exception' => $uuid]));

    $response->assertForbidden();
});

test('show renders the log content for an existing exception file', function () {
    $user = User::factory()->create();
    grantPermission($user, 'exceptions.view');
    $uuid = writeExceptionLog('the actual stack trace');

    $response = $this->actingAs($user)->get(route('exceptions.show', ['exception' => $uuid]));

    $response->assertSuccessful();
    $response->assertViewIs('exception.show');
    $response->assertSee('the actual stack trace');
});

test('show 404s for a nonexistent exception file', function () {
    $user = User::factory()->create();
    grantPermission($user, 'exceptions.view');

    $response = $this->actingAs($user)->get(route('exceptions.show', ['exception' => (string) Str::uuid()]));

    $response->assertNotFound();
});

test('destroy is forbidden without exceptions.delete permission', function () {
    $user = User::factory()->create();
    $uuid = writeExceptionLog();

    $response = $this->actingAs($user)->delete(route('exceptions.destroy', ['exception' => $uuid]));

    $response->assertForbidden();
    Storage::disk('local')->assertExists("exceptions/{$uuid}.log");
});

test('destroy removes an existing exception file', function () {
    $user = User::factory()->create();
    grantPermission($user, 'exceptions.delete');
    $uuid = writeExceptionLog();

    $response = $this->actingAs($user)->delete(route('exceptions.destroy', ['exception' => $uuid]));

    $response->assertRedirect(route('exceptions.index'));
    Storage::disk('local')->assertMissing("exceptions/{$uuid}.log");
});
