<?php

use App\Models\ApplicationSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
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

// Global so it's callable both from test closures and from plain top-level
// factory-helper functions (which have no $this), without redeclaring it
// per file across the Feature/Unit namespaces.
function grantPermission(User $user, string $permission): void
{
    Permission::firstOrCreate(['name' => $permission]);

    $user->givePermissionTo($permission);
}
