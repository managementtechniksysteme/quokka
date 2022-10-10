@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

@unless(Auth::user()->signature())
    <div class="alert alert-warning mt-1" role="alert">
        <div class="d-inline-flex align-items-center">
            <svg class="icon icon-24 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
            </svg>
            <p class="m-0">
                Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch
                eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den
                <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a>
                hinzu.
            </p>
        </div>
    </div>
@endunless

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Prüfberichtes.
        </p>
        <p class="text-muted">
            Bei der Bearbeitung eines bereits unterschriebenen Prüfberichtes wird die vorhandene Unterschrift entferent.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee"
                   placeholder="{{ optional($flowMeterInspectionReport)->employee->person->name ?? Auth::user()->person->name }}"
                   disabled/>
        </div>

        <div class="form-group">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($flowMeterInspectionReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift
                        entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-secondary @if(optional($flowMeterInspectionReport)->status == 'new' || !$flowMeterInspectionReport) active @else disabled @endif">
                    <input type="radio" name="status" id="new"
                           @if(optional($flowMeterInspectionReport)->status == 'new' || !$flowMeterInspectionReport) checked
                           @endif disabled> neu
                </label>
                <label class="btn btn-outline-secondary @if(optional($flowMeterInspectionReport)->status == 'signed') active @else disabled  @endif">
                    <input type="radio" name="status" id="signed"
                           @if(optional($flowMeterInspectionReport)->status == 'signed') checked @endif disabled>
                    unterschrieben
                </label>
                <label class="btn btn-outline-secondary @if(optional($flowMeterInspectionReport)->status == 'finished') active @else disabled  @endif">
                    <input type="radio" name="status" id="finished"
                           @if(optional($flowMeterInspectionReport)->status == 'finished') checked @endif disabled>
                    erledigt
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="inspected_on">Datum</label>
            <input type="date" class="form-control @error('inspected_on') is-invalid @enderror" id="inspected_on"
                   name="inspected_on" placeholder=""
                   value="{{ old('inspected_on', optional(optional($flowMeterInspectionReport)->inspected_on)->format('Y-m-d')) }}"
                   required/>
            <div class="invalid-feedback">
                @error('inspected_on')
                {{ $message }}
                @else
                    Gib bitte das Datum der Überprüfung ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="equipment_identifier">Zu überprüfende Anlage</label>
            <input type="text" class="form-control @error('equipment_identifier') is-invalid @enderror"
                   id="equipment_identifier" name="equipment_identifier" placeholder="Kläranlage Musterort"
                   value="{{ old('equipment_identifier', optional($flowMeterInspectionReport)->equipment_identifier) }}"
                   required/>
            <div class="invalid-feedback">
                @error('equipment_itentifier')
                {{ $message }}
                @else
                    Gib bitte die Anlage ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="treatment_plant">Kläranlage</label>
            <input type="text" class="form-control @error('treatment_plant') is-invalid @enderror" id="treatment_plant"
                   name="treatment_plant" placeholder="Kläranlage Musterort"
                   value="{{ old('treatment_plant', optional($flowMeterInspectionReport)->treatment_plant) }}"/>
            <div class="invalid-feedback">
                @error('treatment_plant')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="sewage_plant">Kanalanlage</label>
            <input type="text" class="form-control @error('sewage_plant') is-invalid @enderror" id="sewage_plant"
                   name="sewage_plant" placeholder="Kanalanlageee Musterort"
                   value="{{ old('sewage_plant', optional($flowMeterInspectionReport)->sewage_plant) }}"/>
            <div class="invalid-feedback">
                @error('sewage_plant')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="indirect_induction">Indirekteinleiter</label>
            <input type="text" class="form-control @error('indirect_induction') is-invalid @enderror"
                   id="indirect_induction" name="indirect_induction" placeholder="Indirekteinleiter"
                   value="{{ old('indirect_induction', optional($flowMeterInspectionReport)->indirect_induction) }}"/>
            <div class="invalid-feedback">
                @error('indirect_induction')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="treatment_plant_size">Ausbaugröße (Bemessungswert) der Kläranlage</label>
            <input type="number" min="0" class="form-control @error('treatment_plant_size') is-invalid @enderror"
                   id="treatment_plant_size" name="treatment_plant_size" placeholder="100000"
                   value="{{ old('treatment_plant_size', optional($flowMeterInspectionReport)->treatment_plant_size) }}"/>
            <div class="invalid-feedback">
                @error('treatment_plant_size')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="weather">Wetter</label>
            </div>
            <div class="btn-group btn-group-toggle @error('weather') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'sunny') active @endif">
                    <input type="radio" name="weather" id="sunny" value="sunny" autocomplete="off"
                           @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'sunny') checked @endif>
                    sonnig
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'cloudy') active @endif">
                    <input type="radio" name="weather" id="cloudy" value="cloudy" autocomplete="off"
                           @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'cloudy') checked @endif>
                    bewölkt
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'rainy') active @endif">
                    <input type="radio" name="weather" id="rainy" value="rainy" autocomplete="off"
                           @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'rainy') checked @endif>
                    regnerisch
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'snowy') active @endif">
                    <input type="radio" name="weather" id="snowy" value="snowy" autocomplete="off"
                           @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'snowy') checked @endif>
                    Schnee
                </label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="temperature">Temperatur</label>
            <div class="input-group">
                <input type="number" step="1" class="form-control @error('temperature') is-invalid @enderror" id="temperature" name="temperature" placeholder="18" value="{{ old('temperature', optional($flowMeterInspectionReport)->temperature) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">°C</span>
                </div>
                <div class="invalid-feedback">
                    @error('temperature')
                    {{ $message }}
                    @else
                        Gib bitte die Temperatur an.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}"
                              :current_project="{{ $currentProject ?? 'null' }}"></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use>
            </svg>
            Messstelle
        </p>
        <p class="text-muted">
            Die Details zur Messstelle sowie aktuell vorherrschenden Gegebenheiten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="measuring_point">Bezeichnung der Messstelle</label>
            <input type="text" class="form-control @error('measuring_point') is-invalid @enderror" id="measuring_point"
                   name="measuring_point" placeholder="Ablaufmessung"
                   value="{{ old('measuring_point', optional($flowMeterInspectionReport)->measuring_point) }}"
                   required/>
            <div class="invalid-feedback">
                @error('measuring_point')
                {{ $message }}
                @else
                    Gib bitte die Bezeichung ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="installation_point">Einbauort</label>
            <input type="text" class="form-control @error('installation_point') is-invalid @enderror"
                   id="installation_point" name="installation_point" placeholder="Kläranlage"
                   value="{{ old('installation_point', optional($flowMeterInspectionReport)->installation_point) }}"
                   required/>
            <div class="invalid-feedback">
                @error('installation_point')
                {{ $message }}
                @else
                    Gib bitte den Einbauort ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="medium">Medium</label>
            <input type="text" class="form-control @error('medium') is-invalid @enderror" id="medium" name="medium"
                   placeholder="Abwasser" value="{{ old('medium', optional($flowMeterInspectionReport)->medium) }}"
                   required/>
            <div class="invalid-feedback">
                @error('medium')
                {{ $message }}
                @else
                    Gib bitte das Medium ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="commissioning_year">Jahr der Inbetriebnahme</label>
            <input type="number" min="0" class="form-control @error('commissioning_year') is-invalid @enderror"
                   id="commissioning_year" name="commissioning_year" placeholder="1991"
                   value="{{ old('commissioning_year', optional($flowMeterInspectionReport)->commissioning_year) }}"/>
            <div class="invalid-feedback">
                @error('commissioning_year')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="responsible_person">Zuständiger Mitarbeiter für die Messeinrichtung</label>
            <input type="text" class="form-control @error('responsible_person') is-invalid @enderror"
                   id="responsible_person" name="responsible_person" placeholder="Max Mustermann"
                   value="{{ old('responsible_person', optional($flowMeterInspectionReport)->responsible_person) }}"
                   required/>
            <div class="invalid-feedback">
                @error('responsible_person')
                {{ $message }}
                @else
                    Gib bitte den Namen ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="responsible_person_instructed_on">Zuständiger Mitarbeiter eingeschult am</label>
            <input type="date" class="form-control @error('responsible_person_instructed_on') is-invalid @enderror"
                   id="responsible_person_instructed_on" name="responsible_person_instructed_on"
                   placeholder="1991-01-01"
                   value="{{ old('responsible_person_instructed_on', optional(optional($flowMeterInspectionReport)->responsible_person_instructed_on)->format('Y-m-d')) }}"
                   required/>
            <div class="invalid-feedback">
                @error('responsible_person_instructed_on')
                {{ $message }}
                @else
                    Gib bitte das Einschuldatum ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="instructor">Zuständiger Mitarbeiter eingeschult durch</label>
            <input type="text" class="form-control @error('instructor') is-invalid @enderror" id="instructor"
                   name="instructor" placeholder="Max Mustermann"
                   value="{{ old('instructor', optional($flowMeterInspectionReport)->instructor) }}" required/>
            <div class="invalid-feedback">
                @error('instructor')
                {{ $message }}
                @else
                    Gib bitte den Namen ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="information_providing_person">Auskunft gebender Betriebsangehöriger</label>
            <input type="text" class="form-control @error('information_providing_person') is-invalid @enderror"
                   id="information_providing_person" name="information_providing_person" placeholder="Max Mustermann"
                   value="{{ old('information_providing_person', optional($flowMeterInspectionReport)->information_providing_person) }}"
                   required/>
            <div class="invalid-feedback">
                @error('information_providing_person')
                {{ $message }}
                @else
                    Gib bitte den Namen ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="last_inspected_on">Datum der letzten Vollprüfung</label>
            <input type="date" class="form-control @error('last_inspected_on') is-invalid @enderror"
                   id="last_inspected_on" name="last_inspected_on" placeholder="1991-01-01"
                   value="{{ old('last_inspected_on', optional(optional($flowMeterInspectionReport)->last_inspected_on)->format('Y-m-d')) }}"/>
            <div class="invalid-feedback">
                @error('last_inspected_on')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="last_inspected_by">Prüfstelle der letzten Vollprüfung</label>
            <input type="text" class="form-control @error('last_inspected_by') is-invalid @enderror"
                   id="last_inspected_by" name="last_inspected_by" placeholder="Musterfirma"
                   value="{{ old('last_inspected_by', optional($flowMeterInspectionReport)->last_inspected_by) }}"/>
            <div class="invalid-feedback">
                @error('last_inspected_by')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="last_inspection_project">Projekt der letzten Vollprüfung</label>
            <input type="text" class="form-control @error('last_inspection_project') is-invalid @enderror"
                   id="last_inspection_project" name="last_inspection_project" placeholder="Musterprojekt"
                   value="{{ old('last_inspection_project', optional($flowMeterInspectionReport)->last_inspection_project) }}"/>
            <div class="invalid-feedback">
                @error('last_inspection_project')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="last_inspection_project_date">Projektdatum der letzten Vollprüfung</label>
            <input type="date" class="form-control @error('last_inspection_project_date') is-invalid @enderror"
                   id="last_inspection_project_date" name="last_inspection_project_date" placeholder="1991-01-01"
                   value="{{ old('last_inspection_project_date', optional(optional($flowMeterInspectionReport)->last_inspection_project_date)->format('Y-m-d')) }}"/>
            <div class="invalid-feedback">
                @error('last_inspection_project_date')
                {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
            </svg>
            Stationäre Messeinrichtung
        </p>
        <p class="text-muted">
            Die Eigenschaften der stationären Messeinrichtung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="profile_measurements">Profilmaße</label>
            <input type="text" class="form-control @error('profile_measurements') is-invalid @enderror"
                   id="profile_measurements" name="profile_measurements" placeholder="606-3"
                   value="{{ old('profile_measurements', optional($flowMeterInspectionReport)->profile_measurements) }}"
                   required/>
            <div class="invalid-feedback">
                @error('profile_measurements')
                {{ $message }}
                @else
                    Gib bitte die Profilmaße ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="without_cross_section_reduction">Querschnittsverengung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('without_cross_section_reduction') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('without_cross_section_reduction') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === true)) active @endif">
                    <input type="radio" name="without_cross_section_reduction" id=1 value=1 autocomplete="off"
                           @if(old('without_cross_section_reduction') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === true)) checked @endif>
                    ohne Verengung
                </label>
                <label class="btn btn-outline-secondary @if(old('without_cross_section_reduction') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === false)) active @endif">
                    <input type="radio" name="without_cross_section_reduction" id=0 value=0 autocomplete="off"
                           @if(old('without_cross_section_reduction') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === false)) checked @endif>
                    mit Verengung
                </label>
            </div>
            <div class="invalid-feedback @error('without_cross_section_reduction') d-block @enderror">
                @error('without_cross_section_reduction')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="fully_filled">Füllstand</label>
            </div>
            <div class="btn-group btn-group-toggle @error('fully_filled') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === true)) active @endif">
                    <input type="radio" name="fully_filled" id=1 value=1 autocomplete="off"
                           @if(old('fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === true)) checked @endif>
                    vollgefüllt
                </label>
                <label class="btn btn-outline-secondary @if(old('fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === false)) active @endif">
                    <input type="radio" name="fully_filled" id=0 value=0 autocomplete="off"
                           @if(old('fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === false)) checked @endif>
                    teilgefüllt
                </label>
            </div>
            <div class="invalid-feedback @error('fully_filled') d-block @enderror">
                @error('fully_filled')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="speed_measurement_type">Geschwindikteitsmessung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('speed_measurement_type') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'doppler_ultrasonic') active @endif">
                    <input type="radio" name="speed_measurement_type" id="doppler_ultrasonic" value="doppler_ultrasonic"
                           autocomplete="off"
                           @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'doppler_ultrasonic') checked @endif>
                    Doppler Ultraschall Messung
                </label>
                <label class="btn btn-outline-secondary @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_signal_transmit_time') active @endif">
                    <input type="radio" name="speed_measurement_type" id="ultrasonic_signal_transmit_time"
                           value="ultrasonic_signal_transmit_time" autocomplete="off"
                           @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_signal_transmit_time') checked @endif>
                    Ultraschall Laufzeitmessung, Signallaufzeit
                </label>
                <label class="btn btn-outline-secondary @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_cross_correlation') active @endif">
                    <input type="radio" name="speed_measurement_type" id="ultrasonic_cross_correlation"
                           value="ultrasonic_cross_correlation" autocomplete="off"
                           @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_cross_correlation') checked @endif>
                    Ultraschall Kreuzkorrelation
                </label>
                <label class="btn btn-outline-secondary @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'radar') active @endif">
                    <input type="radio" name="speed_measurement_type" id="radar" value="radar" autocomplete="off"
                           @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'radar') checked @endif>
                    Radar
                </label>
                <label class="btn btn-outline-secondary @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'other') active @endif">
                    <input type="radio" name="speed_measurement_type" id="other" value="other" autocomplete="off"
                           @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'other') checked @endif>
                    Andere
                </label>
            </div>
            <div class="invalid-feedback @error('speed_measurement_type') d-block @enderror">
                @error('speed_measurement_type')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="speed_measurement_type_other">Andere Geschwindigkeitsmessung</label>
            <input type="text" class="form-control @error('speed_measurement_type_other') is-invalid @enderror"
                   id="speed_measurement_type_other" name="speed_measurement_type_other" placeholder="Messungsart"
                   value="{{ old('speed_measurement_type_other', optional($flowMeterInspectionReport)->speed_measurement_type_other) }}"/>
            <div class="invalid-feedback">
                @error('speed_measurement_type_other')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="water_level_measurement_type">Art der Wasserstandsmessung (bei teilgefüllten
                Messstrecken)</label>
            <input type="text" class="form-control @error('water_level_measurement_type') is-invalid @enderror"
                   id="water_level_measurement_type" name="water_level_measurement_type" placeholder="Messungsart"
                   value="{{ old('water_level_measurement_type', optional($flowMeterInspectionReport)->water_level_measurement_type) }}"/>
            <div class="invalid-feedback">
                @error('water_level_measurement_type')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
            </svg>
            Funktionskontrolle Bauwerk
        </p>
        <p class="text-muted">
            Dokumentation der Funktionskontrolle des Messsystems.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="equipment_changes">Veränderungen am Messsystem</label>
            <input type="text" class="form-control @error('equipment_changes') is-invalid @enderror"
                   id="equipment_changes" name="equipment_changes" placeholder="Einbau Laufzeitmessung"
                   value="{{ old('equipment_changes', optional($flowMeterInspectionReport)->equipment_changes) }}"/>
            <div class="invalid-feedback">
                @error('equipment_changes')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="documentation_existent">Dokumentation vorhanden</label>
            </div>
            <div class="btn-group btn-group-toggle @error('documentation_existent') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('documentation_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === true)) active @endif">
                    <input type="radio" name="documentation_existent" id=1 value=1 autocomplete="off"
                           @if(old('documentation_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('documentation_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === false)) active @endif">
                    <input type="radio" name="documentation_existent" id=0 value=0 autocomplete="off"
                           @if(old('documentation_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('documentation_existent') d-block @enderror">
                @error('documentation_existent')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="inspection_book_existent">Prüfbuch vorhanden</label>
            </div>
            <div class="btn-group btn-group-toggle @error('inspection_book_existent') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('inspection_book_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === true)) active @endif">
                    <input type="radio" name="inspection_book_existent" id=1 value=1 autocomplete="off"
                           @if(old('inspection_book_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('inspection_book_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === false)) active @endif">
                    <input type="radio" name="inspection_book_existent" id=0 value=0 autocomplete="off"
                           @if(old('inspection_book_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('inspection_book_existent') d-block @enderror">
                @error('inspection_book_existent')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="inspection_requirements_existent">Wartungsvorschrift vorhanden</label>
            </div>
            <div class="btn-group btn-group-toggle @error('inspection_requirements_existent') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('inspection_requirements_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === true)) active @endif">
                    <input type="radio" name="inspection_requirements_existent" id=1 value=1 autocomplete="off"
                           @if(old('inspection_requirements_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('inspection_requirements_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === false)) active @endif">
                    <input type="radio" name="inspection_requirements_existent" id=0 value=0 autocomplete="off"
                           @if(old('inspection_requirements_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('inspection_requirements_existent') d-block @enderror">
                @error('inspection_requirements_existent')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="documentation_current">Einbaubedingungen stimmen mit der Dokumentation überein</label>
            </div>
            <div class="btn-group btn-group-toggle @error('documentation_current') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('documentation_current') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === true)) active @endif">
                    <input type="radio" name="documentation_current" id=1 value=1 autocomplete="off"
                           @if(old('documentation_current') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('documentation_current') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === false)) active @endif">
                    <input type="radio" name="documentation_current" id=0 value=0 autocomplete="off"
                           @if(old('documentation_current') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('documentation_current') d-block @enderror">
                @error('documentation_current')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="equipment_changes_to_documentation">Vorgenommene Änderungen gegenüber der Dokumentation</label>
            <input type="text" class="form-control @error('equipment_changes_to_documentation') is-invalid @enderror"
                   id="equipment_changes_to_documentation" name="equipment_changes_to_documentation"
                   placeholder="Änderungen"
                   value="{{ old('equipment_changes_to_documentation', optional($flowMeterInspectionReport)->equipment_changes_to_documentation) }}"/>
            <div class="invalid-feedback">
                @error('equipment_changes_to_documentation')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measuring_pipe_type">Messrohr Fabrikat</label>
            <input type="text" class="form-control @error('measuring_pipe_type') is-invalid @enderror"
                   id="measuring_pipe_type" name="measuring_pipe_type" placeholder="Fabrikat"
                   value="{{ old('measuring_pipe_type', optional($flowMeterInspectionReport)->measuring_pipe_type) }}"
                   required/>
            <div class="invalid-feedback">
                @error('measuring_pipe_type')
                {{ $message }}
                @else
                    Gib bitte das Fabrikat ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measuring_pipe_minimum_speed">Messrohr Mindestgeschwindikeit</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measuring_pipe_minimum_speed') is-invalid @enderror"
                       id="measuring_pipe_minimum_speed" name="measuring_pipe_minimum_speed" placeholder="0,03"
                       value="{{ old('measuring_pipe_minimum_speed', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">m/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('measuring_pipe_minimum_speed')
                    {{ $message }}
                    @else
                        Gib bitte die Mindestgeschwindigkeit ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measuring_pipe_minimum_speed_unit">Messrohr Mindesgeschwindigkeit Einheit</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measuring_pipe_minimum_speed_unit') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measuring_pipe_minimum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed_unit) == 'm_s') active @endif">
                    <input type="radio" name="measuring_pipe_minimum_speed_unit" id="m_s" value="m_s" autocomplete="off"
                           @if(old('measuring_pipe_minimum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed_unit) == 'm_s') checked @endif>
                    m/s
                </label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_minimum_speed_unit') d-block @enderror">
                @error('measuring_pipe_minimum_speed_unit')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measuring_pipe_maximum_flow_rate">Messrohr Messbereich 100% Durchflussrate</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measuring_pipe_maximum_flow_rate') is-invalid @enderror"
                       id="measuring_pipe_maximum_flow_rate" name="measuring_pipe_maximum_flow_rate" placeholder="282"
                       value="{{ old('measuring_pipe_maximum_flow_rate', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s oder m³/h</span>
                </div>
                <div class="invalid-feedback">
                    @error('measuring_pipe_maximum_flow_rate')
                    {{ $message }}
                    @else
                        Gib bitte die Durchflussrate ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measuring_pipe_maximum_flow_rate_unit">Messrohr Messbereich 100% Durchflussrate
                    Einheit</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measuring_pipe_maximum_flow_rate_unit') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'l_s') active @endif">
                    <input type="radio" name="measuring_pipe_maximum_flow_rate_unit" id="l_s" value="l_s"
                           autocomplete="off"
                           @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'l_s') checked @endif>
                    l/s
                </label>
                <label class="btn btn-outline-secondary @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'm3_h') active @endif">
                    <input type="radio" name="measuring_pipe_maximum_flow_rate_unit" id="m3_h" value="m3_h"
                           autocomplete="off"
                           @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'm3_h') checked @endif>
                    m³/h
                </label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_maximum_flow_rate_unit') d-block @enderror">
                @error('measuring_pipe_maximum_flow_rate_unit')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measuring_pipe_maximum_speed">Messrohr Messbereich 100% Geschwindigkeit</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measuring_pipe_maximum_speed') is-invalid @enderror"
                       id="measuring_pipe_maximum_speed" name="measuring_pipe_maximum_speed" placeholder="10"
                       value="{{ old('measuring_pipe_maximum_speed', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">m/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('measuring_pipe_maximum_speed')
                    {{ $message }}
                    @else
                        Gib bitte die Geschwindigkeit ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measuring_pipe_maximum_speed_unit">Messrohr Messbereich 100% Geschwindigkeit Einheit</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measuring_pipe_maximum_speed_unit') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measuring_pipe_maximum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed_unit) == 'm_s') active @endif">
                    <input type="radio" name="measuring_pipe_maximum_speed_unit" id="m_s" value="m_s" autocomplete="off"
                           @if(old('measuring_pipe_maximum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed_unit) == 'm_s') checked @endif>
                    m/s
                </label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_maximum_speed_unit') d-block @enderror">
                @error('measuring_pipe_maximum_speed_unit')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="mucus_suppression">Schleimmengenunterdrückung</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('mucus_suppression') is-invalid @enderror" id="mucus_suppression"
                       name="mucus_suppression" placeholder="1"
                       value="{{ old('mucus_suppression', optional($flowMeterInspectionReport)->mucus_suppression) }}"/>
                <div class="input-group-append">
                    <span class="input-group-text">% oder l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('mucus_suppression')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="mucus_suppression_unit">Messrohr Messbereich 100% Durchflussrate Einheit</label>
            </div>
            <div class="btn-group btn-group-toggle @error('mucus_suppression_unit') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'percent') active @endif">
                    <input type="radio" name="mucus_suppression_unit" id="percent" value="percent" autocomplete="off"
                           @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'percent') checked @endif>
                    %
                </label>
                <label class="btn btn-outline-secondary @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'l_s') active @endif">
                    <input type="radio" name="mucus_suppression_unit" id="l_s" value="l_s" autocomplete="off"
                           @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'l_s') checked @endif>
                    l/s
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('mucus_suppression_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->mucus_suppression_unit === null)) active @endif">
                    <input type="radio" name="mucus_suppression_unit" id="" value="" autocomplete="off"
                           @if((old('_token') && old('mucus_suppression_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->mucus_suppression_unit === null)) checked @endif>
                    keine Angabe
                </label>
            </div>
            <div class="invalid-feedback @error('mucus_suppression_unit') d-block @enderror">
                @error('mucus_suppression_unit')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="q_min">Beobachteter minimaler Durchfluss Q<sub>min</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('q_min') is-invalid @enderror"
                       id="q_min" name="q_min" placeholder="33"
                       value="{{ old('q_min', optional($flowMeterInspectionReport)->q_min) }}" required/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('q_min')
                    {{ $message }}
                    @else
                        Gib bitte den Durchfluss ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="q_max">Beobachteter maximaler Durchfluss Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any" class="form-control @error('q_max') is-invalid @enderror"
                       id="q_max" name="q_max" placeholder="225"
                       value="{{ old('q_max', optional($flowMeterInspectionReport)->q_max) }}" required/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('q_max')
                    {{ $message }}
                    @else
                        Gib bitte den Durchfluss ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="flow_range_type">Feststellung des Durchflussbereiches</label>
            </div>
            <div class="btn-group btn-group-toggle @error('flow_range_type') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'guess') active @endif">
                    <input type="radio" name="flow_range_type" id="guess" value="guess" autocomplete="off"
                           @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'guess') checked @endif>
                    Abschätzung
                </label>
                <label class="btn btn-outline-secondary @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'statistical_analysis') active @endif">
                    <input type="radio" name="flow_range_type" id="statistical_analysis" value="statistical_analysis"
                           autocomplete="off"
                           @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'statistical_analysis') checked @endif>
                    statistische Auswertung
                </label>
            </div>
            <div class="invalid-feedback @error('flow_range_type') d-block @enderror">
                @error('flow_range_type')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertaufnehmer Wasserstand
        </p>
        <p class="text-muted">
            Die Eigenschaften des Wasseeeerstand Messwertaufnehmers bei teilgefüllten Strecken.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="water_level_meter">System</label>
            <input type="text" class="form-control @error('water_level_meter') is-invalid @enderror"
                   id="water_level_meter" name="water_level_meter" placeholder="System"
                   value="{{ old('equipment_changes_to_documentation', optional($flowMeterInspectionReport)->water_level_meter) }}"/>
            <div class="invalid-feedback">
                @error('water_level_meter')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="water_level_meter_make">Fabrikat</label>
            <input type="text" class="form-control @error('water_level_meter_make') is-invalid @enderror"
                   id="water_level_meter_make" name="water_level_meter_make" placeholder="Fabrikat"
                   value="{{ old('water_level_meter_make', optional($flowMeterInspectionReport)->water_level_meter_make) }}"/>
            <div class="invalid-feedback">
                @error('water_level_meter_make')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="water_level_meter_type">Type</label>
            <input type="text" class="form-control @error('water_level_meter_type') is-invalid @enderror"
                   id="water_level_meter_type" name="water_level_meter_type" placeholder="Type"
                   value="{{ old('water_level_meter_type', optional($flowMeterInspectionReport)->water_level_meter_type) }}"/>
            <div class="invalid-feedback">
                @error('water_level_meter_type')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="water_level_meter_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('water_level_meter_identifier') is-invalid @enderror"
                   id="water_level_meter_identifier" name="water_level_meter_identifier" placeholder="Seriennummer"
                   value="{{ old('water_level_meter_identifier', optional($flowMeterInspectionReport)->water_level_meter_identifier) }}"/>
            <div class="invalid-feedback">
                @error('water_level_meter_identifier')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertaufnehmer Fließgeschwindigkeit
        </p>
        <p class="text-muted">
            Die Eigenschaften des Fließgeschwindigkeit Messwertaufnehmers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="flow_rate_meter">System</label>
            <input type="text" class="form-control @error('flow_rate_meter') is-invalid @enderror" id="flow_rate_meter"
                   name="flow_rate_meter" placeholder="System"
                   value="{{ old('flow_rate_meter', optional($flowMeterInspectionReport)->flow_rate_meter) }}"
                   required/>
            <div class="invalid-feedback">
                @error('flow_rate_meter')
                {{ $message }}
                @else
                    Gib bitte das System ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="flow_rate_meter_make">Fabrikat</label>
            <input type="text" class="form-control @error('flow_rate_meter_make') is-invalid @enderror"
                   id="flow_rate_meter_make" name="flow_rate_meter_make" placeholder="Fabrikat"
                   value="{{ old('flow_rate_meter_make', optional($flowMeterInspectionReport)->flow_rate_meter_make) }}"
                   required/>
            <div class="invalid-feedback">
                @error('flow_rate_meter_make')
                {{ $message }}
                @else
                    Gib bitte das Fabrikat ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="flow_rate_meter_type">Type</label>
            <input type="text" class="form-control @error('flow_rate_meter_type') is-invalid @enderror"
                   id="flow_rate_meter_type" name="flow_rate_meter_type" placeholder="Type"
                   value="{{ old('flow_rate_meter_type', optional($flowMeterInspectionReport)->flow_rate_meter_type) }}"
                   required/>
            <div class="invalid-feedback">
                @error('flow_rate_meter_type')
                {{ $message }}
                @else
                    Gib bitte die Type ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="flow_rate_meter_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('flow_rate_meter_identifier') is-invalid @enderror"
                   id="flow_rate_meter_identifier" name="flow_rate_meter_identifier" placeholder="Seriennummer"
                   value="{{ old('flow_rate_meter_identifier', optional($flowMeterInspectionReport)->flow_rate_meter_identifier) }}"
                   required/>
            <div class="invalid-feedback">
                @error('flow_rate_meter_identifier')
                {{ $message }}
                @else
                    Gib bitte die Seriennumer ein.
                    @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertumformer
        </p>
        <p class="text-muted">
            Die Eigenschaften des Messwertumformers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="measurement_transformer_point">Ort der Umformung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_transformer_point') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'local') active @endif">
                    <input type="radio" name="measurement_transformer_point" id="local" value="local" autocomplete="off"
                           @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'local') checked @endif>
                    vor Ort
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'control_stand') active @endif">
                    <input type="radio" name="measurement_transformer_point" id="control_stand" value="control_stand"
                           autocomplete="off"
                           @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'control_stand') checked @endif>
                    Warte
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_transformer_point') d-block @enderror">
                @error('measurement_transformer_point')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_make">Fabrikat</label>
            <input type="text" class="form-control @error('measurement_transformer_make') is-invalid @enderror"
                   id="measurement_transformer_make" name="measurement_transformer_make" placeholder="Fabrikat"
                   value="{{ old('measurement_transformer_make', optional($flowMeterInspectionReport)->measurement_transformer_make) }}"
                   required/>
            <div class="invalid-feedback">
                @error('measurement_transformer_make')
                {{ $message }}
                @else
                    Gib bitte das Fabrikat ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_type">Type</label>
            <input type="text" class="form-control @error('measurement_transformer_type') is-invalid @enderror"
                   id="measurement_transformer_type" name="measurement_transformer_type" placeholder="Type"
                   value="{{ old('measurement_transformer_type', optional($flowMeterInspectionReport)->measurement_transformer_type) }}"
                   required/>
            <div class="invalid-feedback">
                @error('measurement_transformer_type')
                {{ $message }}
                @else
                    Gib bitte die Type ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('measurement_transformer_identifier') is-invalid @enderror"
                   id="measurement_transformer_identifier" name="measurement_transformer_identifier"
                   placeholder="Seriennummer"
                   value="{{ old('measurement_transformer_identifier', optional($flowMeterInspectionReport)->measurement_transformer_identifier) }}"
                   required/>
            <div class="invalid-feedback">
                @error('measurement_transformer_identifier')
                {{ $message }}
                @else
                    Gib bitte die Seriennumer ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_minimum_level">minimaler Signalausgang</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_minimum_level') is-invalid @enderror"
                       id="measurement_transformer_minimum_level" name="measurement_transformer_minimum_level"
                       placeholder="4"
                       value="{{ old('measurement_transformer_minimum_level', optional($flowMeterInspectionReport)->measurement_transformer_minimum_level) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">mA oder V oder Anderes</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_transformer_minimum_level')
                    {{ $message }}
                    @else
                        Gib bitte den minimalen Signalausgang ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_maximum_level">maximaler Signalausgang</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_maximum_level') is-invalid @enderror"
                       id="measurement_transformer_maximum_level" name="measurement_transformer_maximum_level"
                       placeholder="20"
                       value="{{ old('measurement_transformer_maximum_level', optional($flowMeterInspectionReport)->measurement_transformer_maximum_level) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">mA oder V oder Anderes</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_transformer_maximum_level')
                    {{ $message }}
                    @else
                        Gib bitte den maximalen Signalausgang ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_transformer_level_unit">Messeinheit</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_transformer_level_unit') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'mA') active @endif">
                    <input type="radio" name="measurement_transformer_level_unit" id="mA" value="mA" autocomplete="off"
                           @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'mA') checked @endif>
                    mA
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'V') active @endif">
                    <input type="radio" name="measurement_transformer_level_unit" id="V" value="V" autocomplete="off"
                           @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'V') checked @endif>
                    V
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'other') active @endif">
                    <input type="radio" name="measurement_transformer_level_unit" id="other" value="other"
                           autocomplete="off"
                           @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'other') checked @endif>
                    Andere
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_transformer_level_unit') d-block @enderror">
                @error('measurement_transformer_level_unit')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_level_unit_other">Andere Messeinheit</label>
            <input type="text"
                   class="form-control @error('measurement_transformer_level_unit_other') is-invalid @enderror"
                   id="measurement_transformer_level_unit_other" name="measurement_transformer_level_unit_other"
                   placeholder="Buswert"
                   value="{{ old('measurement_transformer_level_unit_other', optional($flowMeterInspectionReport)->measurement_transformer_level_unit_other) }}"/>
            <div class="invalid-feedback">
                @error('measurement_transformer_level_unit_other')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_range_100_percent">Programmierter Messbereich 100%</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_range_100_percent') is-invalid @enderror"
                       id="measurement_transformer_range_100_percent" name="measurement_transformer_range_100_percent"
                       placeholder="250"
                       value="{{ old('measurement_transformer_range_100_percent', optional($flowMeterInspectionReport)->measurement_transformer_range_100_percent) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_transformer_range_100_percent')
                    {{ $message }}
                    @else
                        Gib bitte den Messbereich ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_impulses">Impulsausgang</label>
            <div class="input-group">
                <input type="number" min="0"
                       class="form-control @error('measurement_transformer_impulses') is-invalid @enderror"
                       id="measurement_transformer_impulses" name="measurement_transformer_impulses" placeholder="1"
                       value="{{ old('measurement_transformer_impulses', optional($flowMeterInspectionReport)->measurement_transformer_impulses) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">Impulse/m³</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_transformer_impulses')
                    {{ $message }}
                    @else
                        Gib bitte die Impulse ein.
                        @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Datenaufzeichnung
        </p>
        <p class="text-muted">
            Die Eigenschaften der Datenaufzeichnung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="measurement_transformer_data_logging">Art der Datenaufzeichnung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_transformer_data_logging') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'display') active @endif">
                    <input type="radio" name="measurement_transformer_data_logging" id="display" value="display"
                           autocomplete="off"
                           @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'display') checked @endif>
                    Anzeige Messwertumformer
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'registering_device') active @endif">
                    <input type="radio" name="measurement_transformer_data_logging" id="registering_device"
                           value="registering_device" autocomplete="off"
                           @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'registering_device') checked @endif>
                    Registriergerät
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'pcs') active @endif">
                    <input type="radio" name="measurement_transformer_data_logging" id="pcs" value="pcs"
                           autocomplete="off"
                           @if(old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) == 'pcs') checked @endif>
                    Prozessleitsystem
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_transformer_data_logging') d-block @enderror">
                @error('measurement_transformer_data_logging')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_registering_device_make">Registriergerät Fabrikat</label>
            <input type="text"
                   class="form-control @error('measurement_transformer_registering_device_make') is-invalid @enderror"
                   id="measurement_transformer_registering_device_make"
                   name="measurement_transformer_registering_device_make" placeholder="Fabrikat"
                   value="{{ old('measurement_transformer_registering_device_make', optional($flowMeterInspectionReport)->measurement_transformer_registering_device_make) }}"/>
            <div class="invalid-feedback">
                @error('measurement_transformer_registering_device_make')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_registering_device_type">Registriergerät Type</label>
            <input type="text"
                   class="form-control @error('measurement_transformer_registering_device_type') is-invalid @enderror"
                   id="measurement_transformer_registering_device_type"
                   name="measurement_transformer_registering_device_type" placeholder="Type"
                   value="{{ old('measurement_transformer_registering_device_type', optional($flowMeterInspectionReport)->measurement_transformer_registering_device_type) }}"/>
            <div class="invalid-feedback">
                @error('measurement_transformer_registering_device_type')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_transformer_registering_device_identifier">Registriergerät Seriennummer</label>
            <input type="text"
                   class="form-control @error('measurement_transformer_registering_device_identifier') is-invalid @enderror"
                   id="measurement_transformer_registering_device_identifier"
                   name="measurement_transformer_registering_device_identifier" placeholder="Seriennummer"
                   value="{{ old('measurement_transformer_registering_device_identifier', optional($flowMeterInspectionReport)->measurement_transformer_registering_device_identifier) }}"/>
            <div class="invalid-feedback">
                @error('measurement_transformer_registering_device_identifier')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#water"></use>
            </svg>
            Bestandsaufnahme Oberwasserseite
        </p>
        <p class="text-muted">
            Die Bestandsaufnahme der Oberwasserseite.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="headwater_pipe_diameter">Rohrdurchmesser</label>
            <div class="input-group">
                <input type="number" min="0" class="form-control @error('headwater_pipe_diameter') is-invalid @enderror"
                       id="headwater_pipe_diameter" name="headwater_pipe_diameter" placeholder="600"
                       value="{{ old('headwater_pipe_diameter', optional($flowMeterInspectionReport)->headwater_pipe_diameter) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">mm</span>
                </div>
                <div class="invalid-feedback">
                    @error('headwater_pipe_diameter')
                    {{ $message }}
                    @else
                        Gib bitte den Durchmesser ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="headwater_calming_section">Länge der einlaufseitigen Beruhigungsstrecke</label>
            <input type="text" class="form-control @error('headwater_calming_section') is-invalid @enderror"
                   id="headwater_calming_section" name="headwater_calming_section" placeholder="Dücker"
                   value="{{ old('headwater_calming_section', optional($flowMeterInspectionReport)->headwater_calming_section) }}"
                   required/>
            <div class="invalid-feedback">
                @error('headwater_calming_section')
                {{ $message }}
                @else
                    Gib bitte die Länge ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="headwater_calming_section_assessment">Beurteilung der Beruhigungsstrecke</label>
            <input type="text" class="form-control @error('headwater_calming_section_assessment') is-invalid @enderror"
                   id="headwater_calming_section_assessment" name="headwater_calming_section_assessment"
                   placeholder="ausreichend gleichmäßig"
                   value="{{ old('headwater_calming_section_assessment', optional($flowMeterInspectionReport)->headwater_calming_section_assessment) }}"
                   required/>
            <div class="invalid-feedback">
                @error('headwater_calming_section_assessment')
                {{ $message }}
                @else
                    Gib bitte die Beurteilung ein.
                    @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#moisture"></use>
            </svg>
            Bestandsaufnahme Messstrecke
        </p>
        <p class="text-muted">
            Die Bestandsaufnahme der Messstrecke.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="measurement_section_slope">Gefälle der Messstrecke</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_section_slope') is-invalid @enderror"
                       id="measurement_section_slope" name="measurement_section_slope" placeholder="9"
                       value="{{ old('measurement_section_slope', optional($flowMeterInspectionReport)->measurement_section_slope) }}"/>
                <div class="input-group-append">
                    <span class="input-group-text">‰</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_section_slope')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_section_slope_assessment_type">Vermessung durchgeführt mittels</label>
            <input type="text"
                   class="form-control @error('measurement_section_slope_assessment_type') is-invalid @enderror"
                   id="measurement_section_slope_assessment_type" name="measurement_section_slope_assessment_type"
                   placeholder="Vermessungsart"
                   value="{{ old('measurement_section_slope_assessment_type', optional($flowMeterInspectionReport)->measurement_section_slope_assessment_type) }}"/>
            <div class="invalid-feedback">
                @error('measurement_section_slope_assessment_type')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_installation_according_to_manufacturer">Einbaubedingungen laut
                    Hersteller erfüllt</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_installation_according_to_manufacturer') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_installation_according_to_manufacturer') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === true)) active @endif">
                    <input type="radio" name="measurement_section_installation_according_to_manufacturer" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_installation_according_to_manufacturer') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_installation_according_to_manufacturer') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === false)) active @endif">
                    <input type="radio" name="measurement_section_installation_according_to_manufacturer" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_installation_according_to_manufacturer') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_installation_according_to_manufacturer') d-block @enderror">
                @error('measurement_section_installation_according_to_manufacturer')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_section_minimum_speed_undercut_point">Unterschreitung der Mindestgeschwindigkeit
                unterhalb von</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_section_minimum_speed_undercut_point') is-invalid @enderror"
                       id="measurement_section_minimum_speed_undercut_point"
                       name="measurement_section_minimum_speed_undercut_point" placeholder="1"
                       value="{{ old('measurement_section_minimum_speed_undercut_point', optional($flowMeterInspectionReport)->measurement_section_minimum_speed_undercut_point) }}"/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_section_minimum_speed_undercut_point')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#moisture"></use>
            </svg>
            Beurteilung der Messstrecke
        </p>
        <p class="text-muted">
            Die Beurteilung der Messstrecke im eingebauten Zustand.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="measurement_section_pipe_diameter">Querschnitt des Messrohrese</label>
            <div class="input-group">
                <input type="number" min="0"
                       class="form-control @error('measurement_section_pipe_diameter') is-invalid @enderror"
                       id="measurement_section_pipe_diameter" name="measurement_section_pipe_diameter" placeholder="600"
                       value="{{ old('measurement_section_pipe_diameter', optional($flowMeterInspectionReport)->measurement_section_pipe_diameter) }}"
                       required />
                <div class="input-group-append">
                    <span class="input-group-text">mm</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_section_pipe_diameter')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_access_possible">Zugänglichkeit gegeben</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_access_possible') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_access_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === true)) active @endif">
                    <input type="radio" name="measurement_section_access_possible" id=1 value=1 autocomplete="off"
                           @if(old('measurement_section_access_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_access_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === false)) active @endif">
                    <input type="radio" name="measurement_section_access_possible" id=0 value=0 autocomplete="off"
                           @if(old('measurement_section_access_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_access_possible') d-block @enderror">
                @error('measurement_section_access_possible')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_required_fill_level_existent">Geforderte Fließtiefe bei der
                    Vergleichsmessung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_required_fill_level_existent') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_required_fill_level_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_required_fill_level_existent" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_required_fill_level_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_required_fill_level_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_required_fill_level_existent" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_required_fill_level_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_required_fill_level_existent') d-block @enderror">
                @error('measurement_section_pipe_required_fill_level_existent')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_visible_inspection_inside_possible">Optische Kontrolle im Messrohr
                    möglich</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_visible_inspection_inside_possible') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_visible_inspection_inside_possible" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_visible_inspection_inside_possible" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_visible_inspection_inside_possible') d-block @enderror">
                @error('measurement_section_pipe_visible_inspection_inside_possible')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_section_pipe_visible_inspection_inside">Alternative Möglichkeit zur inneren
                optischen Kontrolle</label>
            <input type="text"
                   class="form-control @error('measurement_section_pipe_visible_inspection_inside') is-invalid @enderror"
                   id="measurement_section_pipe_visible_inspection_inside"
                   name="measurement_section_pipe_visible_inspection_inside" placeholder="Inspektionsöffnung"
                   value="{{ old('measurement_section_pipe_visible_inspection_inside', optional($flowMeterInspectionReport)->measurement_section_pipe_visible_inspection_inside) }}"/>
            <div class="invalid-feedback">
                @error('measurement_section_pipe_visible_inspection_inside')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_contaminated">Ablagerungen, Verschmutzungen im Messrohr, am
                    Messwertaufnehmer</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_contaminated') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_contaminated') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_contaminated" id=1 value=1 autocomplete="off"
                           @if(old('measurement_section_pipe_contaminated') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_contaminated') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_contaminated" id=0 value=0 autocomplete="off"
                           @if(old('measurement_section_pipe_contaminated') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_contaminated') d-block @enderror">
                @error('measurement_section_pipe_contaminated')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_cleaning_possible">Reinigung des Messrohres möglich</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_cleaning_possible') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_cleaning_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_cleaning_possible" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_cleaning_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_cleaning_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_cleaning_possible" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_cleaning_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_cleaning_possible') d-block @enderror">
                @error('measurement_section_pipe_cleaning_possible')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_section_pipe_last_cleaned_on">Letzte Reinigung des Messrohrs</label>
            <input type="date"
                   class="form-control @error('measurement_section_pipe_last_cleaned_on') is-invalid @enderror"
                   id="measurement_section_pipe_last_cleaned_on" name="measurement_section_pipe_last_cleaned_on"
                   placeholder=""
                   value="{{ old('measurement_section_pipe_last_cleaned_on', optional(optional($flowMeterInspectionReport)->measurement_section_pipe_last_cleaned_on)->format('Y-m-d')) }}"/>
            <div class="invalid-feedback">
                @error('measurement_section_pipe_last_cleaned_on')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_sensor_cleaned">Messwertaufnehmer ist gereinigt</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_sensor_cleaned') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_sensor_cleaned') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === true)) active @endif">
                    <input type="radio" name="measurement_section_sensor_cleaned" id=1 value=1 autocomplete="off"
                           @if(old('measurement_section_sensor_cleaned') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_sensor_cleaned') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === false)) active @endif">
                    <input type="radio" name="measurement_section_sensor_cleaned" id=0 value=0 autocomplete="off"
                           @if(old('measurement_section_sensor_cleaned') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_sensor_cleaned') d-block @enderror">
                @error('measurement_section_sensor_cleaned')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_sensor_damaged">Messwertaufnehmer ist mechanisch beschädigt</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_sensor_damaged') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_sensor_damaged') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === true)) active @endif">
                    <input type="radio" name="measurement_section_sensor_damaged" id=1 value=1 autocomplete="off"
                           @if(old('measurement_section_sensor_damaged') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_sensor_damaged') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === false)) active @endif">
                    <input type="radio" name="measurement_section_sensor_damaged" id=0 value=0 autocomplete="off"
                           @if(old('measurement_section_sensor_damaged') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_sensor_damaged') d-block @enderror">
                @error('measurement_section_sensor_damaged')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_inside_surface_ok">Innere Oberfläche in Ordnung</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_inside_surface_ok') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_inside_surface_ok') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_inside_surface_ok" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_inside_surface_ok') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_inside_surface_ok') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_inside_surface_ok" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_inside_surface_ok') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_inside_surface_ok') d-block @enderror">
                @error('measurement_section_pipe_inside_surface_ok')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_grounding_existent">Erdung des Messrohres ist gegeben</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_grounding_existent') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_grounding_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_grounding_existent" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_grounding_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_grounding_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_grounding_existent" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_grounding_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_grounding_existent') d-block @enderror">
                @error('measurement_section_pipe_grounding_existent')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="measurement_section_pipe_air_pockets_visible">Lufteinschlüsse erkennbar</label>
            </div>
            <div class="btn-group btn-group-toggle @error('measurement_section_pipe_air_pockets_visible') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_air_pockets_visible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === true)) active @endif">
                    <input type="radio" name="measurement_section_pipe_air_pockets_visible" id=1 value=1
                           autocomplete="off"
                           @if(old('measurement_section_pipe_air_pockets_visible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('measurement_section_pipe_air_pockets_visible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === false)) active @endif">
                    <input type="radio" name="measurement_section_pipe_air_pockets_visible" id=0 value=0
                           autocomplete="off"
                           @if(old('measurement_section_pipe_air_pockets_visible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_air_pockets_visible') d-block @enderror">
                @error('measurement_section_pipe_air_pockets_visible')
                {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#water"></use>
            </svg>
            Bestandsaufnahme Unterwasserseite
        </p>
        <p class="text-muted">
            Die Bestandsaufnahme der Unterwasserseite.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="tailwater_pipe_diameter">Rohrdurchmesser</label>
            <div class="input-group">
                <input type="number" min="0" class="form-control @error('tailwater_pipe_diameter') is-invalid @enderror"
                       id="tailwater_pipe_diameter" name="tailwater_pipe_diameter" placeholder="600"
                       value="{{ old('tailwater_pipe_diameter', optional($flowMeterInspectionReport)->tailwater_pipe_diameter) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">mm</span>
                </div>
                <div class="invalid-feedback">
                    @error('tailwater_pipe_diameter')
                    {{ $message }}
                    @else
                        Gib bitte den Durchmesser ein.
                        @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="tailwater_pipe_fully_filled">Füllstand</label>
            </div>
            <div class="btn-group btn-group-toggle @error('tailwater_pipe_fully_filled') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('tailwater_pipe_fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === true)) active @endif">
                    <input type="radio" name="tailwater_pipe_fully_filled" id=1 value=1 autocomplete="off"
                           @if(old('tailwater_pipe_fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === true)) checked @endif>
                    vollgefüllt
                </label>
                <label class="btn btn-outline-secondary @if(old('tailwater_pipe_fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === false)) active @endif">
                    <input type="radio" name="tailwater_pipe_fully_filled" id=0 value=0 autocomplete="off"
                           @if(old('tailwater_pipe_fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === false)) checked @endif>
                    teilgefüllt
                </label>
            </div>
            <div class="invalid-feedback @error('tailwater_pipe_fully_filled') d-block @enderror">
                @error('tailwater_pipe_fully_filled')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="tailwater_runout_section_slope">Gefälle der Auslaufstrecke</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('tailwater_runout_section_slope') is-invalid @enderror"
                       id="tailwater_runout_section_slope" name="tailwater_runout_section_slope" placeholder="9"
                       value="{{ old('tailwater_runout_section_slope', optional($flowMeterInspectionReport)->tailwater_runout_section_slope) }}"/>
                <div class="input-group-append">
                    <span class="input-group-text">‰</span>
                </div>
                <div class="invalid-feedback">
                    @error('tailwater_runout_section_slope')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tailwater_runout_section_slope_assessment_type">Vermessung durchgeführt mittels</label>
            <input type="text"
                   class="form-control @error('tailwater_runout_section_slope_assessment_type') is-invalid @enderror"
                   id="tailwater_runout_section_slope_assessment_type"
                   name="tailwater_runout_section_slope_assessment_type" placeholder="Vermessungsart"
                   value="{{ old('tailwater_runout_section_slope_assessment_type', optional($flowMeterInspectionReport)->tailwater_runout_section_slope_assessment_type) }}"/>
            <div class="invalid-feedback">
                @error('tailwater_runout_section_slope_assessment_type')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#water"></use>
            </svg>
            Beurteilung der Auslaufstrecke
        </p>
        <p class="text-muted">
            Die Beurteilung der Auslaufstrecke.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="tailwater_runout_section_assessment">Beurteilung der Auslaufstrecke</label>
            <input type="text" class="form-control @error('tailwater_runout_section_assessment') is-invalid @enderror"
                   id="tailwater_runout_section_assessment" name="tailwater_runout_section_assessment"
                   placeholder="ausreichend gleichmäßig"
                   value="{{ old('tailwater_runout_section_assessment', optional($flowMeterInspectionReport)->tailwater_runout_section_assessment) }}"
                   required/>
            <div class="invalid-feedback">
                @error('tailwater_runout_section_assessment')
                {{ $message }}
                @else
                    Gib bitte die Beurteilung ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="tailwater_measurement_pipe_can_run_dry">Leerlaufen des Messrohrs ist möglich</label>
            </div>
            <div class="btn-group btn-group-toggle @error('tailwater_measurement_pipe_can_run_dry') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('tailwater_measurement_pipe_can_run_dry') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === true)) active @endif">
                    <input type="radio" name="tailwater_measurement_pipe_can_run_dry" id=1 value=1 autocomplete="off"
                           @if(old('tailwater_measurement_pipe_can_run_dry') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('tailwater_measurement_pipe_can_run_dry') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === false)) active @endif">
                    <input type="radio" name="tailwater_measurement_pipe_can_run_dry" id=0 value=0 autocomplete="off"
                           @if(old('tailwater_measurement_pipe_can_run_dry') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('tailwater_measurement_pipe_can_run_dry') d-block @enderror">
                @error('tailwater_measurement_pipe_can_run_dry')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="tailwater_flow_conditions_influenced">Strömungsverhältnisse werden vom Vorfluter oder
                    anderen Einleitungen beeinflusst</label>
            </div>
            <div class="btn-group btn-group-toggle @error('tailwater_flow_conditions_influenced') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('tailwater_flow_conditions_influenced') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === true)) active @endif">
                    <input type="radio" name="tailwater_flow_conditions_influenced" id=1 value=1 autocomplete="off"
                           @if(old('tailwater_flow_conditions_influenced') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('tailwater_flow_conditions_influenced') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === false)) active @endif">
                    <input type="radio" name="tailwater_flow_conditions_influenced" id=0 value=0 autocomplete="off"
                           @if(old('tailwater_flow_conditions_influenced') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('tailwater_flow_conditions_influenced') d-block @enderror">
                @error('tailwater_flow_conditions_influenced')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="tailwater_flow_conditions_influencer">Beeinflussung durch</label>
            <input type="text" class="form-control @error('tailwater_flow_conditions_influencer') is-invalid @enderror"
                   id="tailwater_flow_conditions_influencer" name="tailwater_flow_conditions_influencer"
                   placeholder="Beinflussung"
                   value="{{ old('tailwater_flow_conditions_influencer', optional($flowMeterInspectionReport)->tailwater_flow_conditions_influencer) }}"/>
            <div class="invalid-feedback">
                @error('tailwater_flow_conditions_influencer')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#check-circle"></use>
            </svg>
            Funktionskontrolle Messsystem
        </p>
        <p class="text-muted">
            Details zur Kontrolle der Messwert Anzeige bei Null-Durchfluss.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="zero_flow_rate_testing_conditions">Wie wird der Null-Durchfluss geprüft?</label>
            <input type="text" class="form-control @error('zero_flow_rate_testing_conditions') is-invalid @enderror"
                   id="zero_flow_rate_testing_conditions" name="zero_flow_rate_testing_conditions"
                   placeholder="Schieber zu"
                   value="{{ old('zero_flow_rate_testing_conditions', optional($flowMeterInspectionReport)->zero_flow_rate_testing_conditions) }}"
                   required/>
            <div class="invalid-feedback">
                @error('zero_flow_rate_testing_conditions')
                {{ $message }}
                @else
                    Gib bitte die Umstände der Prüfung ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="zero_flow_rate_reading_points">Ablesepunkte des Null-Durchflusses</label>
            <input type="text" class="form-control @error('zero_flow_rate_reading_points') is-invalid @enderror"
                   id="zero_flow_rate_reading_points" name="zero_flow_rate_reading_points"
                   placeholder="Vorort, Prozessleitsystem"
                   value="{{ old('zero_flow_rate_reading_points', optional($flowMeterInspectionReport)->zero_flow_rate_reading_points) }}"
                   required/>
            <div class="invalid-feedback">
                @error('zero_flow_rate_reading_points')
                {{ $message }}
                @else
                    Gib bitte die Ablesepunkte ein.
                    @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="zero_flow_rate_displayed_flow">Angezeigter Durchfluss bei Null-Durchfluss</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('zero_flow_rate_displayed_flow') is-invalid @enderror"
                       id="zero_flow_rate_displayed_flow" name="zero_flow_rate_displayed_flow" placeholder="0"
                       value="{{ old('zero_flow_rate_displayed_flow', optional($flowMeterInspectionReport)->zero_flow_rate_displayed_flow) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">l/s</span>
                </div>
                <div class="invalid-feedback">
                    @error('zero_flow_rate_displayed_flow')
                    {{ $message }}
                    @else
                        Gib bitte den angezeigten Durchfluss ein.
                        @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#plus-slash-minus"></use>
            </svg>
            Vergleichsmessung
        </p>
        <p class="text-muted">
            Details zum Messverfahren der durchgeführten Vergleichsmessung.
        </p>
    </div>

    <div class="col-md-8">
        <ul class="nav nav-tabs nav-fill mb-2" id="comparison-measurement" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="mobile-tab" data-toggle="tab" href="#mobile" role="tab"
                   aria-controls="mobile" aria-selected="true"
                   onclick="document.getElementById('comparison_measurements_process').value = 'mobile_measurement_equipment'">mobile
                    Messeinrichtung</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="volumetric-tab" data-toggle="tab" href="#volumetric" role="tab"
                   aria-controls="volumetric" aria-selected="false"
                   onclick="document.getElementById('comparison_measurements_process').value = 'volumetric'">volumetrisch</a>
            </li>
        </ul>

        <input type="hidden" id="comparison_measurements_process" name="comparison_measurements_process"
               value="mobile_measurement_equipment">

        <div class="tab-content" id="comparison-measurement-content">
            <div class="tab-pane fade show active" id="mobile" role="tabpanel" aria-labelledby="mobile-tab">
                <div class="form-group">
                    <div>
                        <label for="comparison_measurement_mobile_type">Geschwindikteitsmessung</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('comparison_measurement_mobile_type') is-invalid @enderror"
                         data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'doppler_ultrasonic') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_type" id="doppler_ultrasonic"
                                   value="doppler_ultrasonic" autocomplete="off"
                                   @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'doppler_ultrasonic') checked @endif>
                            Doppler Ultraschall Messung
                        </label>
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_signal_transmit_time') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_type"
                                   id="ultrasonic_signal_transmit_time" value="ultrasonic_signal_transmit_time"
                                   autocomplete="off"
                                   @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_signal_transmit_time') checked @endif>
                            Ultraschall Laufzeitmessung, Signallaufzeit
                        </label>
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_cross_correlation') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_type"
                                   id="ultrasonic_cross_correlation" value="ultrasonic_cross_correlation"
                                   autocomplete="off"
                                   @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_cross_correlation') checked @endif>
                            Ultraschall Kreuzkorrelation
                        </label>
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'radar') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_type" id="radar" value="radar"
                                   autocomplete="off"
                                   @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'radar') checked @endif>
                            Radar
                        </label>
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'other') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_type" id="other" value="other"
                                   autocomplete="off"
                                   @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'other') checked @endif>
                            Andere
                        </label>
                    </div>
                    <div class="invalid-feedback @error('comparison_measurement_mobile_type') d-block @enderror">
                        @error('comparison_measurement_mobile_type')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_type_other">Andere Geschwindigkeitsmessung</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_type_other') is-invalid @enderror"
                           id="comparison_measurement_mobile_type_other" name="comparison_measurement_mobile_type_other"
                           placeholder="Messungsart"
                           value="{{ old('comparison_measurement_mobile_type_other', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type_other) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_type_other')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_installation_point">Einbauort der
                        Vergleichsmessung</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_installation_point') is-invalid @enderror"
                           id="comparison_measurement_mobile_installation_point"
                           name="comparison_measurement_mobile_installation_point" placeholder="Einbauort"
                           value="{{ old('comparison_measurement_mobile_installation_point', optional($flowMeterInspectionReport)->comparison_measurement_mobile_installation_point) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_installation_point')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_make">Prüfmittel Fabrikat</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_equipment_make') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_make"
                           name="comparison_measurement_mobile_equipment_make" placeholder="Fabrikat"
                           value="{{ old('comparison_measurement_mobile_equipment_make', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_make) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_make')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_type">Prüfmittel Type</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_equipment_type') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_type"
                           name="comparison_measurement_mobile_equipment_type" placeholder="Type"
                           value="{{ old('comparison_measurement_mobile_equipment_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_type) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_type')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_identifier">Prüfmittel Seriennummer</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_equipment_identifier') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_identifier"
                           name="comparison_measurement_mobile_equipment_identifier" placeholder="Seriennummer"
                           value="{{ old('comparison_measurement_mobile_equipment_identifier', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_identifier) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_identifier')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_q_min">Minimaler Durchfluss Q<sub>min</sub>
                        (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_q_min') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_q_min"
                               name="comparison_measurement_mobile_equipment_q_min" placeholder="0"
                               value="{{ old('comparison_measurement_mobile_equipment_q_min', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_q_min) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">l/s</span>
                        </div>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_q_min')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_maximum_flow_rate">Messbereich 100%
                        Durchflussrate (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_maximum_flow_rate') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_maximum_flow_rate"
                               name="comparison_measurement_mobile_equipment_maximum_flow_rate" placeholder="282"
                               value="{{ old('comparison_measurement_mobile_equipment_maximum_flow_rate', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">l/s oder m³/h</span>
                        </div>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_maximum_flow_rate')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_flow_rate_unit">Messbereich 100%
                            Durchflussrate Einheit</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit') is-invalid @enderror"
                         data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'l_s') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_equipment_maximum_flow_rate_unit"
                                   id="l_s" value="l_s" autocomplete="off"
                                   @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'l_s') checked @endif>
                            l/s
                        </label>
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'm3_h') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_equipment_maximum_flow_rate_unit"
                                   id="m3_h" value="m3_h" autocomplete="off"
                                   @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'm3_h') checked @endif>
                            m³/h
                        </label>
                    </div>
                    <div class="invalid-feedback @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit') d-block @enderror">
                        @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_maximum_speed">Messbereich 100% Geschwindigkeit
                        (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_maximum_speed') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_maximum_speed"
                               name="comparison_measurement_mobile_equipment_maximum_speed" placeholder="10"
                               value="{{ old('comparison_measurement_mobile_equipment_maximum_speed', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">m/s</span>
                        </div>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_maximum_speed')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_speed_unit">Messbereich 100%
                            Geschwindigkeit Einheit</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('comparison_measurement_mobile_equipment_maximum_speed_unit') is-invalid @enderror"
                         data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('comparison_measurement_mobile_equipment_maximum_speed_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed_unit) == 'm_s') active @endif">
                            <input type="radio" name="comparison_measurement_mobile_equipment_maximum_speed_unit"
                                   id="m_s" value="m_s" autocomplete="off"
                                   @if(old('comparison_measurement_mobile_equipment_maximum_speed_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed_unit) == 'm_s') checked @endif>
                            m/s
                        </label>
                    </div>
                    <div class="invalid-feedback @error('comparison_measurement_mobile_equipment_maximum_speed_unit') d-block @enderror">
                        @error('comparison_measurement_mobile_equipment_maximum_speed_unit')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_last_calibrated_on">Letzte Kalibrierung</label>
                    <input type="date"
                           class="form-control @error('comparison_measurement_mobile_equipment_last_calibrated_on') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_last_calibrated_on"
                           name="comparison_measurement_mobile_equipment_last_calibrated_on" placeholder=""
                           value="{{ old('comparison_measurement_mobile_equipment_last_calibrated_on', optional(optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_calibrated_on)->format('Y-m-d')) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_last_calibrated_on')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_last_cal_provider">Kalibrierung
                        durchgeführt von</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_equipment_last_cal_provider') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_last_cal_provider"
                           name="comparison_measurement_mobile_equipment_last_cal_provider"
                           placeholder="Kalibrierungsstelle"
                           value="{{ old('comparison_measurement_mobile_equipment_last_cal_provider', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_cal_provider) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_last_cal_provider')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_mobile_equipment_last_cal_doc_identifier">Kalibrierung
                        Dokumentation, Geschäftszahl</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_mobile_equipment_last_cal_doc_identifier') is-invalid @enderror"
                           id="comparison_measurement_mobile_equipment_last_cal_doc_identifier"
                           name="comparison_measurement_mobile_equipment_last_cal_doc_identifier"
                           placeholder="Dokumentation"
                           value="{{ old('comparison_measurement_mobile_equipment_last_cal_doc_identifier', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_cal_doc_identifier) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_mobile_equipment_last_cal_doc_identifier')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="volumetric" role="tabpanel" aria-labelledby="volumetric-tab">
                <div class="form-group">
                    <label for="comparison_measurement_volumetric_basin">Förderbecken</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_volumetric_basin') is-invalid @enderror"
                           id="comparison_measurement_volumetric_basin" name="comparison_measurement_volumetric_basin"
                           placeholder="Förderbecken"
                           value="{{ old('comparison_measurement_volumetric_basin', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_basin) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_volumetric_basin')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_volumetric_basin_cross_section_area">Querschnittsfläche
                        des Vergleichsbehälters</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_volumetric_basin_cross_section_area') is-invalid @enderror"
                               id="comparison_measurement_volumetric_basin_cross_section_area"
                               name="comparison_measurement_volumetric_basin_cross_section_area"
                               placeholder="25"
                               value="{{ old('comparison_measurement_volumetric_basin_cross_section_area', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_basin_cross_section_area) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">m²</span>
                        </div>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_volumetric_basin_cross_section_area')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comparison_measurement_volumetric_height_measurement_equipment">Verwendete
                        Höhenmessung</label>
                    <input type="text"
                           class="form-control @error('comparison_measurement_volumetric_height_measurement_equipment') is-invalid @enderror"
                           id="comparison_measurement_volumetric_height_measurement_equipment"
                           name="comparison_measurement_volumetric_height_measurement_equipment"
                           placeholder="Höhenmessung"
                           value="{{ old('comparison_measurement_volumetric_height_measurement_equipment', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_height_measurement_equipment) }}"/>
                    <div class="invalid-feedback">
                        @error('comparison_measurement_volumetric_height_measurement_equipment')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#card-text"></use>
            </svg>
            Dokumentation der Vergleichsmessung
        </p>
        <p class="text-muted">
            Details zur durchgeführten Vergleichsmessung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="comparison_measurement_measurement_transformer_checked">Messwertumformer Zähler
                    überprüft</label>
            </div>
            <div class="btn-group btn-group-toggle @error('comparison_measurement_measurement_transformer_checked') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('comparison_measurement_measurement_transformer_checked') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_measurement_transformer_checked === true)) active @endif">
                    <input type="radio" name="comparison_measurement_measurement_transformer_checked" id=1 value=1
                           autocomplete="off"
                           @if(old('comparison_measurement_measurement_transformer_checked') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_measurement_transformer_checked === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('comparison_measurement_measurement_transformer_checked') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_measurement_transformer_checked === false)) active @endif">
                    <input type="radio" name="comparison_measurement_measurement_transformer_checked" id=0 value=0
                           autocomplete="off"
                           @if(old('comparison_measurement_measurement_transformer_checked') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_measurement_transformer_checked === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('comparison_measurement_measurement_transformer_checked') d-block @enderror">
                @error('comparison_measurement_measurement_transformer_checked')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="comparison_measurement_pcs_checked">Prozessleitsystem Zähler überprüft</label>
            </div>
            <div class="btn-group btn-group-toggle @error('comparison_measurement_pcs_checked') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('comparison_measurement_pcs_checked') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_pcs_checked === true)) active @endif">
                    <input type="radio" name="comparison_measurement_pcs_checked" id=1 value=1 autocomplete="off"
                           @if(old('comparison_measurement_pcs_checked') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_pcs_checked === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('comparison_measurement_pcs_checked') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_pcs_checked === false)) active @endif">
                    <input type="radio" name="comparison_measurement_pcs_checked" id=0 value=0 autocomplete="off"
                           @if(old('comparison_measurement_pcs_checked') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->comparison_measurement_pcs_checked === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('comparison_measurement_pcs_checked') d-block @enderror">
                @error('comparison_measurement_pcs_checked')
                {{ $message }}
                @enderror
            </div>
        </div>

        <ul class="nav nav-tabs nav-fill mb-2" id="comparison-measurements" role="tablist">
            @foreach( $comparison_measurement_q_percentages as $q_percentage )
                <li class="nav-item">
                    <a class="nav-link @if($loop->first) active @endif @error('measurements.'.$q_percentage.'.*') text-danger @enderror" id="q{{ $q_percentage }}-tab" data-toggle="tab"
                       href="#q{{ $q_percentage }}" role="tab" aria-controls="q{{ $q_percentage }}"
                       aria-selected="true">
                        @error('measurements.'.$q_percentage.'.*')
                            <svg class="icon-bs icon-baseline text-danger mr-1">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
                            </svg>
                        @enderror
                        Q<sub>{{ $q_percentage === 100 ? 'geasmt' : $q_percentage.'%' }}</sub>
                    </a>
                </li>
            @endforeach

        </ul>

        <div class="tab-content" id="comparison-measurements-content">
            @foreach( $comparison_measurement_q_percentages as $q_percentage )
                <div class="tab-pane fade show @if($loop->first) active @endif" id="q{{ $q_percentage }}"
                     role="tabpanel" aria-labelledby="q{{ $q_percentage }}-tab">
                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][q_value]">Q<sub>{{ $q_percentage === 100 ? 'geasmt' : $q_percentage.'%' }}</sub></label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.q_value') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][q_value]"
                                   name="measurements[{{ $q_percentage }}][q_value]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.q_value', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">l/s</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.q_value')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][started_at]">Uhrzeit Start</label>
                        <input type="time" class="form-control @error('measurements.'.$q_percentage.'.started_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][started_at]" name="measurements[{{ $q_percentage }}][started_at]" value="{{ old('measurements.'.$q_percentage.'.started_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->started_at_for_input_field) }}" />
                        <div class="invalid-feedback">
                            @error('measurements.'.$q_percentage.'.started_at')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][ended_at]">Uhrzeit Ende</label>
                        <input type="time" class="form-control @error('measurements.'.$q_percentage.'.ended_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][ended_at]" name="measurements[{{ $q_percentage }}][ended_at]" value="{{ old('measurements.'.$q_percentage.'.ended_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->ended_at_for_input_field) }}" />
                        <div class="invalid-feedback">
                            @error('measurements.'.$q_percentage.'.ended_at')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]">Messwertumformer Zählerstand Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_start) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]">Messwertumformer Zählerstand Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_end) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]">Messwertumformer Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_sum) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_start]">Prozessleitsystem Zählerstand Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_start]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_start) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_end]">Prozessleitsystem Zählerstand Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_end]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_end) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_sum]">Prozessleitsystem Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_sum]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_sum) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_start]">Vergleichsmessung Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_start]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_start) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_end]">Vergleichsmessung Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_end]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_end) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_sum]">Vergleichsmessung Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_sum]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_sum) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">m³</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][measurement_difference]">Abweichung Vergleichsmessung/stationär</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_difference') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_difference]"
                                   name="measurements[{{ $q_percentage }}][measurement_difference]" placeholder="3"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_difference', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_difference) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_difference')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="measurements[{{ $q_percentage }}][q_value_average_mobile]">Errechneter Mittelwert mobil</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.q_value_average_mobile') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][q_value_average_mobile]"
                                   name="measurements[{{ $q_percentage }}][q_value_average_mobile]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.q_value_average_mobile', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value_average_mobile) }}"/>
                            <div class="input-group-append">
                                <span class="input-group-text">l/s</span>
                            </div>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.q_value_average_mobile')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
            </svg>
            Zusammenfassende Beurteilung
        </p>
        <p class="text-muted">
            Die abschließende Beurteilung der Überprüfung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="measurement_difference_up_to_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_difference_up_to_30_q_max') is-invalid @enderror"
                       id="measurement_difference_up_to_30_q_max"
                       name="measurement_difference_up_to_30_q_max" placeholder="3"
                       value="{{ old('measurement_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_up_to_30_q_max) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_difference_up_to_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="measurement_difference_above_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_difference_above_30_q_max') is-invalid @enderror"
                       id="measurement_difference_above_30_q_max"
                       name="measurement_difference_above_30_q_max" placeholder="2"
                       value="{{ old('measurement_difference_above_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_above_30_q_max) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('measurement_difference_above_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="reading_difference_up_to_30_q_max">Abweichung Zähölerstände stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('reading_difference_up_to_30_q_max') is-invalid @enderror"
                       id="reading_difference_up_to_30_q_max"
                       name="reading_difference_up_to_30_q_max" placeholder="5"
                       value="{{ old('reading_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->reading_difference_up_to_30_q_max) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('reading_difference_up_to_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="reading_difference_above_30_q_max">Abweichung Zählerstände stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('reading_difference_above_30_q_max') is-invalid @enderror"
                       id="reading_difference_above_30_q_max"
                       name="reading_difference_above_30_q_max" placeholder="3"
                       value="{{ old('reading_difference_above_30_q_max', optional($flowMeterInspectionReport)->reading_difference_above_30_q_max) }}"
                       required/>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('reading_difference_above_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="equipment_in_tolerance_range"><strong>Das Messsystem arbeitet innerhalb des Toleranzbereichs des ÖWAV Regelblatts 38</strong></label>
            </div>
            <div class="btn-group btn-group-toggle @error('equipment_in_tolerance_range') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('equipment_in_tolerance_range') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === true)) active @endif">
                    <input type="radio" name="equipment_in_tolerance_range" id=1 value=1 autocomplete="off"
                           @if(old('equipment_in_tolerance_range') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('equipment_in_tolerance_range') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === false)) active @endif">
                    <input type="radio" name="equipment_in_tolerance_range" id=0 value=0 autocomplete="off"
                           @if(old('equipment_in_tolerance_range') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === false)) checked @endif>
                    nein
                </label>
            </div>
            <div class="invalid-feedback @error('equipment_in_tolerance_range') d-block @enderror">
                @error('equipment_in_tolerance_range')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="equipment_deficiencies">Festgestelle Mängel beim Messsystem</label>
            <input type="text"
                   class="form-control @error('equipment_deficiencies') is-invalid @enderror"
                   id="equipment_deficiencies"
                   name="equipment_deficiencies" placeholder="Mängel"
                   value="{{ old('equipment_deficiencies', optional($flowMeterInspectionReport)->equipment_deficiencies) }}"/>
            <div class="invalid-feedback">
                @error('equipment_deficiencies')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="further_inspection_required">Zweitprüfung/Vollprüfung nach Korrektur erforderlich</label>
            </div>
            <div class="btn-group btn-group-toggle @error('further_inspection_required') is-invalid @enderror"
                 data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('further_inspection_required') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === true)) active @endif">
                    <input type="radio" name="further_inspection_required" id=1 value=1 autocomplete="off"
                           @if(old('further_inspection_required') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === true)) checked @endif>
                    ja
                </label>
                <label class="btn btn-outline-secondary @if(old('further_inspection_required') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === false)) active @endif">
                    <input type="radio" name="further_inspection_required" id=0 value=0 autocomplete="off"
                           @if(old('further_inspection_required') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === false)) checked @endif>
                    nein
                </label>
                <label class="btn btn-outline-secondary @if((old('_token') && old('further_inspection_required') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === null)) active @endif">
                    <input type="radio" name="further_inspection_required" id="" value="" autocomplete="off"
                           @if((old('_token') && old('further_inspection_required') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === null)) checked @endif>
                    keine Angabe
                </label>
            </div>
            <div class="invalid-feedback @error('further_inspection_required') d-block @enderror">
                @error('further_inspection_required')
                    {{ $message }}
                @enderror
            </div>
        </div>
        
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Kommentare zur Prüfung
        </p>
        <p class="text-muted">
            Sonstige Anmerkungen und Kommentare zur Prüfung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Kommentare zur Prüfung
            </label>
            <markdown-editor name="comment" placeholder="Kommentare zur Prüfung"  value="{{ old('comment', optional($flowMeterInspectionReport)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe zu Markdown
            </a>
            <div class="invalid-feedback @error('comment') d-block @enderror">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Anhänge
        </p>
        <p class="text-muted">
            Dem Prüfbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente. Für den Ausdruck
            Anhang kann nur eine PDF Datei ausgewählt werden.
        </p>
        <p class="text-muted">
            Der Dateiname von neu hinzugefügten Anhängen kann geändert werden, indem der Text markiert und ein neuer
            Name eingegeben wird.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="appendix_description">Beschreibung des Anhang Inhaltes</label>
            <input type="text"
                   class="form-control @error('appendix_description') is-invalid @enderror"
                   id="appendix_description"
                   name="appendix_description" placeholder="Anhang Inhalt"
                   value="{{ old('appendix_description', optional($flowMeterInspectionReport)->appendix_description) }}"/>
            <div class="invalid-feedback">
                @error('appendix_description')
                {{ $message }}
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label>PDF Anhang für den
                Ausdruck{{ $flowMeterInspectionReport ? ' (Ohne Auswahl wird der aktuelle Anhang beibehalten)' : '' }}</label>
            <div class="custom-file">
                <input type="file" accept="application/pdf" class="custom-file-input" id="appendix"
                       name="appendix">
                <label class="custom-file-label" for="appendix">PDF Anhang auswählen</label>
            </div>
            <div class="invalid-feedback @error('appendix') d-block @enderror">
                @error('appendix')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>
                Andere Anhänge
            </label>
            <attachments-selector accept="image/*, application/pdf"
                                  :current_attachments="{{ $currentAttachments ?? '[]' }}"
                                  v-cloak></attachments-selector>
            <div class="invalid-feedback @error('remove_attachments') d-block @enderror @error('remove_attachments.*') d-block @enderror @error('new_attachments') d-block @enderror @error('new_attachments.*') d-block @enderror">
                @error('remove_attachments')
                {{ $message }}
                @enderror
                @error('remove_attachments.*')
                {{ $message }}
                @enderror
                @error('new_attachments')
                {{ $message }}
                @enderror
                @error('new_attachments.*')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
            </svg>
            Anfrage zur Unterschrift senden
        </p>
        <p class="text-muted">
            Bei Aktivierung der Schaltfläche kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email
            versendet werden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="alert alert-info" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox"
                       class="custom-control-input @error('send_signature_request') is-invalid @enderror"
                       name="send_signature_request" id="send_signature_request" value="true">
                <label class="custom-control-label" for="send_signature_request">Anfrage zur Unterschrift nach dem
                    Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
