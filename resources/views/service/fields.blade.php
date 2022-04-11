@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
            </svg>
            Leistungsdetails
        </p>
        <p class="text-muted">
            Die Details der Leistung.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Techniker" value="{{ old('name', optional($service)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen der Leistung ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description">Beschreibung</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Techniker Leistung" value="{{ old('description', optional($service)->description) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte die Beschreibung der Leistung ein.
                @enderror
            </div>
        </div>

    </div>

</div>
