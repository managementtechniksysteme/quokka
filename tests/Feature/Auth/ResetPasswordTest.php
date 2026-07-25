<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

function validToken($user)
{
    return Password::broker()->createToken($user);
}

function invalidToken()
{
    return 'invalid-token';
}

function passwordResetGetRoute($token)
{
    return route('password.reset', $token);
}

function passwordResetPostRoute()
{
    return '/password/reset';
}

function successfulPasswordResetRoute()
{
    return route('home');
}

test('user can view a password reset form', function () {
    $user = User::factory()->create();

    $response = $this->get(passwordResetGetRoute($token = validToken($user)));

    $response->assertSuccessful();
    $response->assertViewIs('auth.passwords.reset');
    $response->assertViewHas('token', $token);
});

test('user cannot view a password reset form when authenticated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(passwordResetGetRoute(validToken($user)));

    $response->assertRedirect(route('home'));
});

test('user can reset password with valid token', function () {
    Event::fake();
    $user = User::factory()->create();

    $response = $this->post(passwordResetPostRoute(), [
        'token' => validToken($user),
        'username' => $user->username,
        'password' => 'new-awesome-password',
        'password_confirmation' => 'new-awesome-password',
    ]);

    $response->assertRedirect(successfulPasswordResetRoute());
    $this->assertEquals($user->username, $user->fresh()->username);
    $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
    $this->assertGuest();
    Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
        return $e->user->id === $user->id;
    });
});

test('user cannot reset password with invalid token', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $response = $this->from(passwordResetGetRoute(invalidToken()))->post(passwordResetPostRoute(), [
        'token' => invalidToken(),
        'username' => $user->username,
        'password' => 'new-awesome-password',
        'password_confirmation' => 'new-awesome-password',
    ]);

    $response->assertRedirect(passwordResetGetRoute(invalidToken()));
    $this->assertEquals($user->username, $user->fresh()->username);
    $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    $this->assertGuest();
});

test('user cannot reset password without providing a new password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $response = $this->from(passwordResetGetRoute($token = validToken($user)))->post(passwordResetPostRoute(), [
        'token' => $token,
        'username' => $user->username,
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertRedirect(passwordResetGetRoute($token));
    $response->assertSessionHasErrors('password');
    $this->assertTrue(session()->hasOldInput('username'));
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertEquals($user->username, $user->fresh()->username);
    $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    $this->assertGuest();
});

test('user cannot reset password without providing a username', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $response = $this->from(passwordResetGetRoute($token = validToken($user)))->post(passwordResetPostRoute(), [
        'token' => $token,
        'username' => '',
        'password' => 'new-awesome-password',
        'password_confirmation' => 'new-awesome-password',
    ]);

    $response->assertRedirect(passwordResetGetRoute($token));
    $response->assertSessionHasErrors('username');
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertEquals($user->username, $user->fresh()->username);
    $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    $this->assertGuest();
});
