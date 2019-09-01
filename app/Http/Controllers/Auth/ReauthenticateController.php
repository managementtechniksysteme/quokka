<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ReauthenticateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        session()->reflash();

        return view('auth.reauthenticate');
    }

    public function reauthenticate(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            config('auth2fa.otp_input') => 'sometimes|required|digits:6',
        ]);

        $user = auth()->user();

        if (! Hash::check($validatedData['password'], $user->password)) {
            session()->reflash();

            return back()->withErrors(['password' => Lang::get('auth.password_failed')]);
        }

        if ($user->{config('auth2fa.otp_secret_column')}) {
            $google2fa = new Google2FA();

            if (! $google2fa->verifyKey(
                decrypt($user->{config('auth2fa.otp_secret_column')}),
                $validatedData[config('auth2fa.otp_input')],
                config('auth2fa.window')
            )) {
                session()->reflash();

                return back()->withErrors([config('auth2fa.otp_input') => Lang::get('auth2fa.otp_failed')]);
            }
        }

        session()->flash('reauth.reauthenticated', true);

        return redirect(session()->pull('reauth.requested_url'));
    }
}
