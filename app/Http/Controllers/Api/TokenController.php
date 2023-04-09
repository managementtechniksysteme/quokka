<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use PragmaRX\Google2FA\Google2FA;

class TokenController extends Controller
{
    use ThrottlesLogins;

    public function token(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            $this->username() => 'required',
            'password' => 'required',
            config('authapi.token_name_input') => 'required',
            'remember_me' => 'boolean',
        ]);

        $this->checkLoginAttempts($request);

        $user = User::where('username', $validatedData['username'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                $this->username() => ['The provided credentials are incorrect.'],
            ]);
        }

        $now = Carbon::now();

        if ($user->{config('auth2fa.otp_secret_column')} !== null) {
            $signedUrl = URL::temporarySignedRoute(
                'token.otp',
                $now->addMinutes(config('auth2fa.otp_link_valid_minutes')));

            return new JsonResponse([
                config('auth2fa.otp_input') => 'required',
                'otp-url' => $signedUrl,
            ]);
        }

        // Delete old valid tokens if there are any
        $this->deleteValidTokens($user, $validatedData[config('authapi.token_name_input')]);

        // Create new tokens
        $tokens = $this->createTokens(
            $user,
            $validatedData[config('authapi.token_name_input')],
            $validatedData['remember_me']
        );

        // Return response with tokens
        return new JsonResponse($tokens);
    }

    public function tokenSecondFactorOneTimePassword(Request $request) : JsonResponse
    {
        $this->middleware('signed');

        $validatedData = $request->validate([
            $this->username() => 'required',
            'password' => 'required',
            config('auth2fa.otp_input') => 'required|digits:6',
            config('authapi.token_name_input') => 'required',
            'remember_me' => 'boolean',
        ]);

        $this->checkLoginAttempts($request);

        $user = User::where('username', $validatedData['username'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $now = Carbon::now();

        // Verify the security code and log user in if the code is correct.
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey(
            decrypt($user[config('auth2fa.otp_secret_column')]),
            $validatedData[config('auth2fa.otp_input')],
            config('auth2fa.window')
        )) {
            // Delete old valid tokens if there are any
            $this->deleteValidTokens($user, $validatedData[config('authapi.token_name_input')]);

            // Create new tokens
            $tokens = $this->createTokens(
                $user,
                $validatedData[config('authapi.token_name_input')],
                $validatedData['remember_me']
            );

            // Return response with tokens
            return new JsonResponse($tokens);
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            config('auth2fa.otp_input') => [Lang::get('auth2fa.otp_failed')],
        ]);
    }

    public function refreshToken(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            config('authapi.token_name_input') => 'required',
        ]);

        $user = Auth::user();

        $token = PersonalAccessToken::findToken($request->bearerToken());

        if($token->name !== $validatedData[config('authapi.token_name_input')]) {
            throw ValidationException::withMessages([
                config('authapi.token_name_input') => [Lang::get('authapi.refresh_device_mismatch')],
            ]);
        }

        // Delete old valid tokens if there are any
        $this->deleteValidTokens($user, $validatedData[config('authapi.token_name_input')]);

        // Create new tokens
        $tokens = $this->createTokens($user, $validatedData[config('authapi.token_name_input')]);

        // Return response with tokens
        return new JsonResponse($tokens);
    }

    public function username()
    {
        return 'username';
    }

    private function createTokens(User $user, string $tokenName, bool $rememberMe) : array
    {
        $now = Carbon::now();

        $authenticateToken = $user
            ->createToken(
                $tokenName,
                [config('authapi.auth_token_ability')],
                $now->addMinutes(config('authapi.auth_token_lifetime'))
            )
            ->plainTextToken;

        $refreshToken = $user
            ->createToken(
                $tokenName,
                [config('authapi.refresh_token_ability')],
                $now->addMinutes(
                    $rememberMe ?
                        config('authapi.refresh_token_lifetime') :
                        config('authapi.auth_token_lifetime')
                )
            )
            ->plainTextToken;

        return [
            'token' => $authenticateToken,
            'refresh-token' => $refreshToken,
        ];
    }

    private function deleteValidTokens(User $user, string $tokenName) : void
    {
        $now = Carbon::now();

        $user->tokens()
            ->where('name', $tokenName)
            ->whereJsonContains('abilities', config('authapi.auth_token_ability'))
            ->where('expires_at', '>', $now)
            ->delete();

        $user->tokens()
            ->where('name', $tokenName)
            ->whereJsonContains('abilities', config('authapi.refresh_token_ability'))
            ->where('expires_at', '>', $now)
            ->delete();
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
            'username' => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
