@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
            </svg>
            Adressdetails
        </p>
        <p class="text-muted">
            Die Details der Adresse.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musterfirma" value="{{ old('name', optional($address)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen der Adresse ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="street_number">Straße und Hausnummer</label>
            <input type="text" class="form-control @error('street_number') is-invalid @enderror" id="street_number" name="street_number" placeholder="Musterstraße 99" value="{{ old('street_number', optional($address)->street_number) }}" required />
            <div class="invalid-feedback">
                @error('street_number')
                    {{ $message }}
                @else
                    Gib bitte Straße und Hausnummer der Adresse ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="postcode">Postleitzahl</label>
            <input type="text" pattern="\d*" maxlength="5" class="form-control @error('postcode') is-invalid @enderror" id="postcode" name="postcode" placeholder="1234" value="{{ old('postcode', optional($address)->postcode) }}" required />
            <div class="invalid-feedback">
                @error('postcode')
                    {{ $message }}
                @else
                    Gib bitte eine gültige Postleitzahl (bestehend aus Ziffern) ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="city">Stadt</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="Musterstadt" value="{{ old('city', optional($address)->city) }}" required />
            <div class="invalid-feedback">
                @error('city')
                    {{ $message }}
                @else
                    Gib bitte die Stadt der Adresse ein.
                @enderror
            </div>
        </div>

    </div>

</div>
