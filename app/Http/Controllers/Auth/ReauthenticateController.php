<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

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
        if (! Session::has('requested_url')) {
            return redirect('home');
        }

        Session::reflash();

        return view('auth.reauthenticate');
    }

    public function reauthenticate(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            config('auth2fa.otp_input') => 'sometimes|required|digits:6',
        ]);

        $user = Auth::user();

        if (! Hash::check($validatedData['password'], $user->password)) {
            Session::reflash();

            return back()->withErrors(['password' => Lang::get('auth.password_failed')]);
        }

        if ($user[config('auth2fa.otp_secret_column')]) {
            $google2fa = new Google2FA();

            if (! $google2fa->verifyKey(
                decrypt($user[config('auth2fa.otp_secret_column')]),
                $validatedData[config('auth2fa.otp_input')],
                config('auth2fa.window')
            )) {
                Session::reflash();

                return back()->withErrors([config('auth2fa.otp_input') => Lang::get('auth2fa.otp_failed')]);
            }
        }

        Session::put('reauthenticated', true);

        return redirect(Session::get('requested_url'));
    }
}
