<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

test('sends a password reset email for a registered username', function () {
    Notification::fake();
    $user = User::factory()->create(['username' => 'johndoe']);

    $response = $this->postJson('/api/forgot-password', ['username' => 'johndoe']);

    $response->assertSuccessful();
    $token = DB::table('password_reset_tokens')->first();
    expect($token)->not->toBeNull();
    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token) {
        return Hash::check($notification->token, $token->token) === true;
    });
});

test('returns an error for an unregistered username', function () {
    Notification::fake();

    $response = $this->postJson('/api/forgot-password', ['username' => 'nobody']);

    $response->assertStatus(422);
    Notification::assertNothingSent();
});

test('requires a username', function () {
    $response = $this->postJson('/api/forgot-password', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('username');
});
