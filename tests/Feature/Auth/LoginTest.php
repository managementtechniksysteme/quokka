<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function successfulLoginRoute()
    {
        return route('home');
    }

    protected function loginGetRoute()
    {
        return route('login');
    }

    protected function loginPostRoute()
    {
        return route('login');
    }

    protected function otpGetRoute()
    {
        return route('otp');
    }

    protected function logoutRoute()
    {
        return route('logout');
    }

    protected function successfulLogoutRoute()
    {
        return '/';
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    protected function getTooManyLoginAttemptsMessage()
    {
        return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
    }

    protected function responseUrl(TestResponse $response)
    {
        return $response->headers->get('Location');
    }

    public function test_user_can_view_a_login_form()
    {
        $response = $this->get($this->loginGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->loginGetRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => $password,
        ]);

        $expected = $this->app->make('auth')->user();

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_view_otp_form_without_signed_link()
    {
        $response = $this->get($this->otpGetRoute());

        $response->assertForbidden();
    }

    public function test_user_cannot_view_otp_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->otpGetRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    // TODO: auth logout not ideal (is done automatically by auth once)
    public function test_user_is_redirected_to_otp_form_when_otp_is_enabled_and_correct_credentials_are_provided()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => $password,
        ]);

        // TODO: this is wrong
        auth()->logout();

        $redirectUrl = $this->responseUrl($response);

        $response->assertRedirect();
        $this->assertStringContainsString($this->otpGetRoute(), $redirectUrl);
        $this->assertGuest();
    }

    // TODO: auth logout not ideal (is done automatically by auth once)
    public function test_user_can_login_in_two_steps_with_correct_credentials()
    {
        $google2fa = new Google2FA();

        $otp_secret = $google2fa->generateSecretKey();

        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'otp_secret' => encrypt($otp_secret),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => $password,
        ]);

        // TODO: this is wrong
        auth()->logout();

        $otpUrl = $this->responseUrl($response);

        $this->get($otpUrl);

        $response = $this->post($otpUrl, [
            'user' => encrypt($user->getAuthIdentifier()),
            'one_time_password' => $google2fa->getCurrentOtp($otp_secret),
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    public function test_remember_me_functionality()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => $password,
            'remember' => 'on',
        ]);

        $user = $user->fresh();

        $response->assertRedirect($this->successfulLoginRoute());
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->employee_id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('i-love-laravel'),
        ]);

        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('username');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    // TODO: auth logout not ideal (is done automatically by auth once)
    // TODO: query parameters are switched in signed url --> how to check redirect?
    public function test_user_cannot_login_in_two_steps_with_incorrect_otp()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'username' => $user->username,
            'password' => $password,
        ]);

        // TODO: this is wrong
        auth()->logout();

        $otpUrl = $this->responseUrl($response);

        $this->get($otpUrl);

        $response = $this->post($otpUrl, [
            'user' => encrypt($user->getAuthIdentifier()),
            'one_time_password' => '123456',
        ]);

        // TODO: query parameter ordering for asserting redirect
        //$response->assertRedirect($otpUrl);
        $response->assertSessionHasErrors('one_time_password');
        $this->assertFalse(session()->hasOldInput('one_time_password'));
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_username_that_does_not_exist()
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
            'username' => 'nobody',
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('username');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $this->be(factory(User::class)->create());

        $response = $this->post($this->logoutRoute());

        $response->assertRedirect($this->successfulLogoutRoute());
        $this->assertGuest();
    }

    public function test_user_cannot_logout_when_not_authenticated()
    {
        $response = $this->post($this->logoutRoute());

        $response->assertRedirect($this->successfulLogoutRoute());
        $this->assertGuest();
    }

    public function test_user_cannot_make_more_than_five_attempts_in_one_minute()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        foreach (range(0, 5) as $_) {
            $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                'username' => $user->username,
                'password' => 'invalid-password',
            ]);
        }

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('username');
        $this->assertRegExp(
            $this->getTooManyLoginAttemptsMessage(),
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
    }
}
