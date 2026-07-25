<?php

namespace Tests\Feature;

use App\Models\User;

test('index is forbidden without help.view permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('help.index'));

    $response->assertForbidden();
});

test('index lists the available help topics', function () {
    $user = User::factory()->create();
    grantPermission($user, 'help.view');

    $response = $this->actingAs($user)->get(route('help.index'));

    $response->assertSuccessful();
    $response->assertViewIs('help.index');
    $response->assertViewHas('names', function ($names) {
        return in_array('filters', $names) && in_array('markdown', $names) && ! in_array('index', $names);
    });
});

test('show renders an existing help topic', function () {
    $user = User::factory()->create();
    grantPermission($user, 'help.view');

    $response = $this->actingAs($user)->get(route('help.show', ['help' => 'markdown']));

    $response->assertSuccessful();
    $response->assertViewIs('help.topic.markdown');
});

test('show 404s for a nonexistent help topic', function () {
    $user = User::factory()->create();
    grantPermission($user, 'help.view');

    $response = $this->actingAs($user)->get(route('help.show', ['help' => 'nonexistent-topic']));

    $response->assertNotFound();
});
