@extends('user_settings.edit')

@section('tab')
    <form class="q-form needs-validation" action="{{ route('user-settings.update-password') }}" method="post" novalidate>
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Passwort ändern
                <div class="q-form-section__desc">
                    Nach dem Setzen eines neuen Passwortes werden alle Sitzungen mit Ausnahme der aktuellen abgemeldet.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label for="password">Passwort</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" />
                    <div class="invalid-feedback">
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>

                <div>
                    <label for="password_confirmation">Passwort bestätigen</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" />
                    <div class="invalid-feedback">
                        @error('password_confirmation') {{ $message }} @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                        Passwort speichern
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if(Auth::user()->otp_secret)
        <div class="q-form-section mt-4">
            <div class="q-form-section__head">
                Zwei-Faktor-Authentisierung
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <p class="mb-0">Zwei-Faktor-Authentisierung ist aktiviert. Sie kann durch Klick auf den Button deaktiviert werden.</p>
                <div>
                    <form action="{{ route('user-settings.otp-disable') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#shield-x"></use></svg>
                            Zwei-Faktor-Authentisierung deaktivieren
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @elseif(Session::has('otpSecret'))
        <div class="q-form-section mt-4">
            <div class="q-form-section__head">
                Zwei-Faktor-Authentisierung einrichten
                <div class="q-form-section__desc">
                    Scanne den folgenden QR-Code mit einer Zwei-Faktor-Authentisierung-App auf deinem Smartphone und gib das aktuelle Einmalpasswort ein, um die Aktivierung zu bestätigen.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div class="d-flex justify-content-center">
                    <img src="{!! Session::get('qrCode') !!}" alt="Zwei-Faktor-Authentisierung QR-Code" />
                </div>

                <form class="q-form needs-validation" action="{{ route('user-settings.otp-confirm') }}" method="post" novalidate>
                    @csrf
                    <div>
                        <label for="one_time_password">{{ __('One Time Password') }}</label>
                        <input id="one_time_password" type="text" pattern="\d*" maxlength="6"
                               class="form-control @error('one_time_password') is-invalid @enderror"
                               name="one_time_password" required autocomplete="off" autofocus>
                        <div class="invalid-feedback">
                            @error('one_time_password')
                                {{ $message }}
                            @else
                                {{ __('Please enter the six digit one time password from the authenticator app.') }}
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                            Einmalpasswort bestätigen
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else
        <div class="q-form-section mt-4">
            <div class="q-form-section__head">
                Zwei-Faktor-Authentisierung
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <p class="mb-0">Zwei-Faktor-Authentisierung ist deaktiviert. Sie kann durch Klick auf den Button aktiviert werden.</p>
                <div>
                    <form action="{{ route('user-settings.otp-enable') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#shield"></use></svg>
                            Zwei-Faktor-Authentisierung aktivieren
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
