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

test('task status breakdown reflects status as of the period end, not today', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'in progress',
        'starts_on' => Carbon::parse('2026-06-01'),
        'ends_on' => null,
        'due_on' => null,
    ]);
    // Finished today, i.e. after the reporting period below — the period's
    // breakdown must still read "in progress", the state as of 2026-06-30.
    $task->update(['status' => 'finished', 'ends_on' => Carbon::today()]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-30',
    ]));

    $response->assertSuccessful();
    $breakdown = $response->viewData('metrics')->taskStatusBreakdown();
    expect($breakdown['inProgress'])->toBe(1);
    expect($breakdown['finished'])->toBe(0);
});

test('report status breakdown reflects status as of the period end, not today', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    $report = ConstructionReport::factory()->create([
        'project_id' => $project->id,
        'status' => 'signed',
        'services_provided_on' => '2026-06-01',
    ]);
    // Marked finished today, after the reporting period — the period's
    // breakdown must still read "signed", the state as of 2026-06-30.
    $report->update(['status' => 'finished']);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-30',
    ]));

    $response->assertSuccessful();
    $breakdown = $response->viewData('metrics')->reportStatusBreakdown();
    expect($breakdown['signed'])->toBe(1);
    expect($breakdown['finished'])->toBe(0);
});

test('overdue tasks summary counts overdue as of the period end, not today', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'in progress',
        'starts_on' => Carbon::parse('2026-06-01'),
        'ends_on' => null,
        'due_on' => Carbon::parse('2026-06-10'),
    ]);
    // Finished today, well after both the due date and the reporting
    // period — as of the period end it was still open and overdue.
    $task->update(['status' => 'finished', 'ends_on' => Carbon::today()]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-30',
    ]));

    $response->assertSuccessful();
    $summary = $response->viewData('metrics')->overdueTasksSummary();
    expect($summary['count'])->toBe(1);
    expect($summary['average_days'])->toBe(21.0);
});

test('employee workload counts every task touching the period, finished or not', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);

    $busy = Employee::factory()->create(['entered_on' => Carbon::today()->subYear(), 'left_on' => null]);
    $quiet = Employee::factory()->create(['entered_on' => Carbon::today()->subYear(), 'left_on' => null]);

    // Mix of open and finished tasks for the busy employee — workload is
    // what they were responsible for during the period, not just their
    // current backlog, so the finished one must still count (2026-07-30).
    Task::factory()->count(3)->create([
        'project_id' => $project->id,
        'employee_id' => $busy->person_id,
        'status' => 'new',
        'starts_on' => Carbon::today(),
    ]);
    Task::factory()->create([
        'project_id' => $project->id,
        'employee_id' => $busy->person_id,
        'status' => 'finished',
        'starts_on' => Carbon::today(),
        'ends_on' => Carbon::today(),
    ]);
    Task::factory()->count(2)->create([
        'project_id' => $project->id,
        'employee_id' => $quiet->person_id,
        'status' => 'new',
        'starts_on' => Carbon::today(),
    ]);

    // period=month (not the "Aktuell" default): live workload deliberately
    // excludes finished tasks, this test is specifically about the
    // period-scoped count still including them.
    $response = $this->actingAs($user)->get(route('metrics.index', ['period' => 'month']));

    $response->assertSuccessful();
    $response->assertSee('4 Aufgaben');
    $response->assertSee('2 Aufgaben');
});

test('Aktuell (live, the default) counts a long-open task with no date-range boundary at all', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    $project = Project::factory()->create(['ends_on' => null]);
    $employee = Employee::factory()->create(['entered_on' => Carbon::today()->subYear(), 'left_on' => null]);

    // Started 3 months ago, still open, overdue for weeks — none of its
    // dates fall "in range" the way tasksInRange() scopes things, which is
    // exactly the point: Aktuell must not be a date-range filter at all.
    Task::factory()->create([
        'project_id' => $project->id,
        'employee_id' => $employee->person_id,
        'status' => 'in progress',
        'starts_on' => Carbon::today()->subMonths(3),
        'ends_on' => null,
        'due_on' => Carbon::today()->subDays(10),
    ]);
    // A finished task must be excluded from the live snapshot — it's not
    // part of what's currently open, regardless of when it touched today.
    Task::factory()->create([
        'project_id' => $project->id,
        'employee_id' => $employee->person_id,
        'status' => 'finished',
        'starts_on' => Carbon::today()->subMonths(3),
        'ends_on' => Carbon::today()->subMonths(2),
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index'));

    $response->assertSuccessful();
    $metrics = $response->viewData('metrics');

    $breakdown = $metrics->taskStatusBreakdown();
    expect($breakdown['inProgress'])->toBe(0);
    expect($breakdown['overdue'])->toBe(1);
    expect($breakdown['finished'])->toBe(0);

    $workload = $metrics->employeeWorkload()->firstWhere('employee.person_id', $employee->person_id);
    expect($workload->task_count)->toBe(1);

    $overdue = $metrics->overdueTasksSummary();
    expect($overdue['count'])->toBe(1);
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

test('average hours per week includes overtime but excludes holiday hours', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewmetrics');
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);

    $regular = WageService::factory()->create(['unit' => 'h']);
    $overtime = WageService::factory()->create(['unit' => 'h']);
    $holiday = WageService::factory()->create(['unit' => 'h']);
    ApplicationSettings::get()->update([
        'overtime_50_service_id' => $overtime->id,
        'holiday_service_id' => $holiday->id,
    ]);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create(['entered_on' => Carbon::parse('2026-01-01'), 'left_on' => null]);
    $project = Project::factory()->create(['ends_on' => null]);

    Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $regular->id,
        'project_id' => $project->id,
        'service_provided_on' => '2026-06-01',
        'amount' => 30,
    ]);
    Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $overtime->id,
        'project_id' => $project->id,
        'service_provided_on' => '2026-06-01',
        'amount' => 5,
    ]);
    Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $holiday->id,
        'project_id' => $project->id,
        'service_provided_on' => '2026-06-02',
        'amount' => 8,
    ]);

    $response = $this->actingAs($user)->get(route('metrics.index', [
        'period' => 'custom',
        'from' => '2026-06-01',
        'to' => '2026-06-07',
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
