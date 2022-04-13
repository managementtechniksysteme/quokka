@extends('application_settings.edit')

@php
    use App\Models\ApplicationSettings;
    use App\Models\Company;
    use App\Models\Person;
    use App\Models\WageService
@endphp

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif

@if (old('holiday_service_id'))
    @php $currentHolidayService = WageService::find(old('holiday_service_id')); @endphp
@endif

@if (old('signature_notify_user_id'))
    @php $currentSignatureNotifyPerson = Person::find(old('signature_notify_user_id')); @endphp
@endif

@section('tab')
    <form class="needs-validation" action="{{ route('application-settings.update-general') }}" method="post" novalidate>
        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    Eigene Firma
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="alert alert-info mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="feather feather-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                        </svg>
                        Diese Einstellung ist erforderlich, um Mitarbeiter und andere Objekte direkt der eigenen Firma
                        zuweisen zu können.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="company_id">Firma</label>
                    <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}" v-cloak></company-dropdown>
                    <div class="invalid-feedback @error('company_id') d-block @enderror">
                        @error('company_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                    </svg>
                    Automatische Anpassung sowie Gutschreibung von Urlaub
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="alert alert-info mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="feather feather-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                        </svg>
                        Diese Einstellungen sind erforderlich, um verfügbaren Urlaub von Mitarbeitern automatisch
                        basierend auf Abrechnungen anzupassen sowie das jährliche Urlaubspensum am Eintrittstag
                        gutzuschreiben.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="holiday_service_id">Urlaubsleistung</label>
                    <service-dropdown inputname="holiday_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentHolidayService ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('holiday_service_id') d-block @enderror">
                        @error('holiday_service_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="holiday_yearly_allowance">Jährlich gutzuschreibende Urlaubsmenge</label>
                    <input type="number" min="1" class="form-control @error('holiday_yearly_allowance') is-invalid @enderror" id="holiday_yearly_allowance" name="holiday_yearly_allowance" placeholder="25" value="{{ old('holiday_yearly_allowance', ApplicationSettings::get()->holiday_yearly_allowance) }}" />
                    <div class="invalid-feedback @error('holiday_yearly_allowance') d-block @enderror">
                        @error('holiday_yearly_allowance')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                    </svg>
                    Benachrichtigung bei unterschriebenen Berichten
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="alert alert-info mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="feather feather-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                        </svg>
                        Hier kann ein Quokka Benutzer festgelegt werden, der immer zusätzlich zum zuständigen Techniker
                        informiert wird, sobald ein Bericht von einem Kunden unterschrieben wurde.
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="signature_notify_user_id">Zu benachrichtigender Benutzer</label>
                    <person-dropdown inputname="signature_notify_user_id" :people="{{ $userPeople }}" :current_person="{{ $currentSignatureNotifyPerson ?? 'null' }}" v-cloak></person-dropdown>
                    <div class="invalid-feedback @error('signature_notify_user_id') d-block @enderror">
                        @error('signature_notify_user_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    Bald fällige Aufgaben
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="task_due_soon_days">Anzahl der Tage, die eine Aufgabe als bald fällig markiert wird</label>
                    <input type="number" min="1" class="form-control @error('task_due_soon_days') is-invalid @enderror" id="task_due_soon_days" name="task_due_soon_days" placeholder="7" value="{{ old('task_due_soon_days', ApplicationSettings::get()->task_due_soon_days) }}" required />
                    <div class="invalid-feedback @error('task_due_soon_days') d-block @enderror">
                        @error('task_due_soon_days')
                            {{ $message }}
                        @else
                            Gib bitte die Anzahl der Tage (mindestens 1) ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
            </svg>
            Einstellungen speichern
        </button>
    </form>
@endsection
