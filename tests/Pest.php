<?php

use App\Models\ApplicationSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        // ApplicationSettings is a real app-wide singleton row in production
        // (created via a setup flow); many code paths assume it always exists.
        ApplicationSettings::factory()->create();
        ApplicationSettings::refreshCache();
    })
    ->in('Feature', 'Unit');
