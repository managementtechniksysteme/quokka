@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

@unless(Auth::user()->signature())
    <div class="q-banner">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
        <span>Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a> hinzu.</span>
    </div>
@endunless

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Prüfberichtes. Bei der Bearbeitung eines bereits unterschriebenen Prüfberichtes wird die vorhandene Unterschrift entfernt.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($flowMeterInspectionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div>
            <label>Status</label>
            @if(optional($flowMeterInspectionReport)->status === 'signed')
                <div class="q-banner">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <span>Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.</span>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="status" id="status-new" value="new" autocomplete="off" @if(optional($flowMeterInspectionReport)->status == 'new' || !$flowMeterInspectionReport) checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--sky" for="status-new">neu</label>
                <input type="radio" class="btn-check" name="status" id="status-signed" value="signed" autocomplete="off" @if(optional($flowMeterInspectionReport)->status == 'signed') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--amber" for="status-signed">unterschrieben</label>
                <input type="radio" class="btn-check" name="status" id="status-finished" value="finished" autocomplete="off" @if(optional($flowMeterInspectionReport)->status == 'finished') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--green" for="status-finished">erledigt</label>
            </div>
        </div>

        <div>
            <label for="inspected_on">Datum</label>
            <input type="date" class="form-control @error('inspected_on') is-invalid @enderror" id="inspected_on" name="inspected_on" value="{{ old('inspected_on', optional(optional($flowMeterInspectionReport)->inspected_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('inspected_on')
                    {{ $message }}
                @else
                    Gib bitte das Datum der Überprüfung ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="equipment_identifier">Zu überprüfende Anlage</label>
            <input type="text" class="form-control @error('equipment_identifier') is-invalid @enderror" id="equipment_identifier" name="equipment_identifier" placeholder="Kläranlage Musterort" value="{{ old('equipment_identifier', optional($flowMeterInspectionReport)->equipment_identifier) }}" required />
            <div class="invalid-feedback">
                @error('equipment_identifier')
                    {{ $message }}
                @else
                    Gib bitte die Anlage ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="area_1">Bereich 1</label>
            <input type="text" class="form-control @error('area_1') is-invalid @enderror" id="area_1" name="area_1" placeholder="Musterbereich 1" value="{{ old('area_1', optional($flowMeterInspectionReport)->area_1) }}" />
            <div class="invalid-feedback">
                @error('area_1')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="area_2">Bereich 2</label>
            <input type="text" class="form-control @error('area_2') is-invalid @enderror" id="area_2" name="area_2" placeholder="Musterbereich 2" value="{{ old('area_2', optional($flowMeterInspectionReport)->area_2) }}" />
            <div class="invalid-feedback">
                @error('area_2')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="area_3">Bereich 3</label>
            <input type="text" class="form-control @error('area_3') is-invalid @enderror" id="area_3" name="area_3" placeholder="Musterbereich 3" value="{{ old('area_3', optional($flowMeterInspectionReport)->area_3) }}" />
            <div class="invalid-feedback">
                @error('area_3')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="treatment_plant_size">Ausbaugröße (Bemessungswert) der Kläranlage</label>
            <input type="number" min="0" class="form-control @error('treatment_plant_size') is-invalid @enderror" id="treatment_plant_size" name="treatment_plant_size" placeholder="100000" value="{{ old('treatment_plant_size', optional($flowMeterInspectionReport)->treatment_plant_size) }}" />
            <div class="invalid-feedback">
                @error('treatment_plant_size')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Wetter</label>
            <div class="btn-group @error('weather') is-invalid @enderror">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary q-seg--amber" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary q-seg--sky" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="temperature">Temperatur</label>
            <div class="input-group has-validation">
                <input type="number" step="1" class="form-control @error('temperature') is-invalid @enderror" id="temperature" name="temperature" placeholder="18" value="{{ old('temperature', optional($flowMeterInspectionReport)->temperature) }}" required />
                <span class="input-group-text">°C</span>
                <div class="invalid-feedback">
                    @error('temperature')
                        {{ $message }}
                    @else
                        Gib bitte die Temperatur an.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}"></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Messstelle
        <div class="q-form-section__desc">Die Details zur Messstelle sowie aktuell vorherrschenden Gegebenheiten.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="measuring_point">Bezeichnung der Messstelle</label>
            <input type="text" class="form-control @error('measuring_point') is-invalid @enderror" id="measuring_point" name="measuring_point" placeholder="Ablaufmessung" value="{{ old('measuring_point', optional($flowMeterInspectionReport)->measuring_point) }}" required />
            <div class="invalid-feedback">
                @error('measuring_point')
                    {{ $message }}
                @else
                    Gib bitte die Bezeichung ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="installation_point">Einbauort</label>
            <input type="text" class="form-control @error('installation_point') is-invalid @enderror" id="installation_point" name="installation_point" placeholder="Kläranlage" value="{{ old('installation_point', optional($flowMeterInspectionReport)->installation_point) }}" required />
            <div class="invalid-feedback">
                @error('installation_point')
                    {{ $message }}
                @else
                    Gib bitte den Einbauort ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="medium">Medium</label>
            <input type="text" class="form-control @error('medium') is-invalid @enderror" id="medium" name="medium" placeholder="Abwasser" value="{{ old('medium', optional($flowMeterInspectionReport)->medium) }}" required />
            <div class="invalid-feedback">
                @error('medium')
                    {{ $message }}
                @else
                    Gib bitte das Medium ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="commissioning_year">Jahr der Inbetriebnahme</label>
            <input type="number" min="0" class="form-control @error('commissioning_year') is-invalid @enderror" id="commissioning_year" name="commissioning_year" placeholder="1991" value="{{ old('commissioning_year', optional($flowMeterInspectionReport)->commissioning_year) }}" />
            <div class="invalid-feedback">
                @error('commissioning_year')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="responsible_person">Zuständiger Mitarbeiter für die Messeinrichtung</label>
            <input type="text" class="form-control @error('responsible_person') is-invalid @enderror" id="responsible_person" name="responsible_person" placeholder="Max Mustermann" value="{{ old('responsible_person', optional($flowMeterInspectionReport)->responsible_person) }}" required />
            <div class="invalid-feedback">
                @error('responsible_person')
                    {{ $message }}
                @else
                    Gib bitte den Namen ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="responsible_person_instructed_on">Zuständiger Mitarbeiter eingeschult am</label>
            <input type="date" class="form-control @error('responsible_person_instructed_on') is-invalid @enderror" id="responsible_person_instructed_on" name="responsible_person_instructed_on" value="{{ old('responsible_person_instructed_on', optional(optional($flowMeterInspectionReport)->responsible_person_instructed_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('responsible_person_instructed_on')
                    {{ $message }}
                @else
                    Gib bitte das Einschuldatum ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="instructor">Zuständiger Mitarbeiter eingeschult durch</label>
            <input type="text" class="form-control @error('instructor') is-invalid @enderror" id="instructor" name="instructor" placeholder="Max Mustermann" value="{{ old('instructor', optional($flowMeterInspectionReport)->instructor) }}" required />
            <div class="invalid-feedback">
                @error('instructor')
                    {{ $message }}
                @else
                    Gib bitte den Namen ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="information_providing_people">Auskunft gebende Mitarbeiter</label>
            <input type="text" class="form-control @error('information_providing_people') is-invalid @enderror" id="information_providing_people" name="information_providing_people" placeholder="Max Mustermann" value="{{ old('information_providing_people', optional($flowMeterInspectionReport)->information_providing_people) }}" />
            <div class="invalid-feedback">
                @error('information_providing_people')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="last_inspected_on">Datum der letzten Vollprüfung</label>
            <input type="date" class="form-control @error('last_inspected_on') is-invalid @enderror" id="last_inspected_on" name="last_inspected_on" value="{{ old('last_inspected_on', optional(optional($flowMeterInspectionReport)->last_inspected_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('last_inspected_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="last_inspected_by">Prüfstelle der letzten Vollprüfung</label>
            <input type="text" class="form-control @error('last_inspected_by') is-invalid @enderror" id="last_inspected_by" name="last_inspected_by" placeholder="Musterfirma" value="{{ old('last_inspected_by', optional($flowMeterInspectionReport)->last_inspected_by) }}" />
            <div class="invalid-feedback">
                @error('last_inspected_by')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="last_inspection_project">Projekt/Nummer der letzten Vollprüfung</label>
            <input type="text" class="form-control @error('last_inspection_project') is-invalid @enderror" id="last_inspection_project" name="last_inspection_project" placeholder="Musterprojekt" value="{{ old('last_inspection_project', optional($flowMeterInspectionReport)->last_inspection_project) }}" />
            <div class="invalid-feedback">
                @error('last_inspection_project')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Stationäre Messeinrichtung
        <div class="q-form-section__desc">Die Eigenschaften der stationären Messeinrichtung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-form__row q-form__row--2">
            <div>
                <label for="profile_outer_diameter">Außendurchmesser des Profils</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" class="form-control @error('profile_outer_diameter') is-invalid @enderror" id="profile_outer_diameter" name="profile_outer_diameter" placeholder="600" value="{{ old('profile_outer_diameter', optional($flowMeterInspectionReport)->profile_outer_diameter) }}" required />
                    <span class="input-group-text">mm</span>
                    <div class="invalid-feedback">
                        @error('profile_outer_diameter')
                            {{ $message }}
                        @else
                            Gib bitte den Durchmesser ein.
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <label for="profile_wall_thickness">Wandstärke des Profils</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" class="form-control @error('profile_wall_thickness') is-invalid @enderror" id="profile_wall_thickness" name="profile_wall_thickness" placeholder="3" value="{{ old('profile_wall_thickness', optional($flowMeterInspectionReport)->profile_wall_thickness) }}" required />
                    <span class="input-group-text">mm</span>
                    <div class="invalid-feedback">
                        @error('profile_wall_thickness')
                            {{ $message }}
                        @else
                            Gib bitte die Wandstärke ein.
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label for="profile_material">Material des Profils</label>
            <input type="text" class="form-control @error('profile_material') is-invalid @enderror" id="profile_material" name="profile_material" placeholder="Material" value="{{ old('profile_material', optional($flowMeterInspectionReport)->profile_material) }}" required />
            <div class="invalid-feedback">
                @error('profile_material')
                    {{ $message }}
                @else
                    Gib bitte das Material ein.
                @enderror
            </div>
        </div>

        <div>
            <label>Querschnittsverengung</label>
            <div class="btn-group @error('without_cross_section_reduction') is-invalid @enderror">
                <input type="radio" class="btn-check" name="without_cross_section_reduction" id="without_cross_section_reduction-1" value="1" autocomplete="off" @if(old('without_cross_section_reduction') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="without_cross_section_reduction-1">ohne Verengung</label>
                <input type="radio" class="btn-check" name="without_cross_section_reduction" id="without_cross_section_reduction-0" value="0" autocomplete="off" @if(old('without_cross_section_reduction') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->without_cross_section_reduction === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="without_cross_section_reduction-0">mit Verengung</label>
            </div>
            <div class="invalid-feedback @error('without_cross_section_reduction') d-block @enderror">
                @error('without_cross_section_reduction')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Füllstand</label>
            <div class="btn-group @error('fully_filled') is-invalid @enderror">
                <input type="radio" class="btn-check" name="fully_filled" id="fully_filled-1" value="1" autocomplete="off" @if(old('fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="fully_filled-1">vollgefüllt</label>
                <input type="radio" class="btn-check" name="fully_filled" id="fully_filled-0" value="0" autocomplete="off" @if(old('fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->fully_filled === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="fully_filled-0">teilgefüllt</label>
            </div>
            <div class="invalid-feedback @error('fully_filled') d-block @enderror">
                @error('fully_filled')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Messart</label>
            <div class="btn-group @error('speed_measurement_type') is-invalid @enderror">
                <input type="radio" class="btn-check" name="speed_measurement_type" id="speed_measurement_type-doppler_ultrasonic" value="doppler_ultrasonic" autocomplete="off" @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'doppler_ultrasonic') checked @endif>
                <label class="btn btn-outline-secondary" for="speed_measurement_type-doppler_ultrasonic">Doppler Ultraschall Messung</label>
                <input type="radio" class="btn-check" name="speed_measurement_type" id="speed_measurement_type-ultrasonic_signal_transmit_time" value="ultrasonic_signal_transmit_time" autocomplete="off" @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_signal_transmit_time') checked @endif>
                <label class="btn btn-outline-secondary" for="speed_measurement_type-ultrasonic_signal_transmit_time">Ultraschall Laufzeitmessung, Signallaufzeit</label>
                <input type="radio" class="btn-check" name="speed_measurement_type" id="speed_measurement_type-ultrasonic_cross_correlation" value="ultrasonic_cross_correlation" autocomplete="off" @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'ultrasonic_cross_correlation') checked @endif>
                <label class="btn btn-outline-secondary" for="speed_measurement_type-ultrasonic_cross_correlation">Ultraschall Kreuzkorrelation</label>
                <input type="radio" class="btn-check" name="speed_measurement_type" id="speed_measurement_type-radar" value="radar" autocomplete="off" @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'radar') checked @endif>
                <label class="btn btn-outline-secondary" for="speed_measurement_type-radar">Radar</label>
                <input type="radio" class="btn-check" name="speed_measurement_type" id="speed_measurement_type-other" value="other" autocomplete="off" @if(old('speed_measurement_type', optional($flowMeterInspectionReport)->speed_measurement_type) == 'other') checked @endif>
                <label class="btn btn-outline-secondary" for="speed_measurement_type-other">Andere</label>
            </div>
            <div class="invalid-feedback @error('speed_measurement_type') d-block @enderror">
                @error('speed_measurement_type')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="speed_measurement_type_other">Andere Messart</label>
            <input type="text" class="form-control @error('speed_measurement_type_other') is-invalid @enderror" id="speed_measurement_type_other" name="speed_measurement_type_other" placeholder="Messart" value="{{ old('speed_measurement_type_other', optional($flowMeterInspectionReport)->speed_measurement_type_other) }}" />
            <div class="invalid-feedback">
                @error('speed_measurement_type_other')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="water_level_measurement_type">Art der Wasserstandsmessung (bei teilgefüllten Messstrecken)</label>
            <input type="text" class="form-control @error('water_level_measurement_type') is-invalid @enderror" id="water_level_measurement_type" name="water_level_measurement_type" placeholder="Messungsart" value="{{ old('water_level_measurement_type', optional($flowMeterInspectionReport)->water_level_measurement_type) }}" />
            <div class="invalid-feedback">
                @error('water_level_measurement_type')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Funktionskontrolle Bauwerk
        <div class="q-form-section__desc">Dokumentation der Funktionskontrolle des Messsystems.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="equipment_changes">Veränderungen am Messsystem</label>
            <input type="text" class="form-control @error('equipment_changes') is-invalid @enderror" id="equipment_changes" name="equipment_changes" placeholder="Einbau Laufzeitmessung" value="{{ old('equipment_changes', optional($flowMeterInspectionReport)->equipment_changes) }}" />
            <div class="invalid-feedback">
                @error('equipment_changes')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Dokumentation vorhanden</label>
            <div class="btn-group @error('documentation_existent') is-invalid @enderror">
                <input type="radio" class="btn-check" name="documentation_existent" id="documentation_existent-1" value="1" autocomplete="off" @if(old('documentation_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="documentation_existent-1">ja</label>
                <input type="radio" class="btn-check" name="documentation_existent" id="documentation_existent-0" value="0" autocomplete="off" @if(old('documentation_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_existent === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="documentation_existent-0">nein</label>
            </div>
            <div class="invalid-feedback @error('documentation_existent') d-block @enderror">
                @error('documentation_existent')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Prüfbuch vorhanden</label>
            <div class="btn-group @error('inspection_book_existent') is-invalid @enderror">
                <input type="radio" class="btn-check" name="inspection_book_existent" id="inspection_book_existent-1" value="1" autocomplete="off" @if(old('inspection_book_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="inspection_book_existent-1">ja</label>
                <input type="radio" class="btn-check" name="inspection_book_existent" id="inspection_book_existent-0" value="0" autocomplete="off" @if(old('inspection_book_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_book_existent === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="inspection_book_existent-0">nein</label>
            </div>
            <div class="invalid-feedback @error('inspection_book_existent') d-block @enderror">
                @error('inspection_book_existent')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Wartungsvorschrift vorhanden</label>
            <div class="btn-group @error('inspection_requirements_existent') is-invalid @enderror">
                <input type="radio" class="btn-check" name="inspection_requirements_existent" id="inspection_requirements_existent-1" value="1" autocomplete="off" @if(old('inspection_requirements_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="inspection_requirements_existent-1">ja</label>
                <input type="radio" class="btn-check" name="inspection_requirements_existent" id="inspection_requirements_existent-0" value="0" autocomplete="off" @if(old('inspection_requirements_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->inspection_requirements_existent === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="inspection_requirements_existent-0">nein</label>
            </div>
            <div class="invalid-feedback @error('inspection_requirements_existent') d-block @enderror">
                @error('inspection_requirements_existent')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Einbaubedingungen stimmen mit der Dokumentation überein</label>
            <div class="btn-group @error('documentation_current') is-invalid @enderror">
                <input type="radio" class="btn-check" name="documentation_current" id="documentation_current-1" value="1" autocomplete="off" @if(old('documentation_current') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="documentation_current-1">ja</label>
                <input type="radio" class="btn-check" name="documentation_current" id="documentation_current-0" value="0" autocomplete="off" @if(old('documentation_current') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->documentation_current === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="documentation_current-0">nein</label>
            </div>
            <div class="invalid-feedback @error('documentation_current') d-block @enderror">
                @error('documentation_current')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="equipment_changes_to_documentation">Vorgenommene Änderungen gegenüber der Dokumentation</label>
            <input type="text" class="form-control @error('equipment_changes_to_documentation') is-invalid @enderror" id="equipment_changes_to_documentation" name="equipment_changes_to_documentation" placeholder="Änderungen" value="{{ old('equipment_changes_to_documentation', optional($flowMeterInspectionReport)->equipment_changes_to_documentation) }}" />
            <div class="invalid-feedback">
                @error('equipment_changes_to_documentation')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measuring_pipe_type">Messrohr Fabrikat</label>
            <input type="text" class="form-control @error('measuring_pipe_type') is-invalid @enderror" id="measuring_pipe_type" name="measuring_pipe_type" placeholder="Fabrikat" value="{{ old('measuring_pipe_type', optional($flowMeterInspectionReport)->measuring_pipe_type) }}" />
            <div class="invalid-feedback">
                @error('measuring_pipe_type')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measuring_pipe_minimum_speed">Messrohr Mindestgeschwindigkeit</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measuring_pipe_minimum_speed') is-invalid @enderror" id="measuring_pipe_minimum_speed" name="measuring_pipe_minimum_speed" placeholder="0,03" value="{{ old('measuring_pipe_minimum_speed', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed) }}" />
                <span class="input-group-text">m/s</span>
                <div class="invalid-feedback">
                    @error('measuring_pipe_minimum_speed')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Messrohr Mindesgeschwindigkeit Einheit</label>
            <div class="btn-group @error('measuring_pipe_minimum_speed_unit') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measuring_pipe_minimum_speed_unit" id="measuring_pipe_minimum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('measuring_pipe_minimum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed_unit) == 'm_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_minimum_speed_unit-m_s">m/s</label>
                <input type="radio" class="btn-check" name="measuring_pipe_minimum_speed_unit" id="measuring_pipe_minimum_speed_unit-null" value="" autocomplete="off" @if((old('_token') && old('measuring_pipe_minimum_speed_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measuring_pipe_minimum_speed_unit === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_minimum_speed_unit-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_minimum_speed_unit') d-block @enderror">
                @error('measuring_pipe_minimum_speed_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measuring_pipe_maximum_flow_rate">Messrohr Messbereich 100% Durchflussrate</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measuring_pipe_maximum_flow_rate') is-invalid @enderror" id="measuring_pipe_maximum_flow_rate" name="measuring_pipe_maximum_flow_rate" placeholder="282" value="{{ old('measuring_pipe_maximum_flow_rate', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate) }}" />
                <span class="input-group-text">l/s oder m³/h</span>
                <div class="invalid-feedback">
                    @error('measuring_pipe_maximum_flow_rate')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Messrohr Messbereich 100% Durchflussrate Einheit</label>
            <div class="btn-group @error('measuring_pipe_maximum_flow_rate_unit') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_flow_rate_unit" id="measuring_pipe_maximum_flow_rate_unit-l_s" value="l_s" autocomplete="off" @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'l_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_flow_rate_unit-l_s">l/s</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_flow_rate_unit" id="measuring_pipe_maximum_flow_rate_unit-m3_h" value="m3_h" autocomplete="off" @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'm3_h') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_flow_rate_unit-m3_h">m³/h</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_flow_rate_unit" id="measuring_pipe_maximum_flow_rate_unit-null" value="" autocomplete="off" @if((old('_token') && old('measuring_pipe_maximum_flow_rate_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measuring_pipe_maximum_flow_rate_unit === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_flow_rate_unit-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_maximum_flow_rate_unit') d-block @enderror">
                @error('measuring_pipe_maximum_flow_rate_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measuring_pipe_maximum_speed">Messrohr Messbereich 100% Geschwindigkeit</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measuring_pipe_maximum_speed') is-invalid @enderror" id="measuring_pipe_maximum_speed" name="measuring_pipe_maximum_speed" placeholder="10" value="{{ old('measuring_pipe_maximum_speed', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed) }}" />
                <span class="input-group-text">m/s</span>
                <div class="invalid-feedback">
                    @error('measuring_pipe_maximum_speed')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Messrohr Messbereich 100% Geschwindigkeit Einheit</label>
            <div class="btn-group @error('measuring_pipe_maximum_speed_unit') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_speed_unit" id="measuring_pipe_maximum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('measuring_pipe_maximum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed_unit) == 'm_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_speed_unit-m_s">m/s</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_speed_unit" id="measuring_pipe_maximum_speed_unit-null" value="" autocomplete="off" @if((old('_token') && old('measuring_pipe_maximum_speed_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measuring_pipe_maximum_speed_unit === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_speed_unit-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measuring_pipe_maximum_speed_unit') d-block @enderror">
                @error('measuring_pipe_maximum_speed_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="mucus_suppression">Schleimmengenunterdrückung</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('mucus_suppression') is-invalid @enderror" id="mucus_suppression" name="mucus_suppression" placeholder="1" value="{{ old('mucus_suppression', optional($flowMeterInspectionReport)->mucus_suppression) }}" />
                <span class="input-group-text">% oder l/s</span>
                <div class="invalid-feedback">
                    @error('mucus_suppression')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Messrohr Messbereich 100% Durchflussrate Einheit</label>
            <div class="btn-group @error('mucus_suppression_unit') is-invalid @enderror">
                <input type="radio" class="btn-check" name="mucus_suppression_unit" id="mucus_suppression_unit-percent" value="percent" autocomplete="off" @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'percent') checked @endif>
                <label class="btn btn-outline-secondary" for="mucus_suppression_unit-percent">%</label>
                <input type="radio" class="btn-check" name="mucus_suppression_unit" id="mucus_suppression_unit-l_s" value="l_s" autocomplete="off" @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'l_s') checked @endif>
                <label class="btn btn-outline-secondary" for="mucus_suppression_unit-l_s">l/s</label>
                <input type="radio" class="btn-check" name="mucus_suppression_unit" id="mucus_suppression_unit-null" value="" autocomplete="off" @if((old('_token') && old('mucus_suppression_unit') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->mucus_suppression_unit === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="mucus_suppression_unit-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('mucus_suppression_unit') d-block @enderror">
                @error('mucus_suppression_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="q_min">Beobachteter minimaler Durchfluss Q<sub>min</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('q_min') is-invalid @enderror" id="q_min" name="q_min" placeholder="33" value="{{ old('q_min', optional($flowMeterInspectionReport)->q_min) }}" required />
                <span class="input-group-text">l/s</span>
                <div class="invalid-feedback">
                    @error('q_min')
                        {{ $message }}
                    @else
                        Gib bitte den Durchfluss ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="q_max">Beobachteter maximaler Durchfluss Q<sub>max</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('q_max') is-invalid @enderror" id="q_max" name="q_max" placeholder="225" value="{{ old('q_max', optional($flowMeterInspectionReport)->q_max) }}" required />
                <span class="input-group-text">l/s</span>
                <div class="invalid-feedback">
                    @error('q_max')
                        {{ $message }}
                    @else
                        Gib bitte den Durchfluss ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Feststellung des Durchflussbereiches</label>
            <div class="btn-group @error('flow_range_type') is-invalid @enderror">
                <input type="radio" class="btn-check" name="flow_range_type" id="flow_range_type-guess" value="guess" autocomplete="off" @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'guess') checked @endif>
                <label class="btn btn-outline-secondary" for="flow_range_type-guess">Abschätzung</label>
                <input type="radio" class="btn-check" name="flow_range_type" id="flow_range_type-statistical_analysis" value="statistical_analysis" autocomplete="off" @if(old('flow_range_type', optional($flowMeterInspectionReport)->flow_range_type) == 'statistical_analysis') checked @endif>
                <label class="btn btn-outline-secondary" for="flow_range_type-statistical_analysis">statistische Auswertung</label>
            </div>
            <div class="invalid-feedback @error('flow_range_type') d-block @enderror">
                @error('flow_range_type')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Messsystem &ndash; Messwertaufnehmer Wasserstand
        <div class="q-form-section__desc">Die Eigenschaften des Wasserstand Messwertaufnehmers bei teilgefüllten Strecken.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="water_level_meter">System</label>
            <input type="text" class="form-control @error('water_level_meter') is-invalid @enderror" id="water_level_meter" name="water_level_meter" placeholder="System" value="{{ old('water_level_meter', optional($flowMeterInspectionReport)->water_level_meter) }}" />
            <div class="invalid-feedback">
                @error('water_level_meter')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="water_level_meter_make">Fabrikat</label>
            <input type="text" class="form-control @error('water_level_meter_make') is-invalid @enderror" id="water_level_meter_make" name="water_level_meter_make" placeholder="Fabrikat" value="{{ old('water_level_meter_make', optional($flowMeterInspectionReport)->water_level_meter_make) }}" />
            <div class="invalid-feedback">
                @error('water_level_meter_make')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="water_level_meter_type">Type</label>
            <input type="text" class="form-control @error('water_level_meter_type') is-invalid @enderror" id="water_level_meter_type" name="water_level_meter_type" placeholder="Type" value="{{ old('water_level_meter_type', optional($flowMeterInspectionReport)->water_level_meter_type) }}" />
            <div class="invalid-feedback">
                @error('water_level_meter_type')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="water_level_meter_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('water_level_meter_identifier') is-invalid @enderror" id="water_level_meter_identifier" name="water_level_meter_identifier" placeholder="Seriennummer" value="{{ old('water_level_meter_identifier', optional($flowMeterInspectionReport)->water_level_meter_identifier) }}" />
            <div class="invalid-feedback">
                @error('water_level_meter_identifier')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Messsystem &ndash; Messwertaufnehmer Fließgeschwindigkeit
        <div class="q-form-section__desc">Die Eigenschaften des Fließgeschwindigkeit Messwertaufnehmers.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="flow_rate_meter">System</label>
            <input type="text" class="form-control @error('flow_rate_meter') is-invalid @enderror" id="flow_rate_meter" name="flow_rate_meter" placeholder="System" value="{{ old('flow_rate_meter', optional($flowMeterInspectionReport)->flow_rate_meter) }}" required />
            <div class="invalid-feedback">
                @error('flow_rate_meter')
                    {{ $message }}
                @else
                    Gib bitte das System ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="flow_rate_meter_make">Fabrikat</label>
            <input type="text" class="form-control @error('flow_rate_meter_make') is-invalid @enderror" id="flow_rate_meter_make" name="flow_rate_meter_make" placeholder="Fabrikat" value="{{ old('flow_rate_meter_make', optional($flowMeterInspectionReport)->flow_rate_meter_make) }}" required />
            <div class="invalid-feedback">
                @error('flow_rate_meter_make')
                    {{ $message }}
                @else
                    Gib bitte das Fabrikat ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="flow_rate_meter_type">Type</label>
            <input type="text" class="form-control @error('flow_rate_meter_type') is-invalid @enderror" id="flow_rate_meter_type" name="flow_rate_meter_type" placeholder="Type" value="{{ old('flow_rate_meter_type', optional($flowMeterInspectionReport)->flow_rate_meter_type) }}" required />
            <div class="invalid-feedback">
                @error('flow_rate_meter_type')
                    {{ $message }}
                @else
                    Gib bitte die Type ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="flow_rate_meter_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('flow_rate_meter_identifier') is-invalid @enderror" id="flow_rate_meter_identifier" name="flow_rate_meter_identifier" placeholder="Seriennummer" value="{{ old('flow_rate_meter_identifier', optional($flowMeterInspectionReport)->flow_rate_meter_identifier) }}" required />
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Messsystem &ndash; Messwertumformer
        <div class="q-form-section__desc">Die Eigenschaften des Messwertumformers.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label>Ort der Umformung</label>
            <div class="btn-group @error('measurement_transformer_point') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_transformer_point" id="measurement_transformer_point-local" value="local" autocomplete="off" @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'local') checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_transformer_point-local">vor Ort</label>
                <input type="radio" class="btn-check" name="measurement_transformer_point" id="measurement_transformer_point-control_stand" value="control_stand" autocomplete="off" @if(old('measurement_transformer_point', optional($flowMeterInspectionReport)->measurement_transformer_point) == 'control_stand') checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_transformer_point-control_stand">Warte</label>
            </div>
            <div class="invalid-feedback @error('measurement_transformer_point') d-block @enderror">
                @error('measurement_transformer_point')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_transformer_make">Fabrikat</label>
            <input type="text" class="form-control @error('measurement_transformer_make') is-invalid @enderror" id="measurement_transformer_make" name="measurement_transformer_make" placeholder="Fabrikat" value="{{ old('measurement_transformer_make', optional($flowMeterInspectionReport)->measurement_transformer_make) }}" required />
            <div class="invalid-feedback">
                @error('measurement_transformer_make')
                    {{ $message }}
                @else
                    Gib bitte das Fabrikat ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_transformer_type">Type</label>
            <input type="text" class="form-control @error('measurement_transformer_type') is-invalid @enderror" id="measurement_transformer_type" name="measurement_transformer_type" placeholder="Type" value="{{ old('measurement_transformer_type', optional($flowMeterInspectionReport)->measurement_transformer_type) }}" required />
            <div class="invalid-feedback">
                @error('measurement_transformer_type')
                    {{ $message }}
                @else
                    Gib bitte die Type ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_transformer_identifier">Seriennummer</label>
            <input type="text" class="form-control @error('measurement_transformer_identifier') is-invalid @enderror" id="measurement_transformer_identifier" name="measurement_transformer_identifier" placeholder="Seriennummer" value="{{ old('measurement_transformer_identifier', optional($flowMeterInspectionReport)->measurement_transformer_identifier) }}" required />
            <div class="invalid-feedback">
                @error('measurement_transformer_identifier')
                    {{ $message }}
                @else
                    Gib bitte die Seriennumer ein.
                @enderror
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="measurement_transformer_minimum_level">minimaler Signalausgang</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('measurement_transformer_minimum_level') is-invalid @enderror" id="measurement_transformer_minimum_level" name="measurement_transformer_minimum_level" placeholder="4" value="{{ old('measurement_transformer_minimum_level', optional($flowMeterInspectionReport)->measurement_transformer_minimum_level) }}" />
                    <span class="input-group-text">mA oder V</span>
                    <div class="invalid-feedback">
                        @error('measurement_transformer_minimum_level')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <label for="measurement_transformer_maximum_level">maximaler Signalausgang</label>
                <div class="input-group has-validation">
                    <input type="number" min="0" step="any" class="form-control @error('measurement_transformer_maximum_level') is-invalid @enderror" id="measurement_transformer_maximum_level" name="measurement_transformer_maximum_level" placeholder="20" value="{{ old('measurement_transformer_maximum_level', optional($flowMeterInspectionReport)->measurement_transformer_maximum_level) }}" />
                    <span class="input-group-text">mA oder V</span>
                    <div class="invalid-feedback">
                        @error('measurement_transformer_maximum_level')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label>Messeinheit</label>
            <div class="btn-group @error('measurement_transformer_level_unit') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_transformer_level_unit" id="measurement_transformer_level_unit-mA" value="mA" autocomplete="off" @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'mA') checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_transformer_level_unit-mA">mA</label>
                <input type="radio" class="btn-check" name="measurement_transformer_level_unit" id="measurement_transformer_level_unit-V" value="V" autocomplete="off" @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'V') checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_transformer_level_unit-V">V</label>
                <input type="radio" class="btn-check" name="measurement_transformer_level_unit" id="measurement_transformer_level_unit-interface" value="interface" autocomplete="off" @if(old('measurement_transformer_level_unit', optional($flowMeterInspectionReport)->measurement_transformer_level_unit) == 'interface') checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_transformer_level_unit-interface">Schnittstelle</label>
            </div>
            <div class="invalid-feedback @error('measurement_transformer_level_unit') d-block @enderror">
                @error('measurement_transformer_level_unit')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_transformer_range_100_percent">Programmierter Messbereich 100%</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measurement_transformer_range_100_percent') is-invalid @enderror" id="measurement_transformer_range_100_percent" name="measurement_transformer_range_100_percent" placeholder="250" value="{{ old('measurement_transformer_range_100_percent', optional($flowMeterInspectionReport)->measurement_transformer_range_100_percent) }}" required />
                <span class="input-group-text">l/s</span>
                <div class="invalid-feedback">
                    @error('measurement_transformer_range_100_percent')
                        {{ $message }}
                    @else
                        Gib bitte den Messbereich ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="measurement_transformer_impulses">Impulsausgang</label>
            <div class="input-group has-validation">
                <input type="number" min="0" class="form-control @error('measurement_transformer_impulses') is-invalid @enderror" id="measurement_transformer_impulses" name="measurement_transformer_impulses" placeholder="1" value="{{ old('measurement_transformer_impulses', optional($flowMeterInspectionReport)->measurement_transformer_impulses) }}" required />
                <span class="input-group-text">Impulse/m³</span>
                <div class="invalid-feedback">
                    @error('measurement_transformer_impulses')
                        {{ $message }}
                    @else
                        Gib bitte die Impulse ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="measurement_transformer_data_logging">Aufzeichnung der Durchflusssummen und Momentanwerte für die Betriebsprotokolle</label>
            <input type="text" class="form-control @error('measurement_transformer_data_logging') is-invalid @enderror" id="measurement_transformer_data_logging" name="measurement_transformer_data_logging" placeholder="Aufzeichnung" value="{{ old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) }}" required />
            <div class="invalid-feedback">
                @error('measurement_transformer_data_logging')
                    {{ $message }}
                @else
                    Gib bitte die Art der Aufzeichnung ein.
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bestandsaufnahme Oberwasserseite
        <div class="q-form-section__desc">Die Bestandsaufnahme der Oberwasserseite.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="headwater_pipe_diameter">Rohrdurchmesser innen</label>
            <div class="input-group has-validation">
                <input type="number" min="0" class="form-control @error('headwater_pipe_diameter') is-invalid @enderror" id="headwater_pipe_diameter" name="headwater_pipe_diameter" placeholder="600" value="{{ old('headwater_pipe_diameter', optional($flowMeterInspectionReport)->headwater_pipe_diameter) }}" required />
                <span class="input-group-text">mm</span>
                <div class="invalid-feedback">
                    @error('headwater_pipe_diameter')
                        {{ $message }}
                    @else
                        Gib bitte den Durchmesser ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="headwater_calming_section">Länge der einlaufseitigen Beruhigungsstrecke</label>
            <input type="text" class="form-control @error('headwater_calming_section') is-invalid @enderror" id="headwater_calming_section" name="headwater_calming_section" placeholder="5 x Rohrdurchmesser" value="{{ old('headwater_calming_section', optional($flowMeterInspectionReport)->headwater_calming_section) }}" required />
            <div class="invalid-feedback">
                @error('headwater_calming_section')
                    {{ $message }}
                @else
                    Gib bitte die Länge ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="headwater_calming_section_assessment">Beurteilung der Beruhigungsstrecke</label>
            <input type="text" class="form-control @error('headwater_calming_section_assessment') is-invalid @enderror" id="headwater_calming_section_assessment" name="headwater_calming_section_assessment" placeholder="ausreichend gleichmäßig" value="{{ old('headwater_calming_section_assessment', optional($flowMeterInspectionReport)->headwater_calming_section_assessment) }}" required />
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Bestandsaufnahme Messstrecke
        <div class="q-form-section__desc">Die Bestandsaufnahme der Messstrecke.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="measurement_section_slope">Gefälle der Messstrecke</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measurement_section_slope') is-invalid @enderror" id="measurement_section_slope" name="measurement_section_slope" placeholder="9" value="{{ old('measurement_section_slope', optional($flowMeterInspectionReport)->measurement_section_slope) }}" />
                <span class="input-group-text">‰</span>
                <div class="invalid-feedback">
                    @error('measurement_section_slope')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="measurement_section_slope_assessment_type">Vermessung durchgeführt mittels</label>
            <input type="text" class="form-control @error('measurement_section_slope_assessment_type') is-invalid @enderror" id="measurement_section_slope_assessment_type" name="measurement_section_slope_assessment_type" placeholder="Vermessungsart" value="{{ old('measurement_section_slope_assessment_type', optional($flowMeterInspectionReport)->measurement_section_slope_assessment_type) }}" />
            <div class="invalid-feedback">
                @error('measurement_section_slope_assessment_type')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Einbaubedingungen laut Hersteller erfüllt</label>
            <div class="btn-group @error('measurement_section_installation_according_to_manufacturer') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_installation_according_to_manufacturer" id="measurement_section_installation_according_to_manufacturer-1" value="1" autocomplete="off" @if(old('measurement_section_installation_according_to_manufacturer') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_installation_according_to_manufacturer-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_installation_according_to_manufacturer" id="measurement_section_installation_according_to_manufacturer-0" value="0" autocomplete="off" @if(old('measurement_section_installation_according_to_manufacturer') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_installation_according_to_manufacturer-0">nein</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_installation_according_to_manufacturer') d-block @enderror">
                @error('measurement_section_installation_according_to_manufacturer')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_section_minimum_speed_undercut_point">Unterschreitung der Mindestgeschwindigkeit unterhalb von</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measurement_section_minimum_speed_undercut_point') is-invalid @enderror" id="measurement_section_minimum_speed_undercut_point" name="measurement_section_minimum_speed_undercut_point" placeholder="1" value="{{ old('measurement_section_minimum_speed_undercut_point', optional($flowMeterInspectionReport)->measurement_section_minimum_speed_undercut_point) }}" />
                <span class="input-group-text">l/s</span>
                <div class="invalid-feedback">
                    @error('measurement_section_minimum_speed_undercut_point')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Beurteilung der Messstrecke
        <div class="q-form-section__desc">Die Beurteilung der Messstrecke im eingebauten Zustand.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="measurement_section_pipe_diameter">Querschnitt des Messrohrs innen</label>
            <div class="input-group has-validation">
                <input type="number" min="0" class="form-control @error('measurement_section_pipe_diameter') is-invalid @enderror" id="measurement_section_pipe_diameter" name="measurement_section_pipe_diameter" placeholder="600" value="{{ old('measurement_section_pipe_diameter', optional($flowMeterInspectionReport)->measurement_section_pipe_diameter) }}" required />
                <span class="input-group-text">mm</span>
                <div class="invalid-feedback">
                    @error('measurement_section_pipe_diameter')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Zugänglichkeit gegeben</label>
            <div class="btn-group @error('measurement_section_access_possible') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_access_possible" id="measurement_section_access_possible-1" value="1" autocomplete="off" @if(old('measurement_section_access_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_access_possible-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_access_possible" id="measurement_section_access_possible-0" value="0" autocomplete="off" @if(old('measurement_section_access_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_access_possible-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_access_possible" id="measurement_section_access_possible-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_access_possible') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_access_possible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_access_possible-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_access_possible') d-block @enderror">
                @error('measurement_section_access_possible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Geforderte Fließtiefe bei der Vergleichsmessung</label>
            <div class="btn-group @error('measurement_section_pipe_required_fill_level_existent') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_required_fill_level_existent" id="measurement_section_pipe_required_fill_level_existent-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_required_fill_level_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_required_fill_level_existent-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_required_fill_level_existent" id="measurement_section_pipe_required_fill_level_existent-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_required_fill_level_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_required_fill_level_existent-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_required_fill_level_existent" id="measurement_section_pipe_required_fill_level_existent-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_required_fill_level_existent') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_required_fill_level_existent-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_required_fill_level_existent') d-block @enderror">
                @error('measurement_section_pipe_required_fill_level_existent')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Optische Kontrolle im Messrohr möglich</label>
            <div class="btn-group @error('measurement_section_pipe_visible_inspection_inside_possible') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_visible_inspection_inside_possible" id="measurement_section_pipe_visible_inspection_inside_possible-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_visible_inspection_inside_possible-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_visible_inspection_inside_possible" id="measurement_section_pipe_visible_inspection_inside_possible-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_visible_inspection_inside_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_visible_inspection_inside_possible-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_visible_inspection_inside_possible" id="measurement_section_pipe_visible_inspection_inside_possible-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_visible_inspection_inside_possible') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_visible_inspection_inside_possible-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_visible_inspection_inside_possible') d-block @enderror">
                @error('measurement_section_pipe_visible_inspection_inside_possible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_section_pipe_visible_inspection_inside">Alternative Möglichkeit zur inneren optischen Kontrolle</label>
            <input type="text" class="form-control @error('measurement_section_pipe_visible_inspection_inside') is-invalid @enderror" id="measurement_section_pipe_visible_inspection_inside" name="measurement_section_pipe_visible_inspection_inside" placeholder="Inspektionsöffnung" value="{{ old('measurement_section_pipe_visible_inspection_inside', optional($flowMeterInspectionReport)->measurement_section_pipe_visible_inspection_inside) }}" />
            <div class="invalid-feedback">
                @error('measurement_section_pipe_visible_inspection_inside')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Ablagerungen, Verschmutzungen im Messrohr, am Messwertaufnehmer</label>
            <div class="btn-group @error('measurement_section_pipe_contaminated') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_contaminated" id="measurement_section_pipe_contaminated-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_contaminated') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_contaminated-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_contaminated" id="measurement_section_pipe_contaminated-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_contaminated') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_contaminated-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_contaminated" id="measurement_section_pipe_contaminated-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_contaminated') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_contaminated === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_contaminated-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_contaminated') d-block @enderror">
                @error('measurement_section_pipe_contaminated')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Reinigung des Messrohres möglich</label>
            <div class="btn-group @error('measurement_section_pipe_cleaning_possible') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_cleaning_possible" id="measurement_section_pipe_cleaning_possible-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_cleaning_possible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_cleaning_possible-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_cleaning_possible" id="measurement_section_pipe_cleaning_possible-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_cleaning_possible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_cleaning_possible-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_cleaning_possible" id="measurement_section_pipe_cleaning_possible-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_cleaning_possible') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_cleaning_possible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_cleaning_possible-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_cleaning_possible') d-block @enderror">
                @error('measurement_section_pipe_cleaning_possible')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="measurement_section_pipe_last_cleaned_on">Letzte Reinigung des Messrohrs</label>
            <input type="date" class="form-control @error('measurement_section_pipe_last_cleaned_on') is-invalid @enderror" id="measurement_section_pipe_last_cleaned_on" name="measurement_section_pipe_last_cleaned_on" value="{{ old('measurement_section_pipe_last_cleaned_on', optional(optional($flowMeterInspectionReport)->measurement_section_pipe_last_cleaned_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('measurement_section_pipe_last_cleaned_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Messwertaufnehmer ist gereinigt</label>
            <div class="btn-group @error('measurement_section_sensor_cleaned') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_sensor_cleaned" id="measurement_section_sensor_cleaned-1" value="1" autocomplete="off" @if(old('measurement_section_sensor_cleaned') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_sensor_cleaned-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_sensor_cleaned" id="measurement_section_sensor_cleaned-0" value="0" autocomplete="off" @if(old('measurement_section_sensor_cleaned') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_sensor_cleaned-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_sensor_cleaned" id="measurement_section_sensor_cleaned-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_sensor_cleaned') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_cleaned === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_sensor_cleaned-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_sensor_cleaned') d-block @enderror">
                @error('measurement_section_sensor_cleaned')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Messwertaufnehmer ist mechanisch beschädigt</label>
            <div class="btn-group @error('measurement_section_sensor_damaged') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_sensor_damaged" id="measurement_section_sensor_damaged-1" value="1" autocomplete="off" @if(old('measurement_section_sensor_damaged') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_sensor_damaged-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_sensor_damaged" id="measurement_section_sensor_damaged-0" value="0" autocomplete="off" @if(old('measurement_section_sensor_damaged') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_sensor_damaged-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_sensor_damaged" id="measurement_section_sensor_damaged-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_sensor_damaged') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_sensor_damaged === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_sensor_damaged-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_sensor_damaged') d-block @enderror">
                @error('measurement_section_sensor_damaged')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Innere Oberfläche in Ordnung</label>
            <div class="btn-group @error('measurement_section_pipe_inside_surface_ok') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_inside_surface_ok" id="measurement_section_pipe_inside_surface_ok-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_inside_surface_ok') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_inside_surface_ok-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_inside_surface_ok" id="measurement_section_pipe_inside_surface_ok-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_inside_surface_ok') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_inside_surface_ok-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_inside_surface_ok" id="measurement_section_pipe_inside_surface_ok-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_inside_surface_ok') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_inside_surface_ok-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_inside_surface_ok') d-block @enderror">
                @error('measurement_section_pipe_inside_surface_ok')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Erdung des Messrohres ist gegeben</label>
            <div class="btn-group @error('measurement_section_pipe_grounding_existent') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_grounding_existent" id="measurement_section_pipe_grounding_existent-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_grounding_existent') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_grounding_existent-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_grounding_existent" id="measurement_section_pipe_grounding_existent-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_grounding_existent') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_grounding_existent-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_grounding_existent" id="measurement_section_pipe_grounding_existent-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_grounding_existent') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_grounding_existent === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_grounding_existent-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_grounding_existent') d-block @enderror">
                @error('measurement_section_pipe_grounding_existent')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Lufteinschlüsse erkennbar</label>
            <div class="btn-group @error('measurement_section_pipe_air_pockets_visible') is-invalid @enderror">
                <input type="radio" class="btn-check" name="measurement_section_pipe_air_pockets_visible" id="measurement_section_pipe_air_pockets_visible-1" value="1" autocomplete="off" @if(old('measurement_section_pipe_air_pockets_visible') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="measurement_section_pipe_air_pockets_visible-1">ja</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_air_pockets_visible" id="measurement_section_pipe_air_pockets_visible-0" value="0" autocomplete="off" @if(old('measurement_section_pipe_air_pockets_visible') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="measurement_section_pipe_air_pockets_visible-0">nein</label>
                <input type="radio" class="btn-check" name="measurement_section_pipe_air_pockets_visible" id="measurement_section_pipe_air_pockets_visible-null" value="" autocomplete="off" @if((old('_token') && old('measurement_section_pipe_air_pockets_visible') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="measurement_section_pipe_air_pockets_visible-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('measurement_section_pipe_air_pockets_visible') d-block @enderror">
                @error('measurement_section_pipe_air_pockets_visible')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bestandsaufnahme Unterwasserseite
        <div class="q-form-section__desc">Die Bestandsaufnahme der Unterwasserseite.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="tailwater_pipe_diameter">Rohrdurchmesser innen</label>
            <div class="input-group has-validation">
                <input type="number" min="0" class="form-control @error('tailwater_pipe_diameter') is-invalid @enderror" id="tailwater_pipe_diameter" name="tailwater_pipe_diameter" placeholder="600" value="{{ old('tailwater_pipe_diameter', optional($flowMeterInspectionReport)->tailwater_pipe_diameter) }}" required />
                <span class="input-group-text">mm</span>
                <div class="invalid-feedback">
                    @error('tailwater_pipe_diameter')
                        {{ $message }}
                    @else
                        Gib bitte den Durchmesser ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Füllstand</label>
            <div class="btn-group @error('tailwater_pipe_fully_filled') is-invalid @enderror">
                <input type="radio" class="btn-check" name="tailwater_pipe_fully_filled" id="tailwater_pipe_fully_filled-1" value="1" autocomplete="off" @if(old('tailwater_pipe_fully_filled') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="tailwater_pipe_fully_filled-1">vollgefüllt</label>
                <input type="radio" class="btn-check" name="tailwater_pipe_fully_filled" id="tailwater_pipe_fully_filled-0" value="0" autocomplete="off" @if(old('tailwater_pipe_fully_filled') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_pipe_fully_filled === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="tailwater_pipe_fully_filled-0">teilgefüllt</label>
            </div>
            <div class="invalid-feedback @error('tailwater_pipe_fully_filled') d-block @enderror">
                @error('tailwater_pipe_fully_filled')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="tailwater_runout_section_slope">Gefälle der Auslaufstrecke</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('tailwater_runout_section_slope') is-invalid @enderror" id="tailwater_runout_section_slope" name="tailwater_runout_section_slope" placeholder="9" value="{{ old('tailwater_runout_section_slope', optional($flowMeterInspectionReport)->tailwater_runout_section_slope) }}" />
                <span class="input-group-text">‰</span>
                <div class="invalid-feedback">
                    @error('tailwater_runout_section_slope')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="tailwater_runout_section_slope_assessment_type">Vermessung durchgeführt mittels</label>
            <input type="text" class="form-control @error('tailwater_runout_section_slope_assessment_type') is-invalid @enderror" id="tailwater_runout_section_slope_assessment_type" name="tailwater_runout_section_slope_assessment_type" placeholder="Vermessungsart" value="{{ old('tailwater_runout_section_slope_assessment_type', optional($flowMeterInspectionReport)->tailwater_runout_section_slope_assessment_type) }}" />
            <div class="invalid-feedback">
                @error('tailwater_runout_section_slope_assessment_type')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Beurteilung der Auslaufstrecke
        <div class="q-form-section__desc">Die Beurteilung der Auslaufstrecke.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="tailwater_runout_section_assessment">Beurteilung der Auslaufstrecke</label>
            <input type="text" class="form-control @error('tailwater_runout_section_assessment') is-invalid @enderror" id="tailwater_runout_section_assessment" name="tailwater_runout_section_assessment" placeholder="ausreichend gleichmäßig" value="{{ old('tailwater_runout_section_assessment', optional($flowMeterInspectionReport)->tailwater_runout_section_assessment) }}" required />
            <div class="invalid-feedback">
                @error('tailwater_runout_section_assessment')
                    {{ $message }}
                @else
                    Gib bitte die Beurteilung ein.
                @enderror
            </div>
        </div>

        <div>
            <label>Leerlaufen des Messrohrs ist möglich</label>
            <div class="btn-group @error('tailwater_measurement_pipe_can_run_dry') is-invalid @enderror">
                <input type="radio" class="btn-check" name="tailwater_measurement_pipe_can_run_dry" id="tailwater_measurement_pipe_can_run_dry-1" value="1" autocomplete="off" @if(old('tailwater_measurement_pipe_can_run_dry') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="tailwater_measurement_pipe_can_run_dry-1">ja</label>
                <input type="radio" class="btn-check" name="tailwater_measurement_pipe_can_run_dry" id="tailwater_measurement_pipe_can_run_dry-0" value="0" autocomplete="off" @if(old('tailwater_measurement_pipe_can_run_dry') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="tailwater_measurement_pipe_can_run_dry-0">nein</label>
            </div>
            <div class="invalid-feedback @error('tailwater_measurement_pipe_can_run_dry') d-block @enderror">
                @error('tailwater_measurement_pipe_can_run_dry')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Strömungsverhältnisse werden vom Vorfluter oder anderen Einleitungen beeinflusst</label>
            <div class="btn-group @error('tailwater_flow_conditions_influenced') is-invalid @enderror">
                <input type="radio" class="btn-check" name="tailwater_flow_conditions_influenced" id="tailwater_flow_conditions_influenced-1" value="1" autocomplete="off" @if(old('tailwater_flow_conditions_influenced') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="tailwater_flow_conditions_influenced-1">ja</label>
                <input type="radio" class="btn-check" name="tailwater_flow_conditions_influenced" id="tailwater_flow_conditions_influenced-0" value="0" autocomplete="off" @if(old('tailwater_flow_conditions_influenced') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->tailwater_flow_conditions_influenced === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="tailwater_flow_conditions_influenced-0">nein</label>
            </div>
            <div class="invalid-feedback @error('tailwater_flow_conditions_influenced') d-block @enderror">
                @error('tailwater_flow_conditions_influenced')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="tailwater_flow_conditions_influencer">Beeinflussung durch</label>
            <input type="text" class="form-control @error('tailwater_flow_conditions_influencer') is-invalid @enderror" id="tailwater_flow_conditions_influencer" name="tailwater_flow_conditions_influencer" placeholder="Beinflussung" value="{{ old('tailwater_flow_conditions_influencer', optional($flowMeterInspectionReport)->tailwater_flow_conditions_influencer) }}" />
            <div class="invalid-feedback">
                @error('tailwater_flow_conditions_influencer')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Funktionskontrolle Messsystem
        <div class="q-form-section__desc">Details zur Kontrolle der Messwert Anzeige bei Null-Durchfluss.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="zero_flow_rate_testing_conditions">Wie wird der Null-Durchfluss geprüft?</label>
            <input type="text" class="form-control @error('zero_flow_rate_testing_conditions') is-invalid @enderror" id="zero_flow_rate_testing_conditions" name="zero_flow_rate_testing_conditions" placeholder="Schieber zu" value="{{ old('zero_flow_rate_testing_conditions', optional($flowMeterInspectionReport)->zero_flow_rate_testing_conditions) }}" />
            <div class="invalid-feedback">
                @error('zero_flow_rate_testing_conditions')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="zero_flow_rate_reading_points">Ablesepunkte des Null-Durchflusses</label>
            <input type="text" class="form-control @error('zero_flow_rate_reading_points') is-invalid @enderror" id="zero_flow_rate_reading_points" name="zero_flow_rate_reading_points" placeholder="Vorort, Prozessleitsystem" value="{{ old('zero_flow_rate_reading_points', optional($flowMeterInspectionReport)->zero_flow_rate_reading_points) }}" />
            <div class="invalid-feedback">
                @error('zero_flow_rate_reading_points')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="zero_flow_rate_displayed_flow">Angezeigter Durchfluss bei Null-Durchfluss</label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('zero_flow_rate_displayed_flow') is-invalid @enderror" id="zero_flow_rate_displayed_flow" name="zero_flow_rate_displayed_flow" placeholder="0" value="{{ old('zero_flow_rate_displayed_flow', optional($flowMeterInspectionReport)->zero_flow_rate_displayed_flow) }}" />
                <span class="input-group-text">l/s</span>
                <div class="invalid-feedback">
                    @error('zero_flow_rate_displayed_flow')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Vergleichsmessung
        <div class="q-form-section__desc">Details zum Messverfahren der durchgeführten Vergleichsmessung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <ul class="nav nav-tabs nav-fill mb-2" id="comparison-measurement" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="mobile-tab" data-bs-toggle="tab" href="#mobile" role="tab"
                   aria-controls="mobile" aria-selected="true"
                   onclick="document.getElementById('comparison_measurements_process').value = 'mobile_measurement_equipment'">mobile
                    Messeinrichtung</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="volumetric-tab" data-bs-toggle="tab" href="#volumetric" role="tab"
                   aria-controls="volumetric" aria-selected="false"
                   onclick="document.getElementById('comparison_measurements_process').value = 'volumetric'">volumetrisch</a>
            </li>
        </ul>

        <input type="hidden" id="comparison_measurements_process" name="comparison_measurements_process"
               value="mobile_measurement_equipment">

        <div class="tab-content" id="comparison-measurement-content">
            <div class="tab-pane fade show active" id="mobile" role="tabpanel" aria-labelledby="mobile-tab">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <label>Geschwindikteitsmessung</label>
                        <div class="btn-group @error('comparison_measurement_mobile_type') is-invalid @enderror">
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_type" id="comparison_measurement_mobile_type-doppler_ultrasonic" value="doppler_ultrasonic" autocomplete="off" @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'doppler_ultrasonic') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_type-doppler_ultrasonic">Doppler Ultraschall Messung</label>
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_type" id="comparison_measurement_mobile_type-ultrasonic_signal_transmit_time" value="ultrasonic_signal_transmit_time" autocomplete="off" @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_signal_transmit_time') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_type-ultrasonic_signal_transmit_time">Ultraschall Laufzeitmessung, Signallaufzeit</label>
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_type" id="comparison_measurement_mobile_type-ultrasonic_cross_correlation" value="ultrasonic_cross_correlation" autocomplete="off" @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'ultrasonic_cross_correlation') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_type-ultrasonic_cross_correlation">Ultraschall Kreuzkorrelation</label>
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_type" id="comparison_measurement_mobile_type-radar" value="radar" autocomplete="off" @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'radar') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_type-radar">Radar</label>
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_type" id="comparison_measurement_mobile_type-other" value="other" autocomplete="off" @if(old('comparison_measurement_mobile_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type) == 'other') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_type-other">Andere</label>
                        </div>
                        <div class="invalid-feedback @error('comparison_measurement_mobile_type') d-block @enderror">
                            @error('comparison_measurement_mobile_type')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_type_other">Andere Geschwindigkeitsmessung</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_type_other') is-invalid @enderror" id="comparison_measurement_mobile_type_other" name="comparison_measurement_mobile_type_other" placeholder="Messungsart" value="{{ old('comparison_measurement_mobile_type_other', optional($flowMeterInspectionReport)->comparison_measurement_mobile_type_other) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_type_other')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_installation_point">Einbauort der Vergleichsmessung</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_installation_point') is-invalid @enderror" id="comparison_measurement_mobile_installation_point" name="comparison_measurement_mobile_installation_point" placeholder="Einbauort" value="{{ old('comparison_measurement_mobile_installation_point', optional($flowMeterInspectionReport)->comparison_measurement_mobile_installation_point) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_installation_point')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_make">Prüfmittel Fabrikat</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_equipment_make') is-invalid @enderror" id="comparison_measurement_mobile_equipment_make" name="comparison_measurement_mobile_equipment_make" placeholder="Fabrikat" value="{{ old('comparison_measurement_mobile_equipment_make', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_make) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_make')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_type">Prüfmittel Type</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_equipment_type') is-invalid @enderror" id="comparison_measurement_mobile_equipment_type" name="comparison_measurement_mobile_equipment_type" placeholder="Type" value="{{ old('comparison_measurement_mobile_equipment_type', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_type) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_type')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_identifier">Prüfmittel Seriennummer</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_equipment_identifier') is-invalid @enderror" id="comparison_measurement_mobile_equipment_identifier" name="comparison_measurement_mobile_equipment_identifier" placeholder="Seriennummer" value="{{ old('comparison_measurement_mobile_equipment_identifier', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_identifier) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_identifier')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_q_min">Minimaler Durchfluss Q<sub>min</sub> (Herstellerangabe)</label>
                        <div class="input-group has-validation">
                            <input type="number" min="0" step="any" class="form-control @error('comparison_measurement_mobile_equipment_q_min') is-invalid @enderror" id="comparison_measurement_mobile_equipment_q_min" name="comparison_measurement_mobile_equipment_q_min" placeholder="0" value="{{ old('comparison_measurement_mobile_equipment_q_min', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_q_min) }}" />
                            <span class="input-group-text">l/s</span>
                            <div class="invalid-feedback">
                                @error('comparison_measurement_mobile_equipment_q_min')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_flow_rate">Messbereich 100% Durchflussrate (Herstellerangabe)</label>
                        <div class="input-group has-validation">
                            <input type="number" min="0" step="any" class="form-control @error('comparison_measurement_mobile_equipment_maximum_flow_rate') is-invalid @enderror" id="comparison_measurement_mobile_equipment_maximum_flow_rate" name="comparison_measurement_mobile_equipment_maximum_flow_rate" placeholder="282" value="{{ old('comparison_measurement_mobile_equipment_maximum_flow_rate', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate) }}" />
                            <span class="input-group-text">l/s oder m³/h</span>
                            <div class="invalid-feedback">
                                @error('comparison_measurement_mobile_equipment_maximum_flow_rate')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label>Messbereich 100% Durchflussrate Einheit</label>
                        <div class="btn-group @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit') is-invalid @enderror">
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_equipment_maximum_flow_rate_unit" id="comparison_measurement_mobile_equipment_maximum_flow_rate_unit-l_s" value="l_s" autocomplete="off" @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'l_s') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_equipment_maximum_flow_rate_unit-l_s">l/s</label>
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_equipment_maximum_flow_rate_unit" id="comparison_measurement_mobile_equipment_maximum_flow_rate_unit-m3_h" value="m3_h" autocomplete="off" @if(old('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate_unit) == 'm3_h') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_equipment_maximum_flow_rate_unit-m3_h">m³/h</label>
                        </div>
                        <div class="invalid-feedback @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit') d-block @enderror">
                            @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_speed">Messbereich 100% Geschwindigkeit (Herstellerangabe)</label>
                        <div class="input-group has-validation">
                            <input type="number" min="0" step="any" class="form-control @error('comparison_measurement_mobile_equipment_maximum_speed') is-invalid @enderror" id="comparison_measurement_mobile_equipment_maximum_speed" name="comparison_measurement_mobile_equipment_maximum_speed" placeholder="10" value="{{ old('comparison_measurement_mobile_equipment_maximum_speed', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed) }}" />
                            <span class="input-group-text">m/s</span>
                            <div class="invalid-feedback">
                                @error('comparison_measurement_mobile_equipment_maximum_speed')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label>Messbereich 100% Geschwindigkeit Einheit</label>
                        <div class="btn-group @error('comparison_measurement_mobile_equipment_maximum_speed_unit') is-invalid @enderror">
                            <input type="radio" class="btn-check" name="comparison_measurement_mobile_equipment_maximum_speed_unit" id="comparison_measurement_mobile_equipment_maximum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('comparison_measurement_mobile_equipment_maximum_speed_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed_unit) == 'm_s') checked @endif>
                            <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_equipment_maximum_speed_unit-m_s">m/s</label>
                        </div>
                        <div class="invalid-feedback @error('comparison_measurement_mobile_equipment_maximum_speed_unit') d-block @enderror">
                            @error('comparison_measurement_mobile_equipment_maximum_speed_unit')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_last_calibrated_on">Letzte Kalibrierung</label>
                        <input type="date" class="form-control @error('comparison_measurement_mobile_equipment_last_calibrated_on') is-invalid @enderror" id="comparison_measurement_mobile_equipment_last_calibrated_on" name="comparison_measurement_mobile_equipment_last_calibrated_on" value="{{ old('comparison_measurement_mobile_equipment_last_calibrated_on', optional(optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_calibrated_on)->format('Y-m-d')) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_last_calibrated_on')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_last_cal_provider">Kalibrierung durchgeführt von</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_equipment_last_cal_provider') is-invalid @enderror" id="comparison_measurement_mobile_equipment_last_cal_provider" name="comparison_measurement_mobile_equipment_last_cal_provider" placeholder="Kalibrierungsstelle" value="{{ old('comparison_measurement_mobile_equipment_last_cal_provider', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_cal_provider) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_last_cal_provider')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_mobile_equipment_last_cal_doc_identifier">Kalibrierung Dokumentation, Geschäftszahl</label>
                        <input type="text" class="form-control @error('comparison_measurement_mobile_equipment_last_cal_doc_identifier') is-invalid @enderror" id="comparison_measurement_mobile_equipment_last_cal_doc_identifier" name="comparison_measurement_mobile_equipment_last_cal_doc_identifier" placeholder="Dokumentation" value="{{ old('comparison_measurement_mobile_equipment_last_cal_doc_identifier', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_last_cal_doc_identifier) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_last_cal_doc_identifier')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="volumetric" role="tabpanel" aria-labelledby="volumetric-tab">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <label for="comparison_measurement_volumetric_basin">Förderbecken</label>
                        <input type="text" class="form-control @error('comparison_measurement_volumetric_basin') is-invalid @enderror" id="comparison_measurement_volumetric_basin" name="comparison_measurement_volumetric_basin" placeholder="Förderbecken" value="{{ old('comparison_measurement_volumetric_basin', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_basin) }}" />
                        <div class="invalid-feedback">
                            @error('comparison_measurement_volumetric_basin')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_volumetric_basin_cross_section_area">Querschnittsfläche des Vergleichsbehälters</label>
                        <div class="input-group has-validation">
                            <input type="number" min="0" step="any" class="form-control @error('comparison_measurement_volumetric_basin_cross_section_area') is-invalid @enderror" id="comparison_measurement_volumetric_basin_cross_section_area" name="comparison_measurement_volumetric_basin_cross_section_area" placeholder="25" value="{{ old('comparison_measurement_volumetric_basin_cross_section_area', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_basin_cross_section_area) }}" />
                            <span class="input-group-text">m²</span>
                            <div class="invalid-feedback">
                                @error('comparison_measurement_volumetric_basin_cross_section_area')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="comparison_measurement_volumetric_height_measurement_equipment">Verwendete Höhenmessung</label>
                        <input type="text" class="form-control @error('comparison_measurement_volumetric_height_measurement_equipment') is-invalid @enderror" id="comparison_measurement_volumetric_height_measurement_equipment" name="comparison_measurement_volumetric_height_measurement_equipment" placeholder="Höhenmessung" value="{{ old('comparison_measurement_volumetric_height_measurement_equipment', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_height_measurement_equipment) }}" />
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
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Dokumentation der Vergleichsmessung
        <div class="q-form-section__desc">Details zur durchgeführten Vergleichsmessung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <ul class="nav nav-tabs nav-fill mb-2" id="comparison-measurements" role="tablist">
            @foreach( $comparison_measurement_q_percentages as $q_percentage )
                <li class="nav-item">
                    <a class="nav-link @if($loop->last) active @endif @error('measurements.'.$q_percentage.'.*') text-danger @enderror" id="q{{ $q_percentage }}-tab" data-bs-toggle="tab"
                       href="#q{{ $q_percentage }}" role="tab" aria-controls="q{{ $q_percentage }}"
                       aria-selected="true">
                        @error('measurements.'.$q_percentage.'.*')
                            <svg class="icon-bs icon-baseline text-danger me-1">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
                            </svg>
                        @enderror
                        Q<sub>{{ $q_percentage === 100 ? 'gesamt' : $q_percentage.'%' }}</sub>
                    </a>
                </li>
            @endforeach

        </ul>

        <div class="tab-content" id="comparison-measurements-content">
            @foreach( $comparison_measurement_q_percentages as $q_percentage )
                <div class="tab-pane fade show @if($loop->last) active @endif" id="q{{ $q_percentage }}"
                     role="tabpanel" aria-labelledby="q{{ $q_percentage }}-tab">
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label for="measurements[{{ $q_percentage }}][q_value]">Q<sub>{{ $q_percentage === 100 ? 'gesamt' : $q_percentage.'%' }}</sub></label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.q_value') is-invalid @enderror" id="measurements[{{ $q_percentage }}][q_value]" name="measurements[{{ $q_percentage }}][q_value]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.q_value', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value) }}" />
                                <span class="input-group-text">l/s</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.q_value')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][started_at]">Datum und Uhrzeit Start</label>
                            <input type="datetime-local" class="form-control @error('measurements.'.$q_percentage.'.started_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][started_at]" name="measurements[{{ $q_percentage }}][started_at]" value="{{ old('measurements.'.$q_percentage.'.started_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->started_at_for_input_field) }}" />
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.started_at')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][ended_at]">Datum und Uhrzeit Ende</label>
                            <input type="datetime-local" class="form-control @error('measurements.'.$q_percentage.'.ended_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][ended_at]" name="measurements[{{ $q_percentage }}][ended_at]" value="{{ old('measurements.'.$q_percentage.'.ended_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->ended_at_for_input_field) }}" />
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.ended_at')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]">Messwertumformer Zählerstand Start</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start') is-invalid @enderror" id="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]" name="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_start) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]">Messwertumformer Zählerstand Ende</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end') is-invalid @enderror" id="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]" name="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_end) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]">Messwertumformer Summe</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum') is-invalid @enderror" id="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]" name="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_sum) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][pcs_reading_start]">Prozessleitsystem Zählerstand Start</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_start') is-invalid @enderror" id="measurements[{{ $q_percentage }}][pcs_reading_start]" name="measurements[{{ $q_percentage }}][pcs_reading_start]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.pcs_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_start) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.pcs_reading_start')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][pcs_reading_end]">Prozessleitsystem Zählerstand Ende</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_end') is-invalid @enderror" id="measurements[{{ $q_percentage }}][pcs_reading_end]" name="measurements[{{ $q_percentage }}][pcs_reading_end]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.pcs_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_end) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.pcs_reading_end')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][pcs_reading_sum]">Prozessleitsystem Summe</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_sum') is-invalid @enderror" id="measurements[{{ $q_percentage }}][pcs_reading_sum]" name="measurements[{{ $q_percentage }}][pcs_reading_sum]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.pcs_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_sum) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.pcs_reading_sum')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][comparison_measurement_start]">Vergleichsmessung Start</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_start') is-invalid @enderror" id="measurements[{{ $q_percentage }}][comparison_measurement_start]" name="measurements[{{ $q_percentage }}][comparison_measurement_start]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_start) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.comparison_measurement_start')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][comparison_measurement_end]">Vergleichsmessung Ende</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_end') is-invalid @enderror" id="measurements[{{ $q_percentage }}][comparison_measurement_end]" name="measurements[{{ $q_percentage }}][comparison_measurement_end]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_end) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.comparison_measurement_end')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][comparison_measurement_sum]">Vergleichsmessung Summe</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_sum') is-invalid @enderror" id="measurements[{{ $q_percentage }}][comparison_measurement_sum]" name="measurements[{{ $q_percentage }}][comparison_measurement_sum]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_sum) }}" />
                                <span class="input-group-text">m³</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.comparison_measurement_sum')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][measurement_difference]">Abweichung Vergleichsmessung/stationär</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.measurement_difference') is-invalid @enderror" id="measurements[{{ $q_percentage }}][measurement_difference]" name="measurements[{{ $q_percentage }}][measurement_difference]" placeholder="3" value="{{ old('measurements.'.$q_percentage.'.measurement_difference', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_difference) }}" />
                                <span class="input-group-text">%</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.measurement_difference')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="measurements[{{ $q_percentage }}][q_value_average_mobile]">Errechneter Mittelwert mobil</label>
                            <div class="input-group has-validation">
                                <input type="number" min="0" step="any" class="form-control @error('measurements.'.$q_percentage.'.q_value_average_mobile') is-invalid @enderror" id="measurements[{{ $q_percentage }}][q_value_average_mobile]" name="measurements[{{ $q_percentage }}][q_value_average_mobile]" placeholder="10" value="{{ old('measurements.'.$q_percentage.'.q_value_average_mobile', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value_average_mobile) }}" />
                                <span class="input-group-text">l/s</span>
                                <div class="invalid-feedback">
                                    @error('measurements.'.$q_percentage.'.q_value_average_mobile')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Zusammenfassende Beurteilung
        <div class="q-form-section__desc">Die abschließende Beurteilung der Überprüfung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="measurement_difference_up_to_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measurement_difference_up_to_30_q_max') is-invalid @enderror" id="measurement_difference_up_to_30_q_max" name="measurement_difference_up_to_30_q_max" placeholder="3" value="{{ old('measurement_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_up_to_30_q_max) }}" required />
                <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('measurement_difference_up_to_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="measurement_difference_above_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('measurement_difference_above_30_q_max') is-invalid @enderror" id="measurement_difference_above_30_q_max" name="measurement_difference_above_30_q_max" placeholder="2" value="{{ old('measurement_difference_above_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_above_30_q_max) }}" required />
                <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('measurement_difference_above_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="reading_difference_up_to_30_q_max">Abweichung Zähölerstände stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('reading_difference_up_to_30_q_max') is-invalid @enderror" id="reading_difference_up_to_30_q_max" name="reading_difference_up_to_30_q_max" placeholder="5" value="{{ old('reading_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->reading_difference_up_to_30_q_max) }}" required />
                <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('reading_difference_up_to_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="reading_difference_above_30_q_max">Abweichung Zählerstände stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group has-validation">
                <input type="number" min="0" step="any" class="form-control @error('reading_difference_above_30_q_max') is-invalid @enderror" id="reading_difference_above_30_q_max" name="reading_difference_above_30_q_max" placeholder="3" value="{{ old('reading_difference_above_30_q_max', optional($flowMeterInspectionReport)->reading_difference_above_30_q_max) }}" required />
                <span class="input-group-text">%</span>
                <div class="invalid-feedback">
                    @error('reading_difference_above_30_q_max')
                        {{ $message }}
                    @else
                        Gib bitte die Abweichung ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label><strong>Das Messsystem arbeitet innerhalb des Toleranzbereichs des ÖWAV Regelblatts 38</strong></label>
            <div class="btn-group @error('equipment_in_tolerance_range') is-invalid @enderror">
                <input type="radio" class="btn-check" name="equipment_in_tolerance_range" id="equipment_in_tolerance_range-1" value="1" autocomplete="off" @if(old('equipment_in_tolerance_range') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="equipment_in_tolerance_range-1">ja</label>
                <input type="radio" class="btn-check" name="equipment_in_tolerance_range" id="equipment_in_tolerance_range-0" value="0" autocomplete="off" @if(old('equipment_in_tolerance_range') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="equipment_in_tolerance_range-0">nein</label>
            </div>
            <div class="invalid-feedback @error('equipment_in_tolerance_range') d-block @enderror">
                @error('equipment_in_tolerance_range')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="equipment_deficiencies">Festgestelle Mängel beim Messsystem</label>
            <input type="text" class="form-control @error('equipment_deficiencies') is-invalid @enderror" id="equipment_deficiencies" name="equipment_deficiencies" placeholder="Mängel" value="{{ old('equipment_deficiencies', optional($flowMeterInspectionReport)->equipment_deficiencies) }}" />
            <div class="invalid-feedback">
                @error('equipment_deficiencies')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Zweitprüfung/Vollprüfung nach Korrektur erforderlich</label>
            <div class="btn-group @error('further_inspection_required') is-invalid @enderror">
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-1" value="1" autocomplete="off" @if(old('further_inspection_required') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === true)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--red" for="further_inspection_required-1">ja</label>
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-0" value="0" autocomplete="off" @if(old('further_inspection_required') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === false)) checked @endif>
                <label class="btn btn-outline-secondary q-seg--green" for="further_inspection_required-0">nein</label>
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-null" value="" autocomplete="off" @if((old('_token') && old('further_inspection_required') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="further_inspection_required-null">keine Angabe</label>
            </div>
            <div class="invalid-feedback @error('further_inspection_required') d-block @enderror">
                @error('further_inspection_required')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Kommentare zur Prüfung
        <div class="q-form-section__desc">Sonstige Anmerkungen und Kommentare zur Prüfung.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Kommentare zur Prüfung" value="{{ old('comment', optional($flowMeterInspectionReport)->comment) }}" v-cloak></markdown-editor>
        <a class="q-link--quiet d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
            <svg class="icon-bs icon-16 me-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg>
            Hilfe zu Markdown
        </a>
        <div class="invalid-feedback @error('comment') d-block @enderror">
            @error('comment')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Anhänge
        <div class="q-form-section__desc">Dem Prüfbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente. Für den Ausdruck Anhang kann nur eine PDF Datei ausgewählt werden. Der Dateiname von neu hinzugefügten Anhängen kann geändert werden, indem der Text markiert und ein neuer Name eingegeben wird.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="appendix_description">Beschreibung des Anhang Inhaltes</label>
            <input type="text" class="form-control @error('appendix_description') is-invalid @enderror" id="appendix_description" name="appendix_description" placeholder="Anhang Inhalt" value="{{ old('appendix_description', optional($flowMeterInspectionReport)->appendix_description) }}" />
            <div class="invalid-feedback">
                @error('appendix_description')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="q-banner">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
            <span>Um eine korrekte Formatierung des Anhanges im PDF Ausdruck sicherzustellen muss der Anhang im <strong>A4 Hochformat</strong> vorliegen.</span>
        </div>

        <div>
            <label>PDF Anhang für den Ausdruck{{ $flowMeterInspectionReport ? ' (Ohne Auswahl wird der aktuelle Anhang beibehalten)' : '' }}</label>
            <input type="file" accept="application/pdf" class="form-control" id="appendix" name="appendix">
            <div class="invalid-feedback @error('appendix') d-block @enderror">
                @error('appendix')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Andere Anhänge</label>
            <attachments-selector accept="image/*, application/pdf" :current_attachments="{{ $currentAttachments ?? '[]' }}" v-cloak></attachments-selector>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anfrage zur Unterschrift senden
        <div class="q-form-section__desc">Bei Aktivierung der Schaltfläche kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-banner q-banner--info">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <span>Die Email Adresse kann im nächsten Schritt angegeben werden.</span>
        </div>
        <div>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
                <label class="form-check-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
