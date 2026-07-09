@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Leistungsdetails
        <div class="q-form-section__desc">Die Details der Leistung.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
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

        <div>
            <label for="description">Beschreibung</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Techniker Leistung" value="{{ old('description', optional($service)->description) }}" required />
            <div class="invalid-feedback">
                @error('description')
                    {{ $message }}
                @else
                    Gib bitte die Beschreibung der Leistung ein.
                @enderror
            </div>
        </div>
    </div>
</div>
