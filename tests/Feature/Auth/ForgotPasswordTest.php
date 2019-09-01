<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function passwordRequestRoute()
    {
        return route('password.request');
    }

    protected function passwordEmailGetRoute()
    {
        return route('password.email');
    }

    protected function passwordEmailPostRoute()
    {
        return route('password.email');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    public function test_user_can_viewAn_email_password_form()
    {
        $response = $this->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    public function test_user_cannot_view_an_email_password_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->passwordRequestRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_receives_an_email_withA_password_reset_link()
    {
        Notification::fake();
        $user = factory(User::class)->create([
            'username' => 'johndoe',
        ]);

        $response = $this->post($this->passwordEmailPostRoute(), [
            'username' => 'johndoe',
        ]);

        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    public function test_user_does_not_receive_email_when_not_registered()
    {
        Notification::fake();

        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'username' => 'nobody',
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('username');
        Notification::assertNotSentTo(factory(User::class)->make(['username' => 'nobody']), ResetPassword::class);
    }

    public function test_username_is_required()
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), []);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('username');
    }
}
