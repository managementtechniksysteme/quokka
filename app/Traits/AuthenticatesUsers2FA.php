<?php

namespace App\Traits;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

trait AuthenticatesUsers2FA
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $this->checkLoginAttempts($request);

        // Try to login the user once to get user details for second factor checks
        if (auth()->once($this->credentials($request))) {
            $user = auth()->user();

            // Check if the user has second factor authentication enabled and if so, continue auth flow.
            if ($user->{config('auth2fa.otp_secret_column')} !== null) {
                return redirect(
                    URL::temporarySignedRoute(
                        config('auth2fa.otp_route'),
                        now()->addMinutes(config('auth2fa.otp_link_valid_minutes')),
                        ['user' => encrypt($user->getAuthIdentifier())]
                    )
                );
            }
        }

        // Normal login with username and password only.
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function loginSecondFactorOneTimePassword(Request $request)
    {
        $validatedData = $request->validate([
            config('auth2fa.otp_input') => 'required|digits:6',
        ]);

        // Get user and put the identifying username field into the session for login throtteling to work properly.
        $user = User::find(decrypt($request->user));

        $request->merge([$this->username() => $user[$this->username()]]);

        $this->checkLoginAttempts($request);

        // Verify the security code and log user in if the code is correct.
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey(
            decrypt($user->{config('auth2fa.otp_secret_column')}),
            $validatedData[config('auth2fa.otp_input')],
            config('auth2fa.window')
        )) {
            auth()->login($user);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return back()->withErrors([config('auth2fa.otp_input') => Lang::get('auth2fa.otp_failed')]);
    }

    private function checkLoginAttempts(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
    }

    private function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS)->redirectTo(route('login'));
    }
}
