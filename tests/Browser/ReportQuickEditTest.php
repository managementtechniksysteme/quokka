<?php

namespace Tests\Browser;

use App\Models\AdditionsReport;
use App\Models\Employee;
use App\Models\InspectionReport;
use App\Models\Person;
use App\Models\User;

test('the weather of a report can be changed in the stat strip', function () {
    $user = User::factory()->create();
    grantPermission($user, 'inspection-reports.view.own');
    grantPermission($user, 'inspection-reports.update.own');
    $report = InspectionReport::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'weather' => 'sunny']);

    $this->actingAs($user);

    $page = visit(route('inspection-reports.show', $report))->on()->desktop();

    $page->assertSeeIn('.q-statbar .q-quick-edit__chip', 'sonnig')
        ->click('.q-statbar button[aria-label="Wetter ändern"]')
        ->click('.q-statbar .q-quick-edit__option:has-text("Schnee")')
        ->wait(0.8)
        ->assertSeeIn('.q-statbar .q-quick-edit__chip', 'Schnee')
        ->assertNoJavaScriptErrors();

    expect($report->fresh()->weather)->toBe('snowy');
});

test('the involved employees and present people of a report can be changed in place', function () {
    $user = User::factory()->create();
    grantPermission($user, 'additions-reports.view.own');
    grantPermission($user, 'additions-reports.update.own');
    $report = AdditionsReport::factory()->withInvolvedEmployees(1)->create(['employee_id' => $user->employee_id, 'status' => 'new']);
    $newEmployee = Employee::factory()->create();
    $visitor = Person::factory()->create();

    $this->actingAs($user);

    $page = visit(route('additions-reports.show', $report))->on()->desktop();

    $page->click('.q-detail__people button[aria-label="Personalstand ändern"]')
        ->click('.q-detail__people .vs__dropdown-toggle')
        ->click(".q-detail__people .vs__dropdown-option:has-text(\"{$newEmployee->person->name}\")")
        ->click('.q-detail__people button:has-text("Speichern")')
        ->wait(0.8)
        ->assertSeeIn('.q-detail__people', $newEmployee->person->name);

    expect($report->fresh()->involvedEmployees->pluck('person_id')->all())->toContain($newEmployee->person_id);

    $page->click('.q-detail__people button[aria-label="Anwesende Personen ändern"]')
        ->click('.q-detail__people .vs__dropdown-toggle')
        ->click(".q-detail__people .vs__dropdown-option:has-text(\"{$visitor->name}\")")
        ->click('.q-detail__people button:has-text("Speichern")')
        ->wait(0.8)
        ->assertSeeIn('.q-detail__people', $visitor->name);

    expect($report->fresh()->presentPeople->pluck('id')->all())->toBe([$visitor->id]);
});
