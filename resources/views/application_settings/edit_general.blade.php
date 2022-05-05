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

@if (old('allowances_service_id'))
    @php $currentAllowancesService = WageService::find(old('allowances_service_id')); @endphp
@endif

@if (old('overtime_50_service_id'))
    @php $currentOvertime50Service = WageService::find(old('overtime_50_service_id')); @endphp
@endif

@if (old('overtime_100_service_id'))
    @php $currentOvertime100Service = WageService::find(old('overtime_100_service_id')); @endphp
@endif

@if (old('time_balance_service_id'))
    @php $currentTimeBalanceService = WageService::find(old('time_balance_service_id')); @endphp
@endif

@if (old('holiday_service_id'))
    @php $currentHolidayService = WageService::find(old('holiday_service_id')); @endphp
@endif

@if (old('accounting_time_mandatory_unit'))
    @php $currentServicesHourUnit = old('services_hour_unit'); @endphp
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
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                    </svg>
                    Einstellungen zu Leistungen und Abrechnungen
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
                        Diese Einstellungen sind für die Validierung sowie automatische Berechnung von Stunden sowie
                        Start oder Ende bei der Eingabe von Abrechnungen und Erstellung von Berichten erforderlich.
                        Weiters können Anzeigeeinstellungen vorgenommen werden.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="currency_unit">Währungseinheit (z.B. für Materialleistungen)</label>
                    <input type="text" class="form-control @error('currency_unit') is-invalid @enderror" id="currency_unit" name="currency_unit" placeholder="€" value="{{ old('currency_unit', $applicationSettings->currency_unit) }}" required />
                    <div class="invalid-feedback @error('currency_unit') d-block @enderror">
                        @error('currency_unit')
                            {{ $message }}
                        @else
                            Gib bitte die Währungseinheit ein.
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="services_hour_unit">Stundenbasierte Einheitsbezeichnung (z.B. für Techniker Stunden)</label>
                    <service-unit-dropdown :inputname="'services_hour_unit'" :units="{{ $wageServiceUnits }}" current_unit="{{ $currentServicesHourUnit ?? null }}" :taggable="false" v-cloak></service-unit-dropdown>
                    <div class="invalid-feedback">
                        @error('services_hour_unit')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="accounting_min_amount">Minimale Menge (und Multiplikator) bei Abrechnungen für Lohndienstleistungen</label>
                    <input type="number" min="0" step=".01" class="form-control @error('accounting_min_amount') is-invalid @enderror" id="accounting_min_amount" name="accounting_min_amount" placeholder="0.5" value="{{ old('accounting_min_amount', $applicationSettings->accounting_min_amount) }}" required />
                    <div class="invalid-feedback @error('accounting_min_amount') d-block @enderror">
                        @error('accounting_min_amount')
                            {{ $message }}
                        @else
                            Gib bitte die minimale Menge ein.
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="allowances_service_id">Diäten Leistung</label>
                    <service-dropdown inputname="allowances_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentAllowancesService ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('allowances_service_id') d-block @enderror">
                        @error('allowances_service_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="overtime_50_service_id">Überstunden 50% Leistung</label>
                    <service-dropdown inputname="overtime_50_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentOvertime50Service ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('overtime_50_service_id') d-block @enderror">
                        @error('overtime_50_service_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="overtime_100_service_id">Überstunden 100% Leistung</label>
                    <service-dropdown inputname="overtime_100_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentOvertime100Service ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('overtime_100_service_id') d-block @enderror">
                        @error('overtime_100_service_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="time_balance_service_id">Zeitausgleich Leistung</label>
                    <service-dropdown inputname="time_balance_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentTimeBalanceService ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('time_balance_service_id') d-block @enderror">
                        @error('time_balance_service_id')
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
                    <input type="number" min="1" class="form-control @error('holiday_yearly_allowance') is-invalid @enderror" id="holiday_yearly_allowance" name="holiday_yearly_allowance" placeholder="25" value="{{ old('holiday_yearly_allowance', $applicationSettings->holiday_yearly_allowance) }}" />
                    <div class="invalid-feedback @error('holiday_yearly_allowance') d-block @enderror">
                        @error('holiday_yearly_allowance')
                            {{ $message }}
                        @else
                            Jährlich gutzuschreibende Urlaubsmenge muss mindestens 1 sein.
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
                    Warnung für Kostenschätzungen
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
                        Hier kann festgelgt werden, ab wie vielen Prozent der geschätzten Lohn- sowie Materialkosten eine
                        Warnung beim entsprechenden Projekt angezeigt werden soll.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="project_overall_costs_warning_percentage">Warnschwelle für die Gesamtkosten</label>
                    <div class="input-group">
                        <input type="number" min="1" step="1" max="99" class="form-control @error('project_overall_costs_warning_percentage') is-invalid @enderror" id="project_overall_costs_warning_percentage" name="project_overall_costs_warning_percentage" placeholder="25" value="{{ old('project_overall_costs_warning_percentage', $applicationSettings->project_wage_costs_warning_percentage) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="invalid-feedback @error('project_wage_costs_warning_percentage') d-block @enderror">
                        @error('project_wage_costs_warning_percentage')
                        {{ $message }}
                        @else
                            Warnschwelle muss zwischen 1 und 99 liegen.
                            @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="project_wage_costs_warning_percentage">Warnschwelle für die Lohnkosten</label>
                    <div class="input-group">
                        <input type="number" min="1" step="1" max="99" class="form-control @error('project_wage_costs_warning_percentage') is-invalid @enderror" id="project_wage_costs_warning_percentage" name="project_wage_costs_warning_percentage" placeholder="80" value="{{ old('project_wage_costs_warning_percentage', $applicationSettings->project_wage_costs_warning_percentage) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="invalid-feedback @error('project_wage_costs_warning_percentage') d-block @enderror">
                        @error('project_wage_costs_warning_percentage')
                        {{ $message }}
                        @else
                            Warnschwelle muss zwischen 1 und 99 liegen.
                            @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="project_material_costs_warning_percentage">Warnschwelle für die Materialkosten</label>
                    <div class="input-group">
                        <input type="number" min="1" step="1" max="99" class="form-control @error('project_material_costs_warning_percentage') is-invalid @enderror" id="project_material_costs_warning_percentage" name="project_material_costs_warning_percentage" placeholder="80" value="{{ old('project_material_costs_warning_percentage', $applicationSettings->project_material_costs_warning_percentage) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="invalid-feedback @error('project_material_costs_warning_percentage') d-block @enderror">
                        @error('project_material_costs_warning_percentage')
                        {{ $message }}
                        @else
                            Warnschwelle muss zwischen 1 und 99 liegen.
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
                    <input type="number" min="1" class="form-control @error('task_due_soon_days') is-invalid @enderror" id="task_due_soon_days" name="task_due_soon_days" placeholder="7" value="{{ old('task_due_soon_days', $applicationSettings->task_due_soon_days) }}" required />
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
