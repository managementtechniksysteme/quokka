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
            <svg class="icon icon-24 me-2">
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
            <svg class="icon-bs icon-16 me-2">
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
        <div class="mb-3">
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee"
                   placeholder="{{ optional($flowMeterInspectionReport)->employee->person->name ?? Auth::user()->person->name }}"
                   disabled/>
        </div>

        <div class="mb-3">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($flowMeterInspectionReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 me-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Prüfbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift
                        entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($flowMeterInspectionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="temperature">Temperatur</label>
            <div class="input-group">
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

        <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use>
            </svg>
            Messstelle
        </p>
        <p class="text-muted">
            Die Details zur Messstelle sowie aktuell vorherrschenden Gegebenheiten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
            <label for="information_providing_people">Auskunft gebende Mitarbeiter</label>
            <input type="text" class="form-control @error('information_providing_people') is-invalid @enderror"
                   id="information_providing_people" name="information_providing_people" placeholder="Max Mustermann"
                   value="{{ old('information_providing_people', optional($flowMeterInspectionReport)->information_providing_people) }}"/>
            <div class="invalid-feedback">
                @error('information_providing_people')
                {{ $message }}
                    @enderror
            </div>
        </div>

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
            <label for="last_inspection_project">Projekt/Nummer der letzten Vollprüfung</label>
            <input type="text" class="form-control @error('last_inspection_project') is-invalid @enderror"
                   id="last_inspection_project" name="last_inspection_project" placeholder="Musterprojekt"
                   value="{{ old('last_inspection_project', optional($flowMeterInspectionReport)->last_inspection_project) }}"/>
            <div class="invalid-feedback">
                @error('last_inspection_project')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
            </svg>
            Stationäre Messeinrichtung
        </p>
        <p class="text-muted">
            Die Eigenschaften der stationären Messeinrichtung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="profile_outer_diameter">Außendurchmesser des Profils</label>
            <div class="input-group">
                <input type="number" min="0" class="form-control @error('profile_outer_diameter') is-invalid @enderror"
                       id="profile_outer_diameter" name="profile_outer_diameter" placeholder="600"
                       value="{{ old('profile_outer_diameter', optional($flowMeterInspectionReport)->profile_outer_diameter) }}"
                       required/>
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

        <div class="mb-3">
            <label for="profile_wall_thickness">Wandstärke des Profils</label>
            <div class="input-group">
                <input type="number" min="0" class="form-control @error('profile_wall_thickness') is-invalid @enderror"
                       id="profile_wall_thickness" name="profile_wall_thickness" placeholder="3"
                       value="{{ old('profile_wall_thickness', optional($flowMeterInspectionReport)->profile_wall_thickness) }}"
                       required/>
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

        <div class="mb-3">
            <label for="profile_material">Material des Profils</label>
            <input type="text" class="form-control @error('profile_material') is-invalid @enderror"
                   id="profile_material" name="profile_material" placeholder="Material"
                   value="{{ old('profile_material', optional($flowMeterInspectionReport)->profile_material) }}"
                   required/>
            <div class="invalid-feedback">
                @error('profile_material')
                {{ $message }}
                @else
                    Gib bitte das Material ein.
                    @enderror
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="without_cross_section_reduction">Querschnittsverengung</label>
            </div>
            <div class="btn-group @error('without_cross_section_reduction') is-invalid @enderror"
                 >
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

        <div class="mb-3">
            <label for="speed_measurement_type_other">Andere Messart</label>
            <input type="text" class="form-control @error('speed_measurement_type_other') is-invalid @enderror"
                   id="speed_measurement_type_other" name="speed_measurement_type_other" placeholder="Messart"
                   value="{{ old('speed_measurement_type_other', optional($flowMeterInspectionReport)->speed_measurement_type_other) }}"/>
            <div class="invalid-feedback">
                @error('speed_measurement_type_other')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#building"></use>
            </svg>
            Funktionskontrolle Bauwerk
        </p>
        <p class="text-muted">
            Dokumentation der Funktionskontrolle des Messsystems.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
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

        <div class="mb-3">
            <div>
                <label for="documentation_existent">Dokumentation vorhanden</label>
            </div>
            <div class="btn-group @error('documentation_existent') is-invalid @enderror"
                 >
                <input type="radio" class="btn-check" name="measuring_pipe_minimum_speed_unit" id="measuring_pipe_minimum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('measuring_pipe_minimum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_minimum_speed_unit) == 'm_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_minimum_speed_unit-m_s">m/s</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_flow_rate_unit" id="measuring_pipe_maximum_flow_rate_unit-l_s" value="l_s" autocomplete="off" @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'l_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_flow_rate_unit-l_s">l/s</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_flow_rate_unit" id="measuring_pipe_maximum_flow_rate_unit-m3_h" value="m3_h" autocomplete="off" @if(old('measuring_pipe_maximum_flow_rate_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_flow_rate_unit) == 'm3_h') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_flow_rate_unit-m3_h">m³/h</label>
                <input type="radio" class="btn-check" name="measuring_pipe_maximum_speed_unit" id="measuring_pipe_maximum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('measuring_pipe_maximum_speed_unit', optional($flowMeterInspectionReport)->measuring_pipe_maximum_speed_unit) == 'm_s') checked @endif>
                <label class="btn btn-outline-secondary" for="measuring_pipe_maximum_speed_unit-m_s">m/s</label>
                <input type="radio" class="btn-check" name="mucus_suppression_unit" id="mucus_suppression_unit-percent" value="percent" autocomplete="off" @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'percent') checked @endif>
                <label class="btn btn-outline-secondary" for="mucus_suppression_unit-percent">%</label>
                <input type="radio" class="btn-check" name="mucus_suppression_unit" id="mucus_suppression_unit-l_s" value="l_s" autocomplete="off" @if(old('mucus_suppression_unit', optional($flowMeterInspectionReport)->mucus_suppression_unit) == 'l_s') checked @endif>
                <label class="btn btn-outline-secondary" for="mucus_suppression_unit-l_s">l/s</label>
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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertaufnehmer Wasserstand
        </p>
        <p class="text-muted">
            Die Eigenschaften des Wasserstand Messwertaufnehmers bei teilgefüllten Strecken.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertaufnehmer Fließgeschwindigkeit
        </p>
        <p class="text-muted">
            Die Eigenschaften des Fließgeschwindigkeit Messwertaufnehmers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
            </svg>
            Messsystem - Messwertumformer
        </p>
        <p class="text-muted">
            Die Eigenschaften des Messwertumformers.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <div>
                <label for="measurement_transformer_point">Ort der Umformung</label>
            </div>
            <div class="btn-group @error('measurement_transformer_point') is-invalid @enderror"
                 >
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
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

        <div class="mb-3">
            <label for="measurement_transformer_minimum_level">minimaler Signalausgang</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_minimum_level') is-invalid @enderror"
                       id="measurement_transformer_minimum_level" name="measurement_transformer_minimum_level"
                       placeholder="4"
                       value="{{ old('measurement_transformer_minimum_level', optional($flowMeterInspectionReport)->measurement_transformer_minimum_level) }}"/>
                    <span class="input-group-text">mA oder V</span>
                <div class="invalid-feedback">
                    @error('measurement_transformer_minimum_level')
                    {{ $message }}
                        @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="measurement_transformer_maximum_level">maximaler Signalausgang</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_maximum_level') is-invalid @enderror"
                       id="measurement_transformer_maximum_level" name="measurement_transformer_maximum_level"
                       placeholder="20"
                       value="{{ old('measurement_transformer_maximum_level', optional($flowMeterInspectionReport)->measurement_transformer_maximum_level) }}"/>
                    <span class="input-group-text">mA oder V</span>
                <div class="invalid-feedback">
                    @error('measurement_transformer_maximum_level')
                    {{ $message }}
                        @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div>
                <label for="measurement_transformer_level_unit">Messeinheit</label>
            </div>
            <div class="btn-group @error('measurement_transformer_level_unit') is-invalid @enderror"
                 >
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

        <div class="mb-3">
            <label for="measurement_transformer_range_100_percent">Programmierter Messbereich 100%</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_transformer_range_100_percent') is-invalid @enderror"
                       id="measurement_transformer_range_100_percent" name="measurement_transformer_range_100_percent"
                       placeholder="250"
                       value="{{ old('measurement_transformer_range_100_percent', optional($flowMeterInspectionReport)->measurement_transformer_range_100_percent) }}"
                       required/>
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

        <div class="mb-3">
            <label for="measurement_transformer_impulses">Impulsausgang</label>
            <div class="input-group">
                <input type="number" min="0"
                       class="form-control @error('measurement_transformer_impulses') is-invalid @enderror"
                       id="measurement_transformer_impulses" name="measurement_transformer_impulses" placeholder="1"
                       value="{{ old('measurement_transformer_impulses', optional($flowMeterInspectionReport)->measurement_transformer_impulses) }}"
                       required/>
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

        <div class="mb-3">
            <label for="measurement_transformer_data_logging">Aufzeichnung der Durchflusssummen und Momentanwerte für die Betriebsprotokolle</label>
            <input type="text"
                   class="form-control @error('measurement_transformer_data_logging') is-invalid @enderror"
                   id="measurement_transformer_data_logging"
                   name="measurement_transformer_data_logging" placeholder="Aufzeichnung"
                   value="{{ old('measurement_transformer_data_logging', optional($flowMeterInspectionReport)->measurement_transformer_data_logging) }}"
                   required/>
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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#water"></use>
            </svg>
            Bestandsaufnahme Oberwasserseite
        </p>
        <p class="text-muted">
            Die Bestandsaufnahme der Oberwasserseite.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="headwater_pipe_diameter">Rohrdurchmesser innen</label>
            <div class="input-group">
                <input type="number" min="0" class="form-control @error('headwater_pipe_diameter') is-invalid @enderror"
                       id="headwater_pipe_diameter" name="headwater_pipe_diameter" placeholder="600"
                       value="{{ old('headwater_pipe_diameter', optional($flowMeterInspectionReport)->headwater_pipe_diameter) }}"
                       required/>
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

        <div class="mb-3">
            <label for="headwater_calming_section">Länge der einlaufseitigen Beruhigungsstrecke</label>
            <input type="text" class="form-control @error('headwater_calming_section') is-invalid @enderror"
                   id="headwater_calming_section" name="headwater_calming_section" placeholder="5 x Rohrdurchmesser"
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

        <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#moisture"></use>
            </svg>
            Bestandsaufnahme Messstrecke
        </p>
        <p class="text-muted">
            Die Bestandsaufnahme der Messstrecke.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="measurement_section_slope">Gefälle der Messstrecke</label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_section_slope') is-invalid @enderror"
                       id="measurement_section_slope" name="measurement_section_slope" placeholder="9"
                       value="{{ old('measurement_section_slope', optional($flowMeterInspectionReport)->measurement_section_slope) }}"/>
                    <span class="input-group-text">‰</span>
                <div class="invalid-feedback">
                    @error('measurement_section_slope')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
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

        <div class="mb-3">
            <div>
                <label for="measurement_section_installation_according_to_manufacturer">Einbaubedingungen laut
                    Hersteller erfüllt</label>
            </div>
            <div class="btn-group @error('measurement_section_installation_according_to_manufacturer') is-invalid @enderror"
                 >
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

                <div class="mb-3">
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

                <div class="mb-3">
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

                <div class="mb-3">
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

                <div class="mb-3">
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

                <div class="mb-3">
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

                <div class="mb-3">
                    <label for="comparison_measurement_mobile_equipment_q_min">Minimaler Durchfluss Q<sub>min</sub>
                        (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_q_min') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_q_min"
                               name="comparison_measurement_mobile_equipment_q_min" placeholder="0"
                               value="{{ old('comparison_measurement_mobile_equipment_q_min', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_q_min) }}"/>
                            <span class="input-group-text">l/s</span>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_q_min')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comparison_measurement_mobile_equipment_maximum_flow_rate">Messbereich 100%
                        Durchflussrate (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_maximum_flow_rate') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_maximum_flow_rate"
                               name="comparison_measurement_mobile_equipment_maximum_flow_rate" placeholder="282"
                               value="{{ old('comparison_measurement_mobile_equipment_maximum_flow_rate', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_flow_rate) }}"/>
                            <span class="input-group-text">l/s oder m³/h</span>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_maximum_flow_rate')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_flow_rate_unit">Messbereich 100%
                            Durchflussrate Einheit</label>
                    </div>
                    <div class="btn-group @error('comparison_measurement_mobile_equipment_maximum_flow_rate_unit') is-invalid @enderror"
                         >
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

                <div class="mb-3">
                    <label for="comparison_measurement_mobile_equipment_maximum_speed">Messbereich 100% Geschwindigkeit
                        (Herstellerangabe)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_mobile_equipment_maximum_speed') is-invalid @enderror"
                               id="comparison_measurement_mobile_equipment_maximum_speed"
                               name="comparison_measurement_mobile_equipment_maximum_speed" placeholder="10"
                               value="{{ old('comparison_measurement_mobile_equipment_maximum_speed', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed) }}"/>
                            <span class="input-group-text">m/s</span>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_mobile_equipment_maximum_speed')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div>
                        <label for="comparison_measurement_mobile_equipment_maximum_speed_unit">Messbereich 100%
                            Geschwindigkeit Einheit</label>
                    </div>
                    <div class="btn-group @error('comparison_measurement_mobile_equipment_maximum_speed_unit') is-invalid @enderror"
                         >
                        <input type="radio" class="btn-check" name="comparison_measurement_mobile_equipment_maximum_speed_unit" id="comparison_measurement_mobile_equipment_maximum_speed_unit-m_s" value="m_s" autocomplete="off" @if(old('comparison_measurement_mobile_equipment_maximum_speed_unit', optional($flowMeterInspectionReport)->comparison_measurement_mobile_equipment_maximum_speed_unit) == 'm_s') checked @endif>
                        <label class="btn btn-outline-secondary" for="comparison_measurement_mobile_equipment_maximum_speed_unit-m_s">m/s</label>
                    </div>
                    <div class="invalid-feedback @error('comparison_measurement_mobile_equipment_maximum_speed_unit') d-block @enderror">
                        @error('comparison_measurement_mobile_equipment_maximum_speed_unit')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
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

                <div class="mb-3">
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

                <div class="mb-3">
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
                <div class="mb-3">
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

                <div class="mb-3">
                    <label for="comparison_measurement_volumetric_basin_cross_section_area">Querschnittsfläche
                        des Vergleichsbehälters</label>
                    <div class="input-group">
                        <input type="number" min="0" step="any"
                               class="form-control @error('comparison_measurement_volumetric_basin_cross_section_area') is-invalid @enderror"
                               id="comparison_measurement_volumetric_basin_cross_section_area"
                               name="comparison_measurement_volumetric_basin_cross_section_area"
                               placeholder="25"
                               value="{{ old('comparison_measurement_volumetric_basin_cross_section_area', optional($flowMeterInspectionReport)->comparison_measurement_volumetric_basin_cross_section_area) }}"/>
                            <span class="input-group-text">m²</span>
                        <div class="invalid-feedback">
                            @error('comparison_measurement_volumetric_basin_cross_section_area')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#card-text"></use>
            </svg>
            Dokumentation der Vergleichsmessung
        </p>
        <p class="text-muted">
            Details zur durchgeführten Vergleichsmessung.
        </p>
    </div>

    <div class="col-md-8">
        <ul class="nav nav-tabs nav-fill mb-2" id="comparison-measurements" role="tablist">
            @foreach( $comparison_measurement_q_percentages as $q_percentage )
                <li class="nav-item">
                    <a class="nav-link @if($loop->last) active @endif @error('measurements.'.$q_percentage.'.*') text-danger @enderror" id="q{{ $q_percentage }}-tab" data-bs-toggle="tab"
                       href="#q{{ $q_percentage }}" role="tab" aria-controls="q{{ $q_percentage }}"
                       aria-selected="true">
                        @error('measurements.'.$q_percentage.'.*')
                            <svg class="icon-bs icon-baseline text-danger me-1">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
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
                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][q_value]">Q<sub>{{ $q_percentage === 100 ? 'gesamt' : $q_percentage.'%' }}</sub></label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.q_value') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][q_value]"
                                   name="measurements[{{ $q_percentage }}][q_value]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.q_value', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value) }}"/>
                                <span class="input-group-text">l/s</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.q_value')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][started_at]">Datum und Uhrzeit Start</label>
                        <input type="datetime-local" class="form-control @error('measurements.'.$q_percentage.'.started_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][started_at]" name="measurements[{{ $q_percentage }}][started_at]" value="{{ old('measurements.'.$q_percentage.'.started_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->started_at_for_input_field) }}" />
                        <div class="invalid-feedback">
                            @error('measurements.'.$q_percentage.'.started_at')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][ended_at]">Datum und Uhrzeit Ende</label>
                        <input type="datetime-local" class="form-control @error('measurements.'.$q_percentage.'.ended_at') is-invalid @enderror" id="measurements[{{ $q_percentage }}][ended_at]" name="measurements[{{ $q_percentage }}][ended_at]" value="{{ old('measurements.'.$q_percentage.'.ended_at', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->ended_at_for_input_field) }}" />
                        <div class="invalid-feedback">
                            @error('measurements.'.$q_percentage.'.ended_at')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]">Messwertumformer Zählerstand Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_start) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]">Messwertumformer Zählerstand Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_end) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]">Messwertumformer Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]"
                                   name="measurements[{{ $q_percentage }}][measurement_transformer_reading_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_transformer_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_transformer_reading_sum) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_transformer_reading_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_start]">Prozessleitsystem Zählerstand Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_start]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_start) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_end]">Prozessleitsystem Zählerstand Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_end]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_end) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][pcs_reading_sum]">Prozessleitsystem Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.pcs_reading_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][pcs_reading_sum]"
                                   name="measurements[{{ $q_percentage }}][pcs_reading_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.pcs_reading_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->pcs_reading_sum) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.pcs_reading_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_start]">Vergleichsmessung Start</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_start') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_start]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_start]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_start', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_start) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_start')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_end]">Vergleichsmessung Ende</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_end') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_end]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_end]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_end', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_end) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_end')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][comparison_measurement_sum]">Vergleichsmessung Summe</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.comparison_measurement_sum') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][comparison_measurement_sum]"
                                   name="measurements[{{ $q_percentage }}][comparison_measurement_sum]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.comparison_measurement_sum', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->comparison_measurement_sum) }}"/>
                                <span class="input-group-text">m³</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.comparison_measurement_sum')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][measurement_difference]">Abweichung Vergleichsmessung/stationär</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.measurement_difference') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][measurement_difference]"
                                   name="measurements[{{ $q_percentage }}][measurement_difference]" placeholder="3"
                                   value="{{ old('measurements.'.$q_percentage.'.measurement_difference', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->measurement_difference) }}"/>
                                <span class="input-group-text">%</span>
                            <div class="invalid-feedback">
                                @error('measurements.'.$q_percentage.'.measurement_difference')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="measurements[{{ $q_percentage }}][q_value_average_mobile]">Errechneter Mittelwert mobil</label>
                        <div class="input-group">
                            <input type="number" min="0" step="any"
                                   class="form-control @error('measurements.'.$q_percentage.'.q_value_average_mobile') is-invalid @enderror"
                                   id="measurements[{{ $q_percentage }}][q_value_average_mobile]"
                                   name="measurements[{{ $q_percentage }}][q_value_average_mobile]" placeholder="10"
                                   value="{{ old('measurements.'.$q_percentage.'.q_value_average_mobile', optional(optional($flowMeterInspectionReport)->{'measurementsQ'.$q_percentage})->q_value_average_mobile) }}"/>
                                <span class="input-group-text">l/s</span>
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
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
            </svg>
            Zusammenfassende Beurteilung
        </p>
        <p class="text-muted">
            Die abschließende Beurteilung der Überprüfung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="measurement_difference_up_to_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_difference_up_to_30_q_max') is-invalid @enderror"
                       id="measurement_difference_up_to_30_q_max"
                       name="measurement_difference_up_to_30_q_max" placeholder="3"
                       value="{{ old('measurement_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_up_to_30_q_max) }}"
                       required/>
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

        <div class="mb-3">
            <label for="measurement_difference_above_30_q_max">Abweichung Messwerte stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('measurement_difference_above_30_q_max') is-invalid @enderror"
                       id="measurement_difference_above_30_q_max"
                       name="measurement_difference_above_30_q_max" placeholder="2"
                       value="{{ old('measurement_difference_above_30_q_max', optional($flowMeterInspectionReport)->measurement_difference_above_30_q_max) }}"
                       required/>
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

        <div class="mb-3">
            <label for="reading_difference_up_to_30_q_max">Abweichung Zähölerstände stationär zur Vergleichmessung von 0,1 Q<sub>max</sub> bis 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('reading_difference_up_to_30_q_max') is-invalid @enderror"
                       id="reading_difference_up_to_30_q_max"
                       name="reading_difference_up_to_30_q_max" placeholder="5"
                       value="{{ old('reading_difference_up_to_30_q_max', optional($flowMeterInspectionReport)->reading_difference_up_to_30_q_max) }}"
                       required/>
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

        <div class="mb-3">
            <label for="reading_difference_above_30_q_max">Abweichung Zählerstände stationär zur Vergleichmessung über 0,3 Q<sub>max</sub></label>
            <div class="input-group">
                <input type="number" min="0" step="any"
                       class="form-control @error('reading_difference_above_30_q_max') is-invalid @enderror"
                       id="reading_difference_above_30_q_max"
                       name="reading_difference_above_30_q_max" placeholder="3"
                       value="{{ old('reading_difference_above_30_q_max', optional($flowMeterInspectionReport)->reading_difference_above_30_q_max) }}"
                       required/>
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

        <div class="mb-3">
            <div>
                <label for="equipment_in_tolerance_range"><strong>Das Messsystem arbeitet innerhalb des Toleranzbereichs des ÖWAV Regelblatts 38</strong></label>
            </div>
            <div class="btn-group @error('equipment_in_tolerance_range') is-invalid @enderror"
                 >
                <input type="radio" class="btn-check" name="equipment_in_tolerance_range" id="equipment_in_tolerance_range-1" value="1" autocomplete="off" @if(old('equipment_in_tolerance_range') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="equipment_in_tolerance_range-1">ja</label>
                <input type="radio" class="btn-check" name="equipment_in_tolerance_range" id="equipment_in_tolerance_range-0" value="0" autocomplete="off" @if(old('equipment_in_tolerance_range') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->equipment_in_tolerance_range === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="equipment_in_tolerance_range-0">nein</label>
            </div>
            <div class="invalid-feedback @error('equipment_in_tolerance_range') d-block @enderror">
                @error('equipment_in_tolerance_range')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
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

        <div class="mb-3">
            <div>
                <label for="further_inspection_required">Zweitprüfung/Vollprüfung nach Korrektur erforderlich</label>
            </div>
            <div class="btn-group @error('further_inspection_required') is-invalid @enderror"
                 >
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-1" value="1" autocomplete="off" @if(old('further_inspection_required') === '1' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === true)) checked @endif>
                <label class="btn btn-outline-secondary" for="further_inspection_required-1">ja</label>
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-0" value="0" autocomplete="off" @if(old('further_inspection_required') === '0' || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === false)) checked @endif>
                <label class="btn btn-outline-secondary" for="further_inspection_required-0">nein</label>
                <input type="radio" class="btn-check" name="further_inspection_required" id="further_inspection_required-none" value="" autocomplete="off" @if((old('_token') && old('further_inspection_required') === null) || ($flowMeterInspectionReport && $flowMeterInspectionReport->further_inspection_required === null)) checked @endif>
                <label class="btn btn-outline-secondary" for="further_inspection_required-none">keine Angabe</label>
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
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Kommentare zur Prüfung
        </p>
        <p class="text-muted">
            Sonstige Anmerkungen und Kommentare zur Prüfung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="comment">
                Kommentare zur Prüfung
            </label>
            <markdown-editor name="comment" placeholder="Kommentare zur Prüfung"  value="{{ old('comment', optional($flowMeterInspectionReport)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 me-1">
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
            <svg class="icon icon-16 me-2">
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
        <div class="mb-3">
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

        <div class="alert alert-warning mt-1" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 me-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                </svg>
                <p class="m-0">
                    Um eine korrekte Formatierung des Anhanges im PDF Ausdruck sicherzustellen muss der Anhang im
                    <strong>A4 Hochformat</strong> vorliegen.
                </p>
            </div>
        </div>

        <div class="mb-3">
            <label>PDF Anhang für den
                Ausdruck{{ $flowMeterInspectionReport ? ' (Ohne Auswahl wird der aktuelle Anhang beibehalten)' : '' }}</label>
            <div class="mb-3">
                <input type="file" accept="application/pdf" class="form-control" id="appendix"
                       name="appendix">
                <label class="form-label" for="appendix">PDF Anhang auswählen</label>
            </div>
            <div class="invalid-feedback @error('appendix') d-block @enderror">
                @error('appendix')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
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
            <svg class="icon icon-16 me-2">
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
                <svg class="icon icon-24 me-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input type="checkbox"
                       class="form-check-input @error('send_signature_request') is-invalid @enderror"
                       name="send_signature_request" id="send_signature_request" value="true">
                <label class="form-check-label" for="send_signature_request">Anfrage zur Unterschrift nach dem
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
