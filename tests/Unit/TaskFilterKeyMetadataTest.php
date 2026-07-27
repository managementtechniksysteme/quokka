<?php

namespace Tests\Unit;

use App\Models\Task;

test('ist keys are grouped into one enum entry with all values', function () {
    $metadata = Task::filterKeyMetadata();

    $ist = collect($metadata)->firstWhere('prefix', 'ist:');

    expect($ist)->not->toBeNull();
    expect($ist['kind'])->toBe('enum');

    $values = collect($ist['values'])->pluck('value');

    expect($values)->toContain('privat', 'niedrig', 'mittel', 'hoch', 'neu', 'in_bearbeitung', 'ib', 'erledigt', 'verrechnet', 'nicht_verrechnet', 'nv', 'garantie', 'überfällig', 'bald_fällig');
});

test('enum aliases sharing a label with an earlier value are flagged as duplicates, not removed', function () {
    $metadata = Task::filterKeyMetadata();

    $ist = collect($metadata)->firstWhere('prefix', 'ist:');
    $values = collect($ist['values']);

    // 'ist:ib' / 'ist:nv' are valid aliases for 'ist:in_bearbeitung' / 'ist:nicht_verrechnet'
    // (same label) -- they stay in the data (still typeable/recognized), only
    // flagged so the frontend doesn't list them a second time as their own row.
    expect($values->firstWhere('value', 'in_bearbeitung')['duplicate'])->toBeFalse();
    expect($values->firstWhere('value', 'ib')['duplicate'])->toBeTrue();
    expect($values->firstWhere('value', 'nicht_verrechnet')['duplicate'])->toBeFalse();
    expect($values->firstWhere('value', 'nv')['duplicate'])->toBeTrue();
});

test('lookup keys appear as separate lookup entries', function () {
    $metadata = Task::filterKeyMetadata();

    $lookupPrefixes = collect($metadata)->where('kind', 'lookup')->pluck('prefix');

    expect($lookupPrefixes)->toContain('projekt:', 'p:', 'firma:', 'f:', 'verantwortlich:', 'v:', 'beteiligt:', 'b:');
});

test('lookup aliases sharing a label with an earlier prefix are flagged as duplicates, not removed', function () {
    $metadata = Task::filterKeyMetadata();

    $lookups = collect($metadata)->where('kind', 'lookup')->keyBy('prefix');

    // 'p:'/'f:'/'v:'/'b:' are valid short aliases for 'projekt:'/'firma:'/'verantwortlich:'/'beteiligt:'
    // (same label) -- they stay valid for search (the FilterSuggestionController
    // and the frontend's own recognition both need the full list), only
    // flagged so the frontend doesn't list them a second time as their own row.
    expect($lookups['projekt:']['duplicate'])->toBeFalse();
    expect($lookups['p:']['duplicate'])->toBeTrue();
    expect($lookups['firma:']['duplicate'])->toBeFalse();
    expect($lookups['f:']['duplicate'])->toBeTrue();
    expect($lookups['verantwortlich:']['duplicate'])->toBeFalse();
    expect($lookups['v:']['duplicate'])->toBeTrue();
    expect($lookups['beteiligt:']['duplicate'])->toBeFalse();
    expect($lookups['b:']['duplicate'])->toBeTrue();
});

test('every metadata entry has a non-empty label', function () {
    $metadata = Task::filterKeyMetadata();

    foreach ($metadata as $entry) {
        expect($entry['label'])->not->toBeEmpty();

        if ($entry['kind'] === 'enum') {
            foreach ($entry['values'] as $value) {
                expect($value['label'])->not->toBeEmpty();
            }
        }
    }
});
