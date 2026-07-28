<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\ConstructionReport;
use App\Models\Person;
use App\Models\User;

function smokeTestUser(): User
{
    $user = User::factory()->create();

    foreach ([
        'people.view', 'people.create',
        'companies.view', 'companies.create',
        'construction-reports.view.own',
        'finances-view',
        'application-settings-update',
    ] as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('key authenticated pages load without console errors or JavaScript exceptions', function () {
    $user = smokeTestUser();
    $person = Person::factory()->create();
    $company = Company::factory()->create();
    $report = ConstructionReport::factory()->create(['employee_id' => $user->employee_id]);

    $this->actingAs($user);

    $pages = visit([
        route('home'),
        route('people.index'),
        route('people.create'),
        route('people.show', $person),
        route('people.edit', $person),
        route('companies.index'),
        route('companies.create'),
        route('companies.show', $company),
        route('companies.edit', $company),
        route('construction-reports.show', $report),
        route('finances.index'),
        route('application-settings.edit'),
    ]);

    $pages->assertNoSmoke();
});
