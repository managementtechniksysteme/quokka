@if (old('unit'))
    @php $currentUnit = old('unit'); @endphp
@endif

@component('service.fields', [ 'service' => $wageService ])
@endcomponent

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
            </svg>
            Lohndienstleistung
        </p>
        <p class="text-muted">
            Spezifische Details zur Lohndienstleistung.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-group">
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

        <div class="form-group">
            <label for="costs">Kosten pro Einheit</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">€</span>
                </div>
                <input type="number" min="0" step="0.1" class="form-control @error('costs') is-invalid @enderror" id="costs" name="costs" placeholder="" value="{{ old('costs', optional($wageService)->costs) }}" />
            </div>
            <div class="invalid-feedback">
                @error('costs')
                    {{ $message }}
                @else
                    Gib bitte die Kosten der Leistung ein.
                @enderror
            </div>
        </div>

    </div>

</div>
