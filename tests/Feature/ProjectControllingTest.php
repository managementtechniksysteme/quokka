<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;

function projectControllingUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('index is shown for a user with finances.view permission', function () {
    $user = projectControllingUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('project-controlling.index'));

    $response->assertSuccessful();
    $response->assertViewIs('project_controlling.index');
});

test('index is forbidden without finances.view permission', function () {
    $user = projectControllingUser();

    $response = $this->actingAs($user)->get(route('project-controlling.index'));

    $response->assertForbidden();
});

test('index with a project loads that project\'s finance data', function () {
    $user = projectControllingUser(['finances.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('project-controlling.index', ['project' => $project->id]));

    $response->assertSuccessful();
    $response->assertViewHas('currentProject', function ($currentProject) use ($project) {
        return $currentProject->id === $project->id;
    });
});

test('index validates that project exists', function () {
    $user = projectControllingUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('project-controlling.index', ['project' => 999999]));

    $response->assertSessionHasErrors('project');
});

test('index validates start/end range requires a project', function () {
    $user = projectControllingUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('project-controlling.index', [
        'start' => now()->subDay()->toDateString(),
        'end' => now()->toDateString(),
    ]));

    $response->assertSessionHasErrors('project');
});
