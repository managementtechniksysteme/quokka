<?php

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Models\User;
use App\Models\WageService;
use App\Helpers\Number;
use Carbon\Carbon;

// Pflegeurlaub (nursing holiday): a configurable wage service that counts as
// time off like the regular holiday, and is shown as a total at the bottom of
// the accounting report (like the private kilometres).

function configureNursingHolidayService(string $unit = 'T'): WageService
{
    $service = WageService::factory()->create(['name' => 'Pflegeurlaub', 'unit' => $unit]);

    ApplicationSettings::get()->update(['nursing_holiday_service_id' => $service->id, 'services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();

    return $service;
}

function renderAccountingReport(array $employees, array $nursingHolidays, ?WageService $nursingHolidayService): string
{
    return view('latex.accounting_report', [
        'report' => collect(),
        'employees' => $employees,
        'private_kilometres' => [],
        'kilometre_costs' => 0,
        'project' => null,
        'service' => null,
        'allowancesService' => null,
        'holidayService' => null,
        'nursingHolidayService' => $nursingHolidayService,
        'nursing_holidays' => $nursingHolidays,
        'currencyUnit' => '€',
        'start' => null,
        'end' => null,
    ])->render();
}

test('nursing holidays are summed per employee within the given period', function () {
    $service = configureNursingHolidayService();
    $employee = User::factory()->create()->employee;
    $otherEmployee = User::factory()->create()->employee;

    foreach (['2026-10-30' => 1, '2026-11-02' => 1, '2026-11-03' => 0.5, '2026-12-01' => 1] as $date => $amount) {
        Accounting::factory()->create(['employee_id' => $employee->person_id, 'service_id' => $service->id, 'service_provided_on' => $date, 'amount' => $amount]);
    }
    Accounting::factory()->create(['employee_id' => $otherEmployee->person_id, 'service_id' => $service->id, 'service_provided_on' => '2026-11-05', 'amount' => 1]);
    Accounting::factory()->create(['employee_id' => $employee->person_id, 'service_id' => WageService::factory()->create()->id, 'service_provided_on' => '2026-11-05', 'amount' => 8]);

    expect($employee->getNursingHolidays(Carbon::parse('2026-11-01'), Carbon::parse('2026-11-30')))->toBe(1.5)
        ->and($employee->getNursingHolidays(null, null))->toBe(3.5)
        ->and($otherEmployee->getNursingHolidays(Carbon::parse('2026-11-01'), Carbon::parse('2026-11-30')))->toBe(1.0);
});

test('nursing holidays are zero while no nursing holiday service is configured', function () {
    $employee = User::factory()->create()->employee;

    expect($employee->getNursingHolidays(null, null))->toBe(0.0);
});

test('worked hours still add up while no nursing holiday service is configured', function () {
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();
    $workService = WageService::factory()->create(['unit' => 'h']);
    $employee = User::factory()->create()->employee;

    Accounting::factory()->create(['employee_id' => $employee->person_id, 'service_id' => $workService->id, 'service_provided_on' => Carbon::today()->toDateString(), 'amount' => 6]);

    expect($employee->m_t_d_hourly_based_services)->toEqual(6)
        ->and(Accounting::getReport(['employee_ids' => [$employee->person_id]])->sum('amount_hours'))->toEqual(6);
});

test('an hour-based nursing holiday service does not count as worked hours', function () {
    $service = configureNursingHolidayService('h');
    $workService = WageService::factory()->create(['unit' => 'h']);
    $employee = User::factory()->create()->employee;
    $today = Carbon::today()->toDateString();

    Accounting::factory()->create(['employee_id' => $employee->person_id, 'service_id' => $workService->id, 'service_provided_on' => $today, 'amount' => 6]);
    Accounting::factory()->create(['employee_id' => $employee->person_id, 'service_id' => $service->id, 'service_provided_on' => $today, 'amount' => 2]);

    $report = Accounting::getReport(['employee_ids' => [$employee->person_id]]);

    expect($employee->m_t_d_hourly_based_services)->toEqual(6)
        ->and($report->sum('amount_hours'))->toEqual(6);
});

test('the accounting report shows the nursing holidays of a single employee', function () {
    $service = configureNursingHolidayService();
    $employee = Employee::with(['user', 'person'])->find(User::factory()->create()->employee_id);

    $latex = renderAccountingReport([$employee], [$employee->user->username => 2.5], $service);

    expect($latex)->toContain('\textbf{Pflegeurlaub: }')
        ->toContain(Number::toLocal(2.5).' T');
});

test('the accounting report shows the nursing holidays per employee and their sum', function () {
    $service = configureNursingHolidayService();
    $first = Employee::with(['user', 'person'])->find(User::factory()->create(['username' => 'aaa'])->employee_id);
    $second = Employee::with(['user', 'person'])->find(User::factory()->create(['username' => 'bbb'])->employee_id);

    $latex = renderAccountingReport([$first, $second], ['aaa' => 1, 'bbb' => 2], $service);

    expect($latex)->toContain('AAA: 1 T')
        ->toContain('BBB: 2 T')
        ->toContain('Summe: 3 T');
});

test('the accounting report has no nursing holiday line while no service is configured', function () {
    $employee = Employee::with(['user', 'person'])->find(User::factory()->create()->employee_id);

    expect(renderAccountingReport([$employee], [], null))->not->toContain('Pflegeurlaub');
});

test('the accounting report with nursing holidays compiles to a pdf', function (bool $multipleEmployees) {
    $service = configureNursingHolidayService();
    ApplicationSettings::get()->update(['kilometre_costs' => 0.42]);
    ApplicationSettings::refreshCache();

    $user = User::factory()->create();
    foreach (['accounting.view.own', 'accounting.view.other', 'accounting.createpdf'] as $permission) {
        grantPermission($user, $permission);
    }
    $other = User::factory()->create();

    Accounting::factory()->create(['employee_id' => $user->employee_id, 'service_id' => $service->id, 'service_provided_on' => '2026-11-02', 'amount' => 1]);
    Accounting::factory()->create(['employee_id' => $other->employee_id, 'service_id' => $service->id, 'service_provided_on' => '2026-11-03', 'amount' => 2]);

    $response = $this->actingAs($user)->get(route('accounting.download', [
        'employee_ids' => $multipleEmployees ? [$user->employee_id, $other->employee_id] : [$user->employee_id],
        'start' => '2026-11-01',
        'end' => '2026-11-30',
    ]));

    $response->assertSuccessful();
    expect($response->streamedContent() ?: $response->getContent())->toStartWith('%PDF');
})->with(['single employee' => false, 'multiple employees' => true])->group('pdflatex');
