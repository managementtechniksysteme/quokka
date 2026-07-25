@php use \App\Models\Company; @endphp

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif

@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Projektes.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musterprojekt" value="{{ old('name', optional($project)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen des Projektes ein.
                @enderror
            </div>
        </div>

        {{-- Narrow enough to stay side-by-side even on a phone, unlike most
             --2 rows (2026-07-21, user). --}}
        <div class="q-form__row q-form__row--2 q-form__row--nostack">
            <div>
                <label for="starts_on">Startdatum</label>
                <input type="date" class="form-control @error('starts_on') is-invalid @enderror" id="starts_on" name="starts_on" value="{{ old('starts_on', optional(optional($project)->starts_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('starts_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <label for="ends_on">Enddatum</label>
                <input type="date" class="form-control @error('ends_on') is-invalid @enderror" id="ends_on" name="ends_on" value="{{ old('ends_on', optional(optional($project)->ends_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('ends_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Befindet sich das Projekt in der Vorphase?</label>
            <div class="btn-group @error('is_pre_execution') is-invalid @enderror">
                <input type="radio" class="btn-check" name="is_pre_execution" id="is_pre_execution-1" value="1" autocomplete="off" @if(old('is_pre_execution', optional($project)->is_pre_execution) == true) checked @endif>
                <label class="btn btn-outline-secondary q-seg--sky" for="is_pre_execution-1">ja</label>
                <input type="radio" class="btn-check" name="is_pre_execution" id="is_pre_execution-0" value="0" autocomplete="off" @if(old('is_pre_execution', optional($project)->is_pre_execution) == false) checked @endif>
                <label class="btn btn-outline-secondary" for="is_pre_execution-0">nein</label>
            </div>
            <div class="invalid-feedback @error('is_pre_execution') d-block @enderror">
                @error('is_pre_execution')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Sollen dem Projekt zugehörigen Leistungen in den Finanzen berücksichtigt werden?</label>
            <div class="btn-group @error('include_in_finances') is-invalid @enderror">
                <input type="radio" class="btn-check" name="include_in_finances" id="include_in_finances-1" value="1" autocomplete="off" @if(old('include_in_finances', optional($project)->include_in_finances) == true) checked @endif>
                <label class="btn btn-outline-secondary" for="include_in_finances-1">ja</label>
                <input type="radio" class="btn-check" name="include_in_finances" id="include_in_finances-0" value="0" autocomplete="off" @if(old('include_in_finances', optional($project)->include_in_finances) == false) checked @endif>
                <label class="btn btn-outline-secondary" for="include_in_finances-0">nein</label>
            </div>
            <div class="invalid-feedback @error('include_in_finances') d-block @enderror">
                @error('include_in_finances')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="wage_costs">Lohnkosten Auftragsvolumen</label>
            <div class="input-group has-validation">
                <span class="input-group-text">{{ $currencyUnit }}</span>
                <input type="number" min="0" step="0.01" class="form-control @error('wage_costs') is-invalid @enderror" id="wage_costs" name="wage_costs" value="{{ old('wage_costs', optional($project)->wage_costs) }}" />
                <div class="invalid-feedback">
                    @error('wage_costs')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="material_costs">Materialkosten Auftragsvolumen</label>
            <div class="input-group has-validation">
                <span class="input-group-text">{{ $currencyUnit }}</span>
                <input type="number" min="0" step="0.01" class="form-control @error('material_costs') is-invalid @enderror" id="material_costs" name="material_costs" value="{{ old('material_costs', optional($project)->material_costs) }}" />
                <div class="invalid-feedback">
                    @error('material_costs')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="billed_financial_costs">Aktuell verrechnete Kosten für die Finanzübersicht</label>
            <div class="input-group has-validation">
                <span class="input-group-text">{{ $currencyUnit }}</span>
                <input type="number" min="0" step="0.01" class="form-control @error('billed_financial_costs') is-invalid @enderror" id="billed_financial_costs" name="billed_financial_costs" value="{{ old('billed_financial_costs', optional($project)->billed_financial_costs) }}" />
                <div class="invalid-feedback">
                    @error('billed_financial_costs')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zum Projekt.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Projekt" value="{{ old('comment', optional($project)->comment) }}" :employees="{{ $employees }}" v-cloak></markdown-editor>
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
