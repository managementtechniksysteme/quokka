<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Vehicle;

function quickEditUserWith(string ...$permissions): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('vehicle quick update switches between private and company-wide', function () {
    $user = quickEditUserWith('vehicles.view', 'vehicles.update');
    $vehicle = Vehicle::factory()->create(['private' => false]);

    $this->actingAs($user)->patchJson(route('vehicles.quick-update', $vehicle), ['private' => true])
        ->assertOk()
        ->assertJson(['field' => 'private', 'value' => true, 'changed' => true]);

    expect($vehicle->fresh()->private)->toBeTrue();

    $this->patchJson(route('vehicles.quick-update', $vehicle), ['private' => false])->assertOk();

    expect($vehicle->fresh()->private)->toBeFalse();
});

test('vehicle quick update is forbidden without the update permission and rejects bad values', function () {
    $viewer = quickEditUserWith('vehicles.view');
    $editor = quickEditUserWith('vehicles.view', 'vehicles.update');
    $vehicle = Vehicle::factory()->create(['private' => false]);

    $this->actingAs($viewer)->patchJson(route('vehicles.quick-update', $vehicle), ['private' => true])->assertForbidden();
    $this->actingAs($editor)->patchJson(route('vehicles.quick-update', $vehicle), ['private' => 'maybe'])->assertUnprocessable();

    expect($vehicle->fresh()->private)->toBeFalse();
});

test('vehicle show offers the visibility switch only to users who may update', function () {
    $viewer = quickEditUserWith('vehicles.view');
    $editor = quickEditUserWith('vehicles.view', 'vehicles.update');
    $vehicle = Vehicle::factory()->create(['private' => true]);

    $this->actingAs($editor)->get(route('vehicles.show', $vehicle))->assertSuccessful()->assertSee('<quick-edit-chip', false);
    $this->actingAs($viewer)->get(route('vehicles.show', $vehicle))->assertSuccessful()->assertDontSee('<quick-edit-chip', false);
});

test('project quick update switches the pre-execution phase', function () {
    $user = quickEditUserWith('projects.view', 'projects.update');
    $project = Project::factory()->create(['is_pre_execution' => false, 'ends_on' => null]);

    $this->actingAs($user)->patchJson(route('projects.quick-update', $project), ['is_pre_execution' => true])
        ->assertOk()
        ->assertJson(['field' => 'is_pre_execution', 'value' => true, 'changed' => true]);

    expect($project->fresh()->is_pre_execution)->toBeTrue();

    $this->patchJson(route('projects.quick-update', $project), ['is_pre_execution' => false])->assertOk();

    expect($project->fresh()->is_pre_execution)->toBeFalse();
});

test('project quick update refuses the pre-execution phase for an ended project', function () {
    $user = quickEditUserWith('projects.view', 'projects.update');
    $project = Project::factory()->create(['is_pre_execution' => false, 'starts_on' => now()->subMonth(), 'ends_on' => now()->subDay()]);

    $this->actingAs($user)->patchJson(route('projects.quick-update', $project), ['is_pre_execution' => true])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('is_pre_execution');

    expect($project->fresh()->is_pre_execution)->toBeFalse();
});

test('project quick update is forbidden without the update permission', function () {
    $user = quickEditUserWith('projects.view');
    $project = Project::factory()->create(['is_pre_execution' => false]);

    $this->actingAs($user)->patchJson(route('projects.quick-update', $project), ['is_pre_execution' => true])->assertForbidden();
});
