<?php

namespace Tests\Feature\Api;

use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Models\Logbook;
use App\Models\MaterialService;
use App\Models\Project;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WageService;
use Laravel\Sanctum\Sanctum;

function apiUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    Sanctum::actingAs($user, ['authenticate']);

    return $user;
}

test('employees select-options lists employees by person name', function () {
    apiUser();
    $employee = Employee::factory()->create();

    $response = $this->getJson('/api/employees/select-options');

    $response->assertSuccessful();
    $response->assertJsonFragment(['id' => $employee->person_id]);
});

test('projects select-options lists projects', function () {
    apiUser();
    $project = Project::factory()->create();

    $response = $this->getJson('/api/projects/select-options');

    $response->assertSuccessful();
    $response->assertJsonFragment(['id' => $project->id, 'text' => $project->name]);
});

test('vehicles select-options lists vehicles', function () {
    apiUser();
    $vehicle = Vehicle::factory()->create();

    $response = $this->getJson('/api/vehicles/select-options');

    $response->assertSuccessful();
    $response->assertJsonFragment(['id' => $vehicle->id]);
});

test('vehicles current-kilometres reflects the latest logbook entry', function () {
    apiUser();
    $vehicle = Vehicle::factory()->create();
    Logbook::factory()->create(['vehicle_id' => $vehicle->id, 'end_kilometres' => 12345]);

    $response = $this->getJson('/api/vehicles/current-kilometres');

    $response->assertSuccessful();
    $response->assertJsonFragment(['id' => $vehicle->id, 'kilometres' => 12345]);
});

test('logbook location-select-options merges origins and destinations', function () {
    apiUser(['logbook.view.own']);
    Logbook::factory()->create(['origin' => 'Zurich', 'destination' => 'Bern']);

    $response = $this->getJson('/api/logbook/location-select-options');

    $response->assertSuccessful();
    $response->assertJsonFragment(['text' => 'Zurich']);
    $response->assertJsonFragment(['text' => 'Bern']);
});

test('logbook location-select-options is forbidden without any view permission', function () {
    apiUser();

    $response = $this->getJson('/api/logbook/location-select-options');

    $response->assertForbidden();
});

test('services select-options lists services with their unit', function () {
    apiUser();
    $service = MaterialService::factory()->create(['name' => 'Concrete']);

    $response = $this->getJson('/api/services/select-options');

    $response->assertSuccessful();
    $response->assertJsonFragment(['id' => $service->id]);
});

test('services types lists service ids grouped by type', function () {
    apiUser();
    MaterialService::factory()->create();
    WageService::factory()->create();

    $response = $this->getJson('/api/services/types');

    $response->assertSuccessful();
});

test('services hourly-based-ids only includes wage services matching the hour unit', function () {
    apiUser();
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();
    $hourly = WageService::factory()->create(['unit' => 'h']);
    WageService::factory()->create(['unit' => 'km']);

    $response = $this->getJson('/api/services/hourly-based-ids');

    $response->assertSuccessful();
    $response->assertJsonFragment(['ids' => [$hourly->id]]);
});
