<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use PragmaRX\Google2FA\Google2FA;

function successfulLoginRoute()
{
    return route('home');
}

function loginPostRoute()
{
    return route('login');
}

function otpGetRoute()
{
    return route('otp');
}

function logoutRoute()
{
    return route('logout');
}

function successfulLogoutRoute()
{
    return '/';
}

function tooManyLoginAttemptsMessage()
{
    return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
}

function responseUrl(TestResponse $response)
{
    return $response->headers->get('Location');
}

test('user can view a login form', function () {
    $response = $this->get(route('login'));

    $response->assertSuccessful();
    $response->assertViewIs('auth.login');
});

test('user cannot view a login form when authenticated', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get(route('login'));

    $response->assertRedirect(route('home'));
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
    ]);

    $response = $this->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => $password,
    ]);

    $response->assertRedirect(successfulLoginRoute());
    $this->assertAuthenticatedAs($user);
});

test('user cannot view otp form without signed link', function () {
    $response = $this->get(otpGetRoute());

    $response->assertForbidden();
});

test('user cannot view otp form when authenticated', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get(otpGetRoute());

    $response->assertRedirect(route('home'));
});

test('user is redirected to otp form when otp is enabled and correct credentials are provided', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
        'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
    ]);

    $response = $this->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => $password,
    ]);

    // AuthenticatesUsers2FA::login() uses auth()->once() to peek at the user's 2FA
    // status; that sets the guard's user for this request. Because the HTTP calls in
    // this test share one booted application, that state would otherwise leak into
    // the assertGuest() check below.
    auth()->logout();

    $redirectUrl = responseUrl($response);

    $response->assertRedirect();
    $this->assertStringContainsString(otpGetRoute(), $redirectUrl);
    $this->assertGuest();
});

test('user can login in two steps with correct credentials', function () {
    $google2fa = new Google2FA();

    $otp_secret = $google2fa->generateSecretKey();

    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
        'otp_secret' => encrypt($otp_secret),
    ]);

    $response = $this->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => $password,
    ]);

    // See comment in the "redirected to otp form" test above.
    auth()->logout();

    $otpUrl = responseUrl($response);

    $this->get($otpUrl);

    $response = $this->post($otpUrl, [
        'user' => encrypt($user->getAuthIdentifier()),
        'one_time_password' => $google2fa->getCurrentOtp($otp_secret),
    ]);

    $response->assertRedirect(successfulLoginRoute());
    $this->assertAuthenticatedAs($user);
});

test('remember me functionality', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
    ]);

    $response = $this->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => $password,
        'remember' => 'on',
    ]);

    $user = $user->fresh();

    $response->assertRedirect(successfulLoginRoute());
    // The cookie's password segment is an HMAC of the password hash (SessionGuard::
    // hashPasswordForCookie()), not the raw hash, so recaller cookies don't leak it.
    $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
        $user->employee_id,
        $user->getRememberToken(),
        Auth::guard()->hashPasswordForCookie($user->password),
    ]));
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('i-love-laravel'),
    ]);

    $response = $this->from(route('login'))->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => 'invalid-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('username');
    $this->assertTrue(session()->hasOldInput('username'));
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertGuest();
});

test('user cannot login in two steps with incorrect otp', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
        'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
    ]);

    $response = $this->post(loginPostRoute(), [
        'username' => $user->username,
        'password' => $password,
    ]);

    // See comment in the "redirected to otp form" test above.
    auth()->logout();

    $otpUrl = responseUrl($response);

    $this->get($otpUrl);

    $response = $this->post($otpUrl, [
        'user' => encrypt($user->getAuthIdentifier()),
        'one_time_password' => '123456',
    ]);

    // TODO: query parameters are switched in signed url, so we can't assert the exact
    // redirect target (`$response->assertRedirect($otpUrl)`) here.
    $this->assertStringContainsString(otpGetRoute(), responseUrl($response));
    $response->assertSessionHasErrors('one_time_password');
    $this->assertFalse(session()->hasOldInput('one_time_password'));
    $this->assertGuest();
});

test('user cannot login with username that does not exist', function () {
    $response = $this->from(route('login'))->post(loginPostRoute(), [
        'username' => 'nobody',
        'password' => 'invalid-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('username');
    $this->assertTrue(session()->hasOldInput('username'));
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertGuest();
});

test('user can logout', function () {
    $this->be(User::factory()->create());

    $response = $this->post(logoutRoute());

    $response->assertRedirect(successfulLogoutRoute());
    $this->assertGuest();
});

test('user cannot logout when not authenticated', function () {
    $response = $this->post(logoutRoute());

    $response->assertRedirect(successfulLogoutRoute());
    $this->assertGuest();
});

test('user cannot make more than five attempts in one minute', function () {
    $user = User::factory()->create([
        'password' => Hash::make($password = 'i-love-laravel'),
    ]);

    foreach (range(0, 5) as $_) {
        $response = $this->from(route('login'))->post(loginPostRoute(), [
            'username' => $user->username,
            'password' => 'invalid-password',
        ]);
    }

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('username');
    $this->assertMatchesRegularExpression(
        tooManyLoginAttemptsMessage(),
        collect(
            $response
                ->baseResponse
                ->getSession()
                ->get('errors')
                ->getBag('default')
                ->get('username')
        )->first()
    );
    $this->assertTrue(session()->hasOldInput('username'));
    $this->assertFalse(session()->hasOldInput('password'));
    $this->assertGuest();
});
