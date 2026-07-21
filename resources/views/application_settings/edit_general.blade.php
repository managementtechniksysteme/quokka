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
    <form class="q-form needs-validation" action="{{ route('application-settings.update-general') }}" method="post" novalidate>
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Eigene Firma
                <div class="q-form-section__desc">
                    Diese Einstellung ist erforderlich, um Mitarbeiter und andere Objekte direkt der eigenen Firma zuweisen zu können.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label for="company_id">Firma</label>
                    <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}" v-cloak></company-dropdown>
                    <div class="invalid-feedback @error('company_id') d-block @enderror">
                        @error('company_id') {{ $message }} @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Leistungen und Abrechnung
                <div class="q-form-section__desc">
                    Einstellungen zur Validierung sowie automatischen Berechnung von Stunden und Kosten bei der Eingabe von Abrechnungen und der Erstellung von Berichten.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="currency_unit">Währungseinheit</label>
                        <input type="text" class="form-control @error('currency_unit') is-invalid @enderror" id="currency_unit" name="currency_unit" placeholder="€" value="{{ old('currency_unit', $applicationSettings->currency_unit) }}" required />
                        <div class="invalid-feedback @error('currency_unit') d-block @enderror">
                            @error('currency_unit') {{ $message }}
                            @else Gib bitte die Währungseinheit ein.
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="services_hour_unit">Stundenbasierte Einheitsbezeichnung</label>
                        <service-unit-dropdown :inputname="'services_hour_unit'" :units="{{ $wageServiceUnits }}" current_unit="{{ $currentServicesHourUnit ?? null }}" :taggable="false" v-cloak></service-unit-dropdown>
                        <div class="invalid-feedback @error('services_hour_unit') d-block @enderror">
                            @error('services_hour_unit') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="accounting_min_amount">Minimale Menge bei Lohnleistungen</label>
                        <input type="number" min="0" step=".01" class="form-control @error('accounting_min_amount') is-invalid @enderror" id="accounting_min_amount" name="accounting_min_amount" placeholder="0.5" value="{{ old('accounting_min_amount', $applicationSettings->accounting_min_amount) }}" required />
                        <div class="invalid-feedback @error('accounting_min_amount') d-block @enderror">
                            @error('accounting_min_amount') {{ $message }}
                            @else Gib bitte die minimale Menge ein.
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="kilometre_costs">Fahrtkosten pro Kilometer</label>
                        <div class="input-group has-validation">
                            <input type="number" min="0" step=".01" class="form-control @error('kilometre_costs') is-invalid @enderror" id="kilometre_costs" name="kilometre_costs" placeholder="1.5" value="{{ old('kilometre_costs', $applicationSettings->kilometre_costs) }}" required />
                            <span class="input-group-text">€</span>
                            <div class="invalid-feedback @error('kilometre_costs') d-block @enderror">
                                @error('kilometre_costs') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="allowances_service_id">Diäten Leistung</label>
                    <service-dropdown inputname="allowances_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentAllowancesService ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('allowances_service_id') d-block @enderror">
                        @error('allowances_service_id') {{ $message }} @enderror
                    </div>
                </div>

                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="overtime_50_service_id">Überstunden 50% Leistung</label>
                        <service-dropdown inputname="overtime_50_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentOvertime50Service ?? 'null' }}" v-cloak></service-dropdown>
                        <div class="invalid-feedback @error('overtime_50_service_id') d-block @enderror">
                            @error('overtime_50_service_id') {{ $message }} @enderror
                        </div>
                    </div>

                    <div>
                        <label for="overtime_100_service_id">Überstunden 100% Leistung</label>
                        <service-dropdown inputname="overtime_100_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentOvertime100Service ?? 'null' }}" v-cloak></service-dropdown>
                        <div class="invalid-feedback @error('overtime_100_service_id') d-block @enderror">
                            @error('overtime_100_service_id') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="time_balance_service_id">Zeitausgleich Leistung</label>
                    <service-dropdown inputname="time_balance_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentTimeBalanceService ?? 'null' }}" v-cloak></service-dropdown>
                    <div class="invalid-feedback @error('time_balance_service_id') d-block @enderror">
                        @error('time_balance_service_id') {{ $message }} @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Urlaub
                <div class="q-form-section__desc">
                    Einstellungen zur automatischen Anpassung sowie Gutschreibung von Urlaub basierend auf Abrechnungen.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="holiday_service_id">Urlaubsleistung</label>
                        <service-dropdown inputname="holiday_service_id" :services="{{ $wageServices }}" :current_service="{{ $currentHolidayService ?? 'null' }}" v-cloak></service-dropdown>
                        <div class="invalid-feedback @error('holiday_service_id') d-block @enderror">
                            @error('holiday_service_id') {{ $message }} @enderror
                        </div>
                    </div>

                    <div>
                        <label for="holiday_yearly_allowance">Jährlich gutzuschreibende Urlaubsmenge</label>
                        <input type="number" min="1" class="form-control @error('holiday_yearly_allowance') is-invalid @enderror" id="holiday_yearly_allowance" name="holiday_yearly_allowance" placeholder="25" value="{{ old('holiday_yearly_allowance', $applicationSettings->holiday_yearly_allowance) }}" />
                        <div class="invalid-feedback @error('holiday_yearly_allowance') d-block @enderror">
                            @error('holiday_yearly_allowance') {{ $message }}
                            @else Jährlich gutzuschreibende Urlaubsmenge muss mindestens 1 sein.
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Kostenschätzungen
                <div class="q-form-section__desc">
                    Ab wie vielen Prozent der geschätzten Kosten eine Warnung beim entsprechenden Projekt angezeigt werden soll.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="project_overall_costs_warning_percentage">Warnschwelle Gesamtkosten</label>
                        <div class="input-group has-validation">
                            <input type="number" min="1" step="1" max="99" class="form-control @error('project_overall_costs_warning_percentage') is-invalid @enderror" id="project_overall_costs_warning_percentage" name="project_overall_costs_warning_percentage" placeholder="25" value="{{ old('project_overall_costs_warning_percentage', $applicationSettings->project_wage_costs_warning_percentage) }}" />
                            <span class="input-group-text">%</span>
                            <div class="invalid-feedback @error('project_overall_costs_warning_percentage') d-block @enderror">
                                @error('project_overall_costs_warning_percentage') {{ $message }}
                                @else Warnschwelle muss zwischen 1 und 99 liegen.
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="project_billed_costs_warning_percentage">Warnschwelle verrechnete Kosten</label>
                        <div class="input-group has-validation">
                            <input type="number" min="1" step="1" max="99" class="form-control @error('project_billed_costs_warning_percentage') is-invalid @enderror" id="project_billed_costs_warning_percentage" name="project_billed_costs_warning_percentage" placeholder="25" value="{{ old('project_billed_costs_warning_percentage', $applicationSettings->project_billed_costs_warning_percentage) }}" />
                            <span class="input-group-text">%</span>
                            <div class="invalid-feedback @error('project_billed_costs_warning_percentage') d-block @enderror">
                                @error('project_billed_costs_warning_percentage') {{ $message }}
                                @else Warnschwelle muss zwischen 1 und 99 liegen.
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="q-form__row q-form__row--2">
                    <div>
                        <label for="project_wage_costs_warning_percentage">Warnschwelle Lohnkosten</label>
                        <div class="input-group has-validation">
                            <input type="number" min="1" step="1" max="99" class="form-control @error('project_wage_costs_warning_percentage') is-invalid @enderror" id="project_wage_costs_warning_percentage" name="project_wage_costs_warning_percentage" placeholder="80" value="{{ old('project_wage_costs_warning_percentage', $applicationSettings->project_wage_costs_warning_percentage) }}" />
                            <span class="input-group-text">%</span>
                            <div class="invalid-feedback @error('project_wage_costs_warning_percentage') d-block @enderror">
                                @error('project_wage_costs_warning_percentage') {{ $message }}
                                @else Warnschwelle muss zwischen 1 und 99 liegen.
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="project_material_costs_warning_percentage">Warnschwelle Materialkosten</label>
                        <div class="input-group has-validation">
                            <input type="number" min="1" step="1" max="99" class="form-control @error('project_material_costs_warning_percentage') is-invalid @enderror" id="project_material_costs_warning_percentage" name="project_material_costs_warning_percentage" placeholder="80" value="{{ old('project_material_costs_warning_percentage', $applicationSettings->project_material_costs_warning_percentage) }}" />
                            <span class="input-group-text">%</span>
                            <div class="invalid-feedback @error('project_material_costs_warning_percentage') d-block @enderror">
                                @error('project_material_costs_warning_percentage') {{ $message }}
                                @else Warnschwelle muss zwischen 1 und 99 liegen.
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Berichte
                <div class="q-form-section__desc">
                    Benutzer, der zusätzlich zum zuständigen Techniker benachrichtigt wird, wenn ein Bericht von einem Kunden unterschrieben wurde.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label for="signature_notify_user_id">Zu benachrichtigender Benutzer</label>
                    <person-dropdown inputname="signature_notify_user_id" :people="{{ $userPeople }}" :current_person="{{ $currentSignatureNotifyPerson ?? 'null' }}" v-cloak></person-dropdown>
                    <div class="invalid-feedback @error('signature_notify_user_id') d-block @enderror">
                        @error('signature_notify_user_id') {{ $message }} @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Aufgaben
                <div class="q-form-section__desc">
                    Wie viele Tage vor Fälligkeit eine Aufgabe als „bald fällig" markiert wird.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label for="task_due_soon_days">Tage bis zur Fälligkeit</label>
                    <input type="number" min="1" class="form-control @error('task_due_soon_days') is-invalid @enderror" id="task_due_soon_days" name="task_due_soon_days" placeholder="7" value="{{ old('task_due_soon_days', $applicationSettings->task_due_soon_days) }}" required />
                    <div class="invalid-feedback @error('task_due_soon_days') d-block @enderror">
                        @error('task_due_soon_days') {{ $message }}
                        @else Gib bitte die Anzahl der Tage (mindestens 1) ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-section">
            <div class="q-form-section__head">
                Datenverwaltung
                <div class="q-form-section__desc">
                    Automatisches Entfernen alter Einträge, die älter als einen Monat sind.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label>Gelesene Benachrichtigungen entfernen?</label>
                    <div class="btn-group @error('prune_read_notifications') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="prune_read_notifications" id="prune_read_notifications-1" value="1" autocomplete="off" @if(old('prune_read_notifications', $applicationSettings->prune_read_notifications) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="prune_read_notifications-1">entfernen</label>
                        <input type="radio" class="btn-check" name="prune_read_notifications" id="prune_read_notifications-0" value="0" autocomplete="off" @if(old('prune_read_notifications', $applicationSettings->prune_read_notifications) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="prune_read_notifications-0">behalten</label>
                    </div>
                    <div class="invalid-feedback @error('prune_read_notifications') d-block @enderror">
                        @error('prune_read_notifications') {{ $message }} @enderror
                    </div>
                </div>

                <div>
                    <label>Gesendete Emails entfernen?</label>
                    <div class="btn-group @error('prune_sent_emails') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="prune_sent_emails" id="prune_sent_emails-1" value="1" autocomplete="off" @if(old('prune_sent_emails', $applicationSettings->prune_sent_emails) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="prune_sent_emails-1">entfernen</label>
                        <input type="radio" class="btn-check" name="prune_sent_emails" id="prune_sent_emails-0" value="0" autocomplete="off" @if(old('prune_sent_emails', $applicationSettings->prune_sent_emails) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="prune_sent_emails-0">behalten</label>
                    </div>
                    <div class="invalid-feedback @error('prune_sent_emails') d-block @enderror">
                        @error('prune_sent_emails') {{ $message }} @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-actions">
            <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                <span class="d-none d-md-inline">Einstellungen speichern</span>
                <span class="d-inline d-md-none">Speichern</span>
            </button>
        </div>
    </form>
@endsection
