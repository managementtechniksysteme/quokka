<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use PragmaRX\Google2FA\Google2FA;

function loginPayload(User $user, string $password = 'i-love-laravel', array $overrides = []): array
{
    return array_merge([
        'username' => $user->username,
        'password' => $password,
        'device_name' => 'test-device',
    ], $overrides);
}

test('token returns a token pair for valid credentials', function () {
    $user = User::factory()->create(['password' => Hash::make('i-love-laravel')]);

    $response = $this->postJson('/api/token', loginPayload($user));

    $response->assertSuccessful();
    $response->assertJsonStructure(['token', 'refresh_token']);
});

test('token rejects an invalid password', function () {
    $user = User::factory()->create(['password' => Hash::make('i-love-laravel')]);

    $response = $this->postJson('/api/token', loginPayload($user, 'wrong-password'));

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('username');
});

test('token requires the second factor when otp is enabled', function () {
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
        'otp_secret' => encrypt($secret),
    ]);

    $response = $this->postJson('/api/token', loginPayload($user));

    $response->assertSuccessful();
    $response->assertJsonStructure(['otp_url']);
    $response->assertJsonMissing(['token']);
});

test('otp endpoint rejects an unsigned request', function () {
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
        'otp_secret' => encrypt($secret),
    ]);

    $response = $this->postJson('/api/otp', loginPayload($user, 'i-love-laravel', [
        'one_time_password' => $google2fa->getCurrentOtp($secret),
    ]));

    $response->assertForbidden();
});

test('the full login-then-otp flow issues tokens for a correct code', function () {
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
        'otp_secret' => encrypt($secret),
    ]);

    $otpUrl = $this->postJson('/api/token', loginPayload($user))->json('otp_url');
    $path = parse_url($otpUrl, PHP_URL_PATH).'?'.parse_url($otpUrl, PHP_URL_QUERY);

    $response = $this->postJson($path, loginPayload($user, 'i-love-laravel', [
        'one_time_password' => $google2fa->getCurrentOtp($secret),
    ]));

    $response->assertSuccessful();
    $response->assertJsonStructure(['token', 'refresh_token']);
});

test('the otp flow rejects an incorrect code', function () {
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
        'otp_secret' => encrypt($secret),
    ]);

    $otpUrl = $this->postJson('/api/token', loginPayload($user))->json('otp_url');
    $path = parse_url($otpUrl, PHP_URL_PATH).'?'.parse_url($otpUrl, PHP_URL_QUERY);

    $response = $this->postJson($path, loginPayload($user, 'i-love-laravel', [
        'one_time_password' => '000000',
    ]));

    $response->assertUnprocessable();
});

test('refresh issues a new token pair for a token with the refresh ability', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-device', ['refresh'])->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")->postJson('/api/token/refresh');

    $response->assertSuccessful();
    $response->assertJsonStructure(['token', 'refresh_token']);
});

test('refresh is forbidden for a token without the refresh ability', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-device', ['authenticate'])->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")->postJson('/api/token/refresh');

    $response->assertForbidden();
});
