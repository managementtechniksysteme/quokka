@if (old('unit'))
    @php $currentUnit = old('unit'); @endphp
@endif

@include('service.fields', ['service' => $wageService])

<div class="q-form-section">
    <div class="q-form-section__head">
        Lohndienstleistung
        <div class="q-form-section__desc">Spezifische Details zur Lohndienstleistung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="unit">Einheit</label>
            <service-unit-dropdown :units="{{ $units }}" current_unit="{{ $currentUnit ?? "''" }}" v-cloak></service-unit-dropdown>
            <div class="invalid-feedback">
                @error('unit')
                    {{ $message }}
                @else
                    Gib bitte die Einheit der Leistung ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="costs">Kosten pro Einheit</label>
            <div class="input-group has-validation">
                <span class="input-group-text">€</span>
                <input type="number" min="0" step="0.1" class="form-control @error('costs') is-invalid @enderror" id="costs" name="costs" placeholder="" value="{{ old('costs', optional($wageService)->costs) }}" />
                <div class="invalid-feedback @error('costs') d-block @enderror">
                    @error('costs')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
