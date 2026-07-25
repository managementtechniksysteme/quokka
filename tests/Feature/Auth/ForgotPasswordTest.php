<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

function passwordRequestRoute()
{
    return route('password.request');
}

function passwordEmailGetRoute()
{
    return route('password.email');
}

function passwordEmailPostRoute()
{
    return route('password.email');
}

test('user can view an email password form', function () {
    $response = $this->get(passwordRequestRoute());

    $response->assertSuccessful();
    $response->assertViewIs('auth.passwords.email');
});

test('user cannot view an email password form when authenticated', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get(passwordRequestRoute());

    $response->assertRedirect(route('home'));
});

test('user receives an email with a password reset link', function () {
    Notification::fake();
    $user = User::factory()->create([
        'username' => 'johndoe',
    ]);

    $this->post(passwordEmailPostRoute(), [
        'username' => 'johndoe',
    ]);

    $this->assertNotNull($token = DB::table('password_reset_tokens')->first());
    Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
        return Hash::check($notification->token, $token->token) === true;
    });
});

test('user does not receive email when not registered', function () {
    Notification::fake();

    $response = $this->from(passwordEmailGetRoute())->post(passwordEmailPostRoute(), [
        'username' => 'nobody',
    ]);

    $response->assertRedirect(passwordEmailGetRoute());
    $response->assertSessionHasErrors('username');
    Notification::assertNotSentTo(User::factory()->make(['username' => 'nobody']), ResetPassword::class);
});

test('username is required', function () {
    $response = $this->from(passwordEmailGetRoute())->post(passwordEmailPostRoute(), []);

    $response->assertRedirect(passwordEmailGetRoute());
    $response->assertSessionHasErrors('username');
});
