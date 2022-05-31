@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Fahrzeugs.
        </p>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="make">Marke</label>
            <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" placeholder="Mustermarke" value="{{ old('make', optional($vehicle)->make) }}" required />
            <div class="invalid-feedback">
                @error('make')
                    {{ $message }}
                @else
                    Gib bitte die Marke des Fahrzeugs ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="model">Modell</label>
            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" placeholder="Mustermodell" value="{{ old('model', optional($vehicle)->model) }}" required />
            <div class="invalid-feedback">
                @error('model')
                    {{ $message }}
                @else
                    Gib bitte das Modell des Fahrzeugs ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="registration_identifier">Kennzeichen</label>
            <input type="text" class="form-control @error('registration_identifier') is-invalid @enderror" id="registration_identifier" name="registration_identifier" placeholder="VW-XYZ1" value="{{ old('registration_identifier', optional($vehicle)->registration_identifier) }}" required />
            <div class="invalid-feedback">
                @error('registration_identifier')
                    {{ $message }}
                @else
                    Gib bitte das Modell des Fahrzeugs ein.
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
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zum Fahrzeug.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zum Fahrzeug"  value="{{ old('comment', optional($vehicle)->comment) }}" v-cloak></markdown-editor>
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
