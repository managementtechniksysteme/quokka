<?php

namespace Tests\Feature;

use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\User;
use App\Models\WageService;

function applicationSettingsUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function validApplicationSettingsPayload(array $overrides = []): array
{
    return array_merge([
        'company_id' => Company::factory()->create()->id,
        'currency_unit' => 'CHF',
        'accounting_min_amount' => 10,
        'task_due_soon_days' => 3,
        'prune_read_notifications' => false,
        'prune_sent_emails' => false,
    ], $overrides);
}

test('edit is forbidden without application-settings.update.general permission', function () {
    $user = applicationSettingsUser();

    $response = $this->actingAs($user)->get(route('application-settings.edit', ['tab' => 'general']));

    $response->assertForbidden();
});

test('edit general tab is shown for an authorized user', function () {
    $user = applicationSettingsUser(['application-settings.update.general']);

    $response = $this->actingAs($user)->get(route('application-settings.edit', ['tab' => 'general']));

    $response->assertSuccessful();
    $response->assertViewIs('application_settings.edit_general');
});

test('edit without a tab redirects to the general tab', function () {
    $user = applicationSettingsUser(['application-settings.update.general']);

    $response = $this->actingAs($user)->get(route('application-settings.edit'));

    $response->assertRedirect(route('application-settings.edit', ['tab' => 'general']));
});

test('update-general is forbidden without application-settings.update.general permission', function () {
    $user = applicationSettingsUser();

    $response = $this->actingAs($user)->post(route('application-settings.update-general'), validApplicationSettingsPayload());

    $response->assertForbidden();
});

test('update-general updates the settings and refreshes the cache', function () {
    $user = applicationSettingsUser(['application-settings.update.general']);

    $response = $this->actingAs($user)->post(
        route('application-settings.update-general'),
        validApplicationSettingsPayload(['currency_unit' => 'EUR'])
    );

    $response->assertRedirect(route('application-settings.edit', ['tab' => 'general']));
    $response->assertSessionHas('success');

    expect(ApplicationSettings::get()->currency_unit)->toBe('EUR');
});

test('update-general disassociates the holiday service when holiday_service_id is omitted', function () {
    $user = applicationSettingsUser(['application-settings.update.general']);
    $holidayService = WageService::factory()->create();

    $this->actingAs($user)->post(
        route('application-settings.update-general'),
        validApplicationSettingsPayload(['holiday_service_id' => $holidayService->id, 'holiday_yearly_allowance' => 20])
    );

    expect(ApplicationSettings::get()->holiday_service_id)->toBe($holidayService->id);

    $this->actingAs($user)->post(route('application-settings.update-general'), validApplicationSettingsPayload());

    expect(ApplicationSettings::get()->holiday_service_id)->toBeNull();
});

test('update-general requires distinct service ids for the different wage service slots', function () {
    $user = applicationSettingsUser(['application-settings.update.general']);
    $service = WageService::factory()->create();

    $response = $this->actingAs($user)->post(
        route('application-settings.update-general'),
        validApplicationSettingsPayload([
            'allowances_service_id' => $service->id,
            'overtime_50_service_id' => $service->id,
        ])
    );

    $response->assertSessionHasErrors(['allowances_service_id', 'overtime_50_service_id']);
});
