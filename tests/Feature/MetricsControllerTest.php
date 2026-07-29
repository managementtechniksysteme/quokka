<?php

namespace Tests\Feature;

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\WageService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('index is forbidden without tools.viewmetrics permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('metrics.index'));

    $response->assertForbidden();
});

test('index renders for an authorized user', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');

    $response = $this->actingAs($user)->get(route('metrics.index'));

    $response->assertSuccessful();
    $response->assertViewIs('metrics.index');
});

test('on-time task rate counts a task finished by its due date as on time', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'finished',
        'starts_on' => Carbon::parse('2026-06-01'),
        'ends_on' => Carbon::parse('2026-06-10'),
        'due_on' => Carbon::parse('2026-06-15'),
    ]);
    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'finished',
        'starts_on' => Carbon::parse('2026-06-01'),
        'ends_on' => Carbon::parse('2026-06-20'),
        'due_on' => Carbon::parse('2026-06-15'),
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-30',
    ]));

    $response->assertSuccessful();
    $response->assertSee('1 von 2 pünktlich');
});

test('task status breakdown buckets an overdue task separately from in progress', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'in progress',
        'starts_on' => Carbon::today()->subDays(5),
        'ends_on' => null,
        'due_on' => Carbon::today()->subDay(),
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index'));

    $response->assertSuccessful();
    $response->assertSee('Überfällig');
});

test('employee workload reflects open task counts relative to the busiest employee', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    $busy = Employee::factory()->create(['entered_on' => Carbon::today()->subYear(), 'left_on' => null]);
    $quiet = Employee::factory()->create(['entered_on' => Carbon::today()->subYear(), 'left_on' => null]);

    Task::factory()->count(4)->create([
        'project_id' => $project->id,
        'employee_id' => $busy->person_id,
        'status' => 'new',
        'starts_on' => Carbon::today(),
    ]);
    Task::factory()->count(2)->create([
        'project_id' => $project->id,
        'employee_id' => $quiet->person_id,
        'status' => 'new',
        'starts_on' => Carbon::today(),
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index'));

    $response->assertSuccessful();
    $response->assertSee('4 offene Aufgaben');
    $response->assertSee('2 offene Aufgaben');
});

test('average hours per week sums hour-based wage services within the range', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create(['entered_on' => Carbon::parse('2026-01-01'), 'left_on' => null]);
    $service = WageService::factory()->create(['unit' => 'h']);
    $project = Project::factory()->create(['ends_on' => null]);

    Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $service->id,
        'project_id' => $project->id,
        'service_provided_on' => '2026-06-01',
        'amount' => 35,
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-07',
        // Scoped to this one employee: User::factory() (above) creates its own
        // incidental Employee with a random entered_on/left_on, which would
        // otherwise sometimes fall inside the window too and dilute the
        // average (flaky, 2026-07-29 — landed on "35 / 2 employees = 17,5").
        'employee_id' => $employee->person_id,
    ]));

    $response->assertSuccessful();
    $response->assertSee('35,0 Std / Woche');
});

test('average time to signature diffs the report date against the signature media timestamp', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');

    $project = Project::factory()->create(['ends_on' => null]);
    $report = ConstructionReport::factory()->create([
        'status' => 'signed',
        'services_provided_on' => '2026-06-01',
        'project_id' => $project->id,
    ]);
    $report->addMedia(UploadedFile::fake()->image('signature.png'))->toMediaCollection('signature');
    $report->media('signature')->first()->update(['created_at' => Carbon::parse('2026-06-05')]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-30',
    ]));

    $response->assertSuccessful();
    $response->assertSee('Median 4,0 Tage');
});
