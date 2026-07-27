<?php

namespace Tests\Unit;

use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Project;
use App\Models\User;

test('hasraw beteiligt key appears as a lookup entry', function () {
    $metadata = ConstructionReport::filterKeyMetadata();

    $lookup = collect($metadata)->where('kind', 'lookup')->firstWhere('prefix', 'beteiligt:');

    expect($lookup)->not->toBeNull();
    expect($lookup['label'])->toBe('Beteiligt');
});

test('bare-column nummer key is grouped as a lookup, not misread as an enum', function () {
    $metadata = ConstructionReport::filterKeyMetadata();

    $lookup = collect($metadata)->where('kind', 'lookup')->firstWhere('prefix', 'nummer:');

    expect($lookup)->not->toBeNull();

    // a naive '(.*)'-only capture-group check would miss 'nummer:(\d)' entirely,
    // silently misgrouping it as an enum value under a bogus "nummer" group
    $enumGroup = collect($metadata)->where('kind', 'enum')->firstWhere('prefix', 'nummer:');
    expect($enumGroup)->toBeNull();
});

test('filterSuggestionValues resolves a hasraw person-name lookup to matching concatenated names', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.other');
    $this->actingAs($user);

    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $matchingPerson = Person::factory()->create(['first_name' => 'Findable', 'last_name' => 'Testperson']);
    $otherPerson = Person::factory()->create(['first_name' => 'Someone', 'last_name' => 'Else']);

    $report = ConstructionReport::factory()->create(['employee_id' => $employee->person_id, 'project_id' => $project->id]);
    $report->presentPeople()->attach($matchingPerson->id, ['person_type' => 'present']);
    $report->presentPeople()->attach($otherPerson->id, ['person_type' => 'present']);

    $values = ConstructionReport::filterSuggestionValues('beteiligt:', 'Findable');

    expect($values)->toContain('Findable Testperson');
    expect($values)->not->toContain('Someone Else');
});

test('filterSuggestionValues resolves a bare-column lookup without a relation', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.other');
    $this->actingAs($user);

    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    ConstructionReport::factory()->create(['employee_id' => $employee->person_id, 'project_id' => $project->id, 'number' => 4242]);

    $values = ConstructionReport::filterSuggestionValues('nummer:', '424');

    expect($values)->toContain('4242');
});
