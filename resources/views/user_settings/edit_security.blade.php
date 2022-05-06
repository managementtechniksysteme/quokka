@extends('user_settings.edit')

@section('tab')
    <div class="row">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                </svg>
                Passwort ändern
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Nach dem Setzen eines neuen Passwortes werden alle Sitzungen mit Ausnahme der aktuellen (in welcher
                    das Passowrt geändert wurde) abgemeldet.
                </div>
            </div>
        </div>
    </div>

    <form class="needs-validation" action="{{ route('user-settings.update-password') }}" method="post" novalidate>
        @csrf

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="password">Passwort</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" />
                    <div class="invalid-feedback">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Passwort bestätigen</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" />
                    <div class="invalid-feedback">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Passwort speichern
                </button>
            </div>
        </div>
    </form>

    <div class="row mt-4">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#shield"></use>
                </svg>
                Zwei-Faktor-Authentisierung
            </p>
        </div>
    </div>

    @if(Auth::user()->otp_secret)

        <div class="row">
            <div class="col">
                <p>
                    Zwei-Faktor-Authentisierung ist aktiviert. Sie kann durch Klick auf den Button deaktiveirt werden.
                </p>
            </div>
        </div>

        <form class="needs-validation" action="{{ route('user-settings.otp-disable') }}" method="post" novalidate>
            @csrf

            <div class="row mt-2">
                <div class="col">
                    <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#shield-off"></use>
                        </svg>
                        Zwei-Faktor-Authentisierung deaktivieren
                    </button>
                </div>
            </div>
        </form>

    @elseif(Session::has('otpSecret'))

        <div class="row">
            <div class="col">
                <div class="alert alert-info mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                        </svg>
                        Scanne den folgenden QR-Code mit einer Zwei-Faktor-Autentisierung Applikation auf deinem
                        Smartphone und gib das aktuelle Einmalpasswort in das unten stehende Feld ein, um die Aktivierung
                        zu bestätigen.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-center">
                    <img
                        src="{!! Session::get('qrCode') !!}"
                        alt="Zwei-Faktor-Authentisierung QR-Code" />

                </div>
            </div>
        </div>

        <form class="needs-validation" action="{{ route('user-settings.otp-confirm') }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="one_time_password">{{ __('One Time Password') }}</label>
                        <input id="one_time_password" type="text" pattern="\d*" maxlength="6" class="form-control @error('one_time_password') is-invalid @enderror" name="one_time_password" required autocomplete="off" autofocus>
                        <div class="invalid-feedback">
                            @error('one_time_password')
                                {{ $message }}
                            @else
                                {{ __('Please enter the six digit one time password from the authenticator app.') }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                        </svg>
                        Einmalpasswort bestätigen
                    </button>
                </div>
            </div>
        </form>

    @else

        <div class="row">
            <div class="col">
                <p>
                    Zwei-Faktor-Authentisierung ist deaktiviert. Sie kann durch Klick auf den Button aktiviert werden.
                </p>
            </div>
        </div>

        <form class="needs-validation" action="{{ route('user-settings.otp-enable') }}" method="post" novalidate>
            @csrf

            <div class="row mt-2">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#shield"></use>
                        </svg>
                        Zwei-Faktor-Authentisierung aktivieren
                    </button>
                </div>
            </div>
        </form>

    @endif

@endsection
