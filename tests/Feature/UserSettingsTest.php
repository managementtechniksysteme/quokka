<?php

namespace Tests\Feature;

use App\Models\NotificationType;
use App\Models\User;
use Database\Seeders\NotificationTypesSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

// 1x1 transparent PNG, small enough to keep the test payload trivial.
const TINY_PNG_BASE64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

test('edit general tab is shown', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('user-settings.edit', ['tab' => 'general']));

    $response->assertSuccessful();
    $response->assertViewIs('user_settings.edit_general');
});

test('edit interface tab is shown', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('user-settings.edit', ['tab' => 'interface']));

    $response->assertSuccessful();
    $response->assertViewIs('user_settings.edit_interface');
});

test('edit notifications tab is shown with the notification category catalogue', function () {
    $user = User::factory()->create();
    // edit_notifications.blade.php indexes into $notifications by every notification
    // class referenced in the category catalogue, so all real types must exist.
    $this->seed(NotificationTypesSeeder::class);

    $response = $this->actingAs($user)->get(route('user-settings.edit', ['tab' => 'notifications']));

    $response->assertSuccessful();
    $response->assertViewIs('user_settings.edit_notifications');
    $response->assertViewHas('notificationCategories');
});

test('edit without a tab redirects to the general tab', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('user-settings.edit'));

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'general']));
});

test('edit security tab requires reauthentication when navigated to fresh', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('user-settings.edit', ['tab' => 'security']));

    $response->assertRedirect(route('reauthenticate'));
});

test('edit security tab is shown once reauthenticated', function () {
    $user = User::factory()->create();
    Session::put('reauthenticated', true);

    $response = $this->actingAs($user)->get(route('user-settings.edit', ['tab' => 'security']));

    $response->assertSuccessful();
    $response->assertViewIs('user_settings.edit_security');
});

test('update-interface updates the acting user\'s settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('user-settings.update-interface'), [
        'list_pagination_size' => 25,
        'show_finished_items' => true,
        'show_signed_reports' => true,
        'show_only_own_reports' => false,
        'task_comments_sort_newest_first' => true,
        'accounting_expand_errors' => false,
    ]);

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'interface']));
    expect($user->settings->fresh()->list_pagination_size)->toBe(25);
});

test('update-interface forces show_cost_estimates false without projects.view.estimates permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('user-settings.update-interface'), [
        'list_pagination_size' => 25,
        'show_finished_items' => true,
        'show_signed_reports' => true,
        'show_only_own_reports' => false,
        'show_cost_estimates' => true,
        'task_comments_sort_newest_first' => true,
        'accounting_expand_errors' => false,
    ]);

    expect($user->settings->fresh()->show_cost_estimates)->toBeFalse();
});

test('update-interface honours show_cost_estimates with projects.view.estimates permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view.estimates');

    $this->actingAs($user)->post(route('user-settings.update-interface'), [
        'list_pagination_size' => 25,
        'show_finished_items' => true,
        'show_signed_reports' => true,
        'show_only_own_reports' => false,
        'show_cost_estimates' => true,
        'task_comments_sort_newest_first' => true,
        'accounting_expand_errors' => false,
    ]);

    expect($user->settings->fresh()->show_cost_estimates)->toBeTrue();
});

test('update-notifications updates notify_self', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('user-settings.update-notifications'), ['notify_self' => true]);

    expect($user->settings->fresh()->notify_self)->toBeTrue();
});

test('update-notification-targets syncs the email and webpush pivots', function () {
    $user = User::factory()->create();
    $emailType = NotificationType::factory()->create();
    $webpushType = NotificationType::factory()->create();

    $this->actingAs($user)->post(route('user-settings.update-notification-targets'), [
        'email' => [$emailType->id],
        'webpush' => [$webpushType->id],
    ]);

    expect($user->notificationsViaEmail()->pluck('notification_types.id')->all())->toBe([$emailType->id]);
    expect($user->notificationsViaWebPush()->pluck('notification_types.id')->all())->toBe([$webpushType->id]);
});

test('update-notification-targets detaches when omitted', function () {
    $user = User::factory()->create();
    $emailType = NotificationType::factory()->create();
    $user->notificationsViaEmail()->attach($emailType->id, ['notification_target_type' => 'email']);

    $this->actingAs($user)->post(route('user-settings.update-notification-targets'), []);

    expect($user->notificationsViaEmail()->count())->toBe(0);
});

test('update-signature stores a signature for the acting user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('user-settings.update-signature'), [
        'signature' => TINY_PNG_BASE64,
    ]);

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'general']));
    expect($user->fresh()->signature())->not->toBeNull();
});

test('update-password updates the password and logs out other devices', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('user-settings.update-password'), [
        'password' => 'a-new-strong-password',
        'password_confirmation' => 'a-new-strong-password',
    ]);

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'security']));
    expect(Hash::check('a-new-strong-password', $user->fresh()->password))->toBeTrue();
});

test('otp-enable puts a fresh otp secret and qr code into the session', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('user-settings.otp-enable'));

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'security']));
    $response->assertSessionHas('otpSecret');
    $response->assertSessionHas('qrCode');
});

test('otp-confirm enables 2fa with a valid code', function () {
    $user = User::factory()->create();
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();

    Session::put('otpSecret', $secret);

    $response = $this->actingAs($user)->withSession(['otpSecret' => $secret])->post(route('user-settings.otp-confirm'), [
        config('auth2fa.otp_input') => $google2fa->getCurrentOtp($secret),
    ]);

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'security']));
    expect($user->fresh()->otp_secret)->not->toBeNull();
});

test('otp-confirm rejects an invalid code', function () {
    $user = User::factory()->create();
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();

    $response = $this->actingAs($user)->withSession(['otpSecret' => $secret])->post(route('user-settings.otp-confirm'), [
        config('auth2fa.otp_input') => '000000',
    ]);

    $response->assertSessionHasErrors(config('auth2fa.otp_input'));
    expect($user->fresh()->otp_secret)->toBeNull();
});

test('otp-disable clears the otp secret', function () {
    $user = User::factory()->create(['otp_secret' => encrypt('some-secret')]);

    $response = $this->actingAs($user)->post(route('user-settings.otp-disable'));

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'security']));
    expect($user->fresh()->otp_secret)->toBeNull();
});
