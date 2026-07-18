<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AccountingStoreRequest;
use App\Http\Requests\AccountingUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\MaterialService;
use App\Models\WageService;

/**
 * AccountingStoreRequest/UpdateRequest::rules() dynamically computes min/max/lte
 * bounds from the selected service's type and unit, and (for hour-based wage
 * services) a start/end time difference - the most intricate conditional rules()
 * in the app, per the test-backfill plan's backlog. Store and Update share
 * identical logic, so these tests exercise it once via the Store variant and
 * spot-check Update separately to confirm it isn't out of sync.
 */
function rulesFor(string $requestClass, array $data): array
{
    return $requestClass::create('/accounting', 'POST', $data)->rules();
}

test('without a service_id, only the base rules apply', function () {
    $rules = rulesFor(AccountingStoreRequest::class, []);

    expect($rules['amount'])->toBe('required|numeric')
        ->and($rules)->not->toHaveKey('service_provided_started_at')
        ->and($rules)->not->toHaveKey('service_provided_ended_at');
});

test('a material service gets a min:0/multiple_of:0.01 amount rule and prohibits the time fields', function () {
    $service = MaterialService::factory()->create();

    $rules = rulesFor(AccountingStoreRequest::class, ['service_id' => $service->id]);

    expect($rules['amount'])->toBe('required|numeric|min:0|multiple_of:0.01')
        ->and($rules['service_provided_started_at'])->toBe('prohibited|nullable')
        ->and($rules['service_provided_ended_at'])->toBe('prohibited|nullable');
});

test('a non-hour wage service prohibits the time fields and bounds amount to accounting_min_amount', function () {
    ApplicationSettings::get()->update(['accounting_min_amount' => 0.5]);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'km']);

    $rules = rulesFor(AccountingStoreRequest::class, ['service_id' => $service->id]);

    expect($rules['amount'])->toBe('required|numeric|min:0.5|multiple_of:0.5')
        ->and($rules['service_provided_started_at'])->toBe('prohibited|nullable')
        ->and($rules['service_provided_ended_at'])->toBe('prohibited|nullable');
});

test('an hour-based wage service requires time fields and bounds amount by the time difference', function () {
    ApplicationSettings::get()->update(['services_hour_unit' => 'h', 'accounting_min_amount' => 0.5]);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'h']);

    $rules = rulesFor(AccountingStoreRequest::class, [
        'service_id' => $service->id,
        'service_provided_started_at' => '08:00',
        'service_provided_ended_at' => '10:20',
    ]);

    // 140 minutes actual difference, floored to the nearest 30-minute
    // (accounting_min_amount = 0.5h) increment -> 120 minutes -> 2 hours.
    expect($rules['service_provided_started_at'])->toBe('required|date_format:H:i|before:service_provided_ended_at')
        ->and($rules['service_provided_ended_at'])->toBe('required|date_format:H:i|after:service_provided_started_at')
        ->and($rules['amount'])->toBe('required|numeric|min:0.5|multiple_of:0.5|lte:2');
});

test('an hour-based wage service without time fields still requires them but skips the lte bound', function () {
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'h']);

    $rules = rulesFor(AccountingStoreRequest::class, ['service_id' => $service->id]);

    expect($rules['service_provided_started_at'])->toBe('required|date_format:H:i|before:service_provided_ended_at')
        ->and($rules['service_provided_ended_at'])->toBe('required|date_format:H:i|after:service_provided_started_at')
        ->and($rules['amount'])->not->toContain('lte:');
});

test('an hour-based wage service with a malformed time still requires them but skips the lte bound', function () {
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'h']);

    $rules = rulesFor(AccountingStoreRequest::class, [
        'service_id' => $service->id,
        'service_provided_started_at' => 'not-a-time',
        'service_provided_ended_at' => '10:20',
    ]);

    expect($rules['amount'])->not->toContain('lte:');
});

test('a nonexistent service_id falls back to the base amount rule', function () {
    $rules = rulesFor(AccountingStoreRequest::class, ['service_id' => 999999]);

    expect($rules['amount'])->toBe('required|numeric');
});

test('AccountingUpdateRequest computes the same lte bound as AccountingStoreRequest', function () {
    ApplicationSettings::get()->update(['services_hour_unit' => 'h', 'accounting_min_amount' => 0.5]);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'h']);

    $data = [
        'service_id' => $service->id,
        'service_provided_started_at' => '08:00',
        'service_provided_ended_at' => '10:20',
    ];

    expect(rulesFor(AccountingUpdateRequest::class, $data)['amount'])
        ->toBe(rulesFor(AccountingStoreRequest::class, $data)['amount']);
});
