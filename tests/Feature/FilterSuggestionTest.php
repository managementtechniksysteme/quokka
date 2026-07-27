<?php

namespace Tests\Feature;

use App\Models\ConstructionReport;
use App\Models\DeliveryNote;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

function filterSuggestionUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('search returns 404 for an unknown model', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'nonexistent',
        'prefix' => 'p:',
        'query' => 'Acme',
    ]));

    $response->assertNotFound();
});

test('search is forbidden without viewAny permission on the model', function () {
    $user = filterSuggestionUser();

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'p:',
        'query' => 'Acme',
    ]));

    $response->assertForbidden();
});

test('search returns 422 for an unsupported prefix', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'ist:',
        'query' => 'anything',
    ]));

    $response->assertStatus(422);
});

test('search returns an empty array for a blank query', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'p:',
        'query' => '',
    ]));

    $response->assertSuccessful();
    $response->assertExactJson([]);
});

test('search returns a project name from a task the user can view', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);
    $project = Project::factory()->create(['name' => 'Findable Bridgeworks']);
    Task::factory()->create(['employee_id' => $user->employee_id, 'project_id' => $project->id]);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'p:',
        'query' => 'Findable Bridge',
    ]));

    $response->assertSuccessful();
    $response->assertJson(['Findable Bridgeworks']);
});

test('search omits a project name only present on a task the user cannot view', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);
    $project = Project::factory()->create(['name' => 'Findable Bridgeworks']);
    Task::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'p:',
        'query' => 'Findable Bridge',
    ]));

    $response->assertSuccessful();
    $response->assertExactJson([]);
});

test('search caps results at 8', function () {
    $user = filterSuggestionUser(['tasks.view.responsible']);

    foreach (range(1, 10) as $i) {
        $project = Project::factory()->create(['name' => "Findable Project {$i}"]);
        Task::factory()->create(['employee_id' => $user->employee_id, 'project_id' => $project->id]);
    }

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'task',
        'prefix' => 'p:',
        'query' => 'Findable Project',
    ]));

    $response->assertSuccessful();
    expect($response->json())->toHaveCount(8);
});

test('search resolves a hasraw person-name lookup for a different registered model', function () {
    $user = filterSuggestionUser(['construction-reports.view.other']);
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $person = Person::factory()->create(['first_name' => 'Findable', 'last_name' => 'Testperson']);
    $report = ConstructionReport::factory()->create(['employee_id' => $employee->person_id, 'project_id' => $project->id]);
    $report->presentPeople()->attach($person->id, ['person_type' => 'present']);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'construction_report',
        'prefix' => 'beteiligt:',
        'query' => 'Findable',
    ]));

    $response->assertSuccessful();
    $response->assertJson(['Findable Testperson']);
});

test('search resolves a bare-column lookup for a model with no FiltersPermissions scope', function () {
    $user = filterSuggestionUser(['delivery-notes.view']);
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    DeliveryNote::factory()->create([
        'employee_id' => $employee->person_id,
        'project_id' => $project->id,
        'title' => 'Findable Delivery Title',
    ]);

    $response = $this->actingAs($user)->get(route('filter-suggestions.search', [
        'model' => 'delivery_note',
        'prefix' => 'titel:',
        'query' => 'Findable',
    ]));

    $response->assertSuccessful();
    $response->assertJson(['Findable Delivery Title']);
});
