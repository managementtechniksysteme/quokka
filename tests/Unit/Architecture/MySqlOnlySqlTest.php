<?php

namespace Tests\Unit\Architecture;

use Illuminate\Support\Facades\File;

/**
 * These MySQL-only SQL constructs broke repeatedly under sqlite (the test
 * database) while backfilling this app's tests, and in one case (Project's
 * `included_in_finances` typo, masked by field()) hid a real bug in
 * production too. Each has already been normalized to a portable form -
 * this test stops any of them from silently reappearing.
 */
test('app/ contains no MySQL-only SQL patterns known to break under sqlite', function () {
    $bannedPatterns = [
        'field()' => '/\bfield\s*\(/i',
        'curdate()' => '/\bcurdate\s*\(/i',
        'ISNULL()' => '/\bisnull\s*\(/i',
    ];

    $violations = [];

    foreach (File::allFiles(app_path()) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $codeLines = collect(file($file->getRealPath()))
            ->reject(fn (string $line) => preg_match('/^\s*(\/\/|\*|#)/', $line));

        $source = $codeLines->implode('');

        foreach ($bannedPatterns as $label => $pattern) {
            if (preg_match($pattern, $source)) {
                $violations[] = "{$file->getRelativePathname()}: {$label}";
            }
        }

        // group_concat(distinct ... separator ...) is MySQL-only - sqlite's
        // group_concat() rejects DISTINCT combined with a custom separator.
        // There's no portable single SQL string for this combination, so
        // the established fix is a DB::connection()->getDriverName() branch
        // (see Project::getReport()), not a rewrite - only flag it if a
        // file uses the combination without also branching on the driver.
        if (preg_match('/group_concat\(distinct[^)]*separator/i', $source) && ! str_contains($source, 'getDriverName()')) {
            $violations[] = "{$file->getRelativePathname()}: group_concat(distinct ... separator ...) without a getDriverName() branch";
        }
    }

    expect($violations)->toBe([]);
});
