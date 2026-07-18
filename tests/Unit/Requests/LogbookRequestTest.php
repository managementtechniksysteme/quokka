<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\LogbookStoreRequest;
use App\Http\Requests\LogbookUpdateRequest;

/**
 * LogbookStoreRequest/UpdateRequest::rules() derives each of start/end/driven
 * kilometres' size: bound from the *other two* submitted values - a genuinely
 * circular validation shape per the test-backfill plan's backlog. Store and
 * Update share identical logic, so these tests exercise it once via the Store
 * variant and spot-check Update separately to confirm it isn't out of sync.
 */
function logbookRulesFor(string $requestClass, array $data): array
{
    return $requestClass::create('/logbook', 'POST', $data)->rules();
}

test('a self-consistent triple derives matching size: bounds for all three fields', function () {
    $rules = logbookRulesFor(LogbookStoreRequest::class, [
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 50,
    ]);

    expect($rules['start_kilometres'])->toContain('size:100')
        ->and($rules['end_kilometres'])->toContain('size:150')
        ->and($rules['driven_kilometres'])->toContain('size:50');
});

test('an inconsistent triple derives size: bounds that reject the submitted values', function () {
    $rules = logbookRulesFor(LogbookStoreRequest::class, [
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 999,
    ]);

    // driven_kilometres itself is derived from start/end (150-100=50), not from
    // its own submitted value - so the bound is internally consistent with the
    // other two fields, deliberately ignoring what was actually submitted for it.
    expect($rules['driven_kilometres'])->toContain('size:50');
});

test('missing kilometre fields are still rejected by integer, without a size: bound or a crash', function () {
    $rules = logbookRulesFor(LogbookStoreRequest::class, []);

    expect($rules['start_kilometres'])->toBe('required|integer|min:0|lt:end_kilometres')
        ->and($rules['end_kilometres'])->toBe('required|integer|min:1|gt:start_kilometres')
        ->and($rules['driven_kilometres'])->toBe('required|integer|min:1');
});

test('a non-numeric kilometre field is still rejected by integer, without a size: bound or a crash', function () {
    $rules = logbookRulesFor(LogbookStoreRequest::class, [
        'start_kilometres' => 100,
        'end_kilometres' => 'not-a-number',
        'driven_kilometres' => 50,
    ]);

    expect($rules['start_kilometres'])->not->toContain('size:')
        ->and($rules['driven_kilometres'])->not->toContain('size:')
        // end_kilometres' own bound is derived from start/driven, neither of
        // which is the malformed field, so it's still computable.
        ->and($rules['end_kilometres'])->toContain('size:150');
});

test('LogbookUpdateRequest derives the same size: bounds as LogbookStoreRequest', function () {
    $data = [
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 50,
    ];

    expect(logbookRulesFor(LogbookUpdateRequest::class, $data))
        ->toBe(logbookRulesFor(LogbookStoreRequest::class, $data));
});
