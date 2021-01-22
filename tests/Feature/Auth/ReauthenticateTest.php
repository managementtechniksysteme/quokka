<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class ReauthenticateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_test/reauthenticated', function () {
            return 'OK';
        })->middleware(['auth', 'reauth']);
    }

    protected function loginGetRoute()
    {
        return route('login');
    }

    protected function homeGetRoute()
    {
        return route('home');
    }

    protected function reauthenticateGetRoute()
    {
        return route('reauthenticate');
    }

    protected function reauthenticatePostRoute()
    {
        return route('reauthenticate');
    }

    protected function reauthenticateRequiredRoute()
    {
        return '/_test/reauthenticated';
    }

    // TODO: localisation
    protected function passwordLabel()
    {
        return 'Passwort';
    }

    // TODO: localisation
    protected function otpLabel()
    {
        return 'Einmalpasswort';
    }

    public function test_user_cannot_view_a_reauthenticate_form()
    {
        $response = $this->get($this->reauthenticateGetRoute());

        $response->assertRedirect($this->loginGetRoute());
    }

    public function test_user_is_redirected_to_the_reauthenticate_page_when_required_and_authenticated()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->get($this->reauthenticateRequiredRoute());

        $response->assertRedirect($this->reauthenticateGetRoute());
    }

    public function test_user_is_redirected_to_home_when_logged_in_and_trying_to_access_reauthentication_form()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->get($this->reauthenticateGetRoute());

        $response->assertRedirect($this->homeGetRoute());
        //$response->assertViewIs('home');
    }

    public function test_user_sees_password_field_in_reauthenticate_form()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.reauthenticate');
        $response->assertSee($this->passwordLabel());
    }

    public function test_user_sees_otp_field_in_reauthenticate_form_when_otp_is_enabled()
    {
        $user = User::factory()->create([
            'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
        ]);

        $response = $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.reauthenticate');
        $response->assertSee($this->otpLabel());
    }

    public function test_user_can_reauthenticate_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response = $this->actingAs($user)->post($this->reauthenticatePostRoute(), [
            'password' => $password,
        ]);

        $response->assertRedirect($this->reauthenticateRequiredRoute());
    }

    public function test_user_can_reauthenticate_in_two_steps_with_correct_credentials()
    {
        $google2fa = new Google2FA();

        $otp_secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'otp_secret' => encrypt($otp_secret),
        ]);

        $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response = $this->actingAs($user)->post($this->reauthenticatePostRoute(), [
            'password' => $password,
            'one_time_password' => $google2fa->getCurrentOtp($otp_secret),
        ]);

        $response->assertRedirect($this->reauthenticateRequiredRoute());
    }

    public function test_user_can_not_reauthenticate_with_incorrect_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response = $this->actingAs($user)->post($this->reauthenticatePostRoute(), [
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->reauthenticateGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function test_user_can_not_reauthenticate_in_two_steps_with_incorrect_otp()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'otp_secret' => encrypt('MZUWY3DFMQWW65LU'),
        ]);

        $this->actingAs($user)->followingRedirects()->get($this->reauthenticateRequiredRoute());

        $response = $this->actingAs($user)->post($this->reauthenticatePostRoute(), [
            'password' => $password,
            'one_time_password' => '123456',
        ]);

        $response->assertRedirect($this->reauthenticateGetRoute());
        $response->assertSessionHasErrors('one_time_password');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertFalse(session()->hasOldInput('one_time_password'));
    }
}
