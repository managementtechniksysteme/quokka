<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FA\Google2FA;

function homeGetRoute()
{
    return route('home');
}

function reauthenticateGetRoute()
{
    return route('reauthenticate');
}

function reauthenticatePostRoute()
{
    return route('reauthenticate');
}

function reauthenticateRequiredRoute()
{
    return '/_test/reauthenticated';
}

// TODO: localisation
function passwordLabel()
{
    return 'Passwort';
}

// TODO: localisation
function otpLabel()
{
    return 'Einmalpasswort';
}

beforeEach(function () {
    Route::get('/_test/reauthenticated', function () {
        return 'OK';
    })->middleware(['auth', 'reauth']);
});

test('user cannot view a reauthenticate form', function () {
    $response = $this->get(reauthenticateGetRoute());

    $response->assertRedirect(route('login'));
});

test('user is redirected to the reauthenticate page when required and authenticated', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get(reauthenticateRequiredRoute());

    $response->assertRedirect(reauthenticateGetRoute());
});

test('user is redirected to home when logged in and trying to access reauthentication form', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get(reauthenticateGetRoute());

    $response->assertRedirect(homeGetRoute());
});

test('user sees password field in reauthenticate form', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response->assertSuccessful();
    $response->assertViewIs('auth.reauthenticate');
    $response->assertSee(passwordLabel());
});

test('user sees otp field in reauthenticate form when otp is enabled', function () {
    $user = User::factory()->create([
        'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
    ]);

    $response = $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response->assertSuccessful();
    $response->assertViewIs('auth.reauthenticate');
    $response->assertSee(otpLabel());
});

test('user can reauthenticate with correct credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
    ]);

    $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response = $this->actingAs($user)->post(reauthenticatePostRoute(), [
        'password' => $password,
    ]);

    $response->assertRedirect(reauthenticateRequiredRoute());
});

test('user can reauthenticate in two steps with correct credentials', function () {
    $google2fa = new Google2FA();

    $otp_secret = $google2fa->generateSecretKey();

    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
        'otp_secret' => encrypt($otp_secret),
    ]);

    $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response = $this->actingAs($user)->post(reauthenticatePostRoute(), [
        'password' => $password,
        'one_time_password' => $google2fa->getCurrentOtp($otp_secret),
    ]);

    $response->assertRedirect(reauthenticateRequiredRoute());
});

test('user can not reauthenticate with incorrect password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
    ]);

    $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response = $this->actingAs($user)->post(reauthenticatePostRoute(), [
        'password' => 'invalid-password',
    ]);

    $response->assertRedirect(reauthenticateGetRoute());
    $response->assertSessionHasErrors('password');
    $this->assertFalse(session()->hasOldInput('password'));
});

test('user can not reauthenticate in two steps with incorrect otp', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
        'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
    ]);

    $this->actingAs($user)->followingRedirects()->get(reauthenticateRequiredRoute());

    $response = $this->actingAs($user)->post(reauthenticatePostRoute(), [
        'password' => $password,
        'one_time_password' => '123456',
    ]);

    $response->assertRedirect(reauthenticateGetRoute());
    $response->assertSessionHasErrors('one_time_password');
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertFalse(session()->hasOldInput('one_time_password'));
});
