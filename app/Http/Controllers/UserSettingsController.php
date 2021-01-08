<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Reauthenticate;
use App\Http\Requests\UserSettingsConfirmOtpRequest;
use App\Http\Requests\UserSettingsUpdateInterfaceRequest;
use App\Http\Requests\UserSettingsUpdatePasswordRequest;
use App\Http\Requests\UserSettingsUpdateSignatureRequest;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PragmaRX\Google2FA\Google2FA;

class UserSettingsController extends Controller
{
    public function __construct(Request $request)
    {
        if ($request->has('tab') && $request->tab === 'security' && URL::previous() !== $request->fullUrl()) {
            $this->middleware(Reauthenticate::class);
        }
    }

    public function edit(Request $request)
    {
        Auth::user()->load('settings');

        switch ($request->tab) {
            case 'general':
                return view('user_settings.edit_general');
            case 'interface':
                return view('user_settings.edit_interface');
            case 'notifications':
                Auth::user()->loadCount('pushSubscriptions');

                return view('user_settings.edit_notifications');
            case 'security':
                if (Session::has('otpSecret')) {
                    Session::reflash();
                }

                return view('user_settings.edit_security');
            default:
                return redirect()->route('user-settings.edit', ['tab' => 'general']);
        }
    }

    public function updateSignature(UserSettingsUpdateSignatureRequest $request)
    {
        $validatedData = $request->validated();

        Auth::user()->addSignature($request->signature);

        return redirect()->route('user-settings.edit', ['tab' => 'general'])->with('success', 'Die Unterschrift wurde erfolgreich gepseichert.');
    }

    public function updateInterface(UserSettingsUpdateInterfaceRequest $request)
    {
        $validatedData = $request->validated();

        Auth::user()->settings->update($validatedData);

        return redirect()->route('user-settings.edit', ['tab' => 'interface'])->with('success', 'Die Einstellungen wurde erfolgreich gepseichert.');
    }

    public function updatePassword(UserSettingsUpdatePasswordRequest $request)
    {
        $validatedData = $request->validated();

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user-settings.edit', ['tab' => 'security'])->with('success', 'Das Passwort wurde erfolgreich gepseichert.');
    }

    public function enableOtp()
    {
        $google2fa = new Google2FA();

        $otpSecret = $google2fa->generateSecretKey();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            Auth::user()->username,
            $otpSecret);

        $qrCode = (new QRCode())->render($qrCodeUrl);

        return redirect()
            ->route('user-settings.edit', ['tab' => 'security'])
            ->with('otpSecret', $otpSecret)
            ->with('qrCode', $qrCode);
    }

    public function confirmOtp(UserSettingsConfirmOtpRequest $request)
    {
        $validatedData = $request->validated();

        $otpSecret = Session::get('otpSecret');

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($otpSecret, $request[config('auth2fa.otp_input')], config('auth2fa.window'))) {
            Auth::user()->update([
                config('auth2fa.otp_secret_column') => encrypt($otpSecret),
            ]);

            Session::remove('otpSecret');

            return redirect()->route('user-settings.edit', ['tab' => 'security'])->with('success', 'Zwei-Faktor-Authentisierung wurde erfolgreich aktiviert.');
        }

        Session::reflash();

        return back()->withErrors([config('auth2fa.otp_input') => Lang::get('auth2fa.otp_failed')]);
    }

    public function disableOtp()
    {
        Auth::user()->update([
            'otp_secret' => null,
        ]);

        return redirect()->route('user-settings.edit', ['tab' => 'security'])->with('success', 'Zwei-Faktor-Authentisierung wurde erfolgreich deaktiviert.');
    }
}
