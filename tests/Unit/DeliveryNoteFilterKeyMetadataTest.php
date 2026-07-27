<?php

namespace Tests\Unit;

use App\Models\DeliveryNote;
use App\Models\Employee;
use App\Models\Project;

test('bare-column titel key appears as a lookup entry', function () {
    $metadata = DeliveryNote::filterKeyMetadata();

    $lookup = collect($metadata)->where('kind', 'lookup')->firstWhere('prefix', 'titel:');

    expect($lookup)->not->toBeNull();
    expect($lookup['label'])->toBe('Titel');
});

test('filterSuggestionValues resolves a bare-column lookup with no FiltersPermissions scope', function () {
    // DeliveryNote has no FiltersPermissions trait/$permissionFilters at all -- this
    // proves filterPermissionsOrQuery() falls back to an unscoped query instead of
    // fatal-erroring on a scope that doesn't exist on this model.
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    DeliveryNote::factory()->create([
        'employee_id' => $employee->person_id,
        'project_id' => $project->id,
        'title' => 'Findable Delivery Title',
    ]);
    DeliveryNote::factory()->create([
        'employee_id' => $employee->person_id,
        'project_id' => $project->id,
        'title' => 'Unrelated Other Title',
    ]);

    $values = DeliveryNote::filterSuggestionValues('titel:', 'Findable');

    expect($values)->toContain('Findable Delivery Title');
    expect($values)->not->toContain('Unrelated Other Title');
});
