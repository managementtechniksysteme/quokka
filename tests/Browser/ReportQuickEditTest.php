<?php

namespace Tests\Browser;

use App\Models\AdditionsReport;
use App\Models\Employee;
use App\Models\InspectionReport;
use App\Models\Memo;
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

test('participants of a report are added with the plus and removed with the x', function () {
    $user = User::factory()->create();
    grantPermission($user, 'additions-reports.view.own');
    grantPermission($user, 'additions-reports.update.own');
    $report = AdditionsReport::factory()->withInvolvedEmployees(1)->create(['employee_id' => $user->employee_id, 'status' => 'new']);
    $newEmployee = Employee::factory()->create();
    $visitor = Person::factory()->create();

    $this->actingAs($user);

    $page = visit(route('additions-reports.show', $report))->on()->desktop();

    $page->click('.q-detail__people button[aria-label="Personalstand: Person hinzufügen"]')
        ->click(".q-detail__people .vs__dropdown-option:has-text(\"{$newEmployee->person->name}\")")
        ->wait(0.8)
        ->assertSeeIn('.q-detail__people', $newEmployee->person->name);

    expect($report->fresh()->involvedEmployees->pluck('person_id')->all())->toContain($newEmployee->person_id);

    $page->click('.q-detail__people button[aria-label="Anwesende Personen: Person hinzufügen"]')
        ->click(".q-detail__people .vs__dropdown-option:has-text(\"{$visitor->name}\")")
        ->wait(0.8)
        ->assertSeeIn('.q-detail__people', $visitor->name)
        ->hover(".q-detail__people .q-quick-edit-people__row:has-text(\"{$visitor->name}\")")
        ->click(".q-detail__people button[aria-label=\"{$visitor->name} entfernen\"]")
        ->wait(0.8)
        ->assertDontSeeIn('.q-detail__people', $visitor->name);

    expect($report->fresh()->presentPeople)->toHaveCount(0);
});

test('the last involved employee of a report cannot be removed', function () {
    $user = User::factory()->create();
    grantPermission($user, 'additions-reports.view.own');
    grantPermission($user, 'additions-reports.update.own');
    $report = AdditionsReport::factory()->withInvolvedEmployees(1)->create(['employee_id' => $user->employee_id, 'status' => 'new']);
    $employee = $report->involvedEmployees->first();

    $this->actingAs($user);

    $page = visit(route('additions-reports.show', $report))->on()->desktop();

    $page->assertPresent(".q-detail__people button[aria-label=\"{$employee->person->name} entfernen\"][disabled]");

    expect($report->fresh()->involvedEmployees)->toHaveCount(1);
});

test('the people of a memo are added with the plus and removed with the x', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.sender');
    grantPermission($user, 'memos.update.sender');
    $memo = Memo::factory()->create(['employee_id' => $user->employee_id, 'draft' => false]);
    $visitor = Person::factory()->create();

    $this->actingAs($user);

    $page = visit(route('memos.show', $memo))->on()->desktop();

    $page->click('.q-detail__people button[aria-label="Verteiler: Person hinzufügen"]')
        ->click(".q-detail__people .vs__dropdown-option:has-text(\"{$visitor->name}\")")
        ->wait(0.8)
        ->assertSeeIn('.q-detail__people', $visitor->name);

    expect($memo->fresh()->notifiedPeople->pluck('id')->all())->toBe([$visitor->id]);

    $page->hover(".q-detail__people .q-quick-edit-people__row:has-text(\"{$visitor->name}\")")
        ->click(".q-detail__people button[aria-label=\"{$visitor->name} entfernen\"]")
        ->wait(0.8)
        ->assertDontSeeIn('.q-detail__people', $visitor->name);

    expect($memo->fresh()->notifiedPeople)->toHaveCount(0);
});
