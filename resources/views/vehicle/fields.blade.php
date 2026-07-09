@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Fahrzeugs.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
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

        <div>
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

        <div>
            <label for="registration_identifier">Kennzeichen</label>
            <input type="text" class="form-control @error('registration_identifier') is-invalid @enderror" id="registration_identifier" name="registration_identifier" placeholder="VW-XYZ1" value="{{ old('registration_identifier', optional($vehicle)->registration_identifier) }}" required />
            <div class="invalid-feedback">
                @error('registration_identifier')
                    {{ $message }}
                @else
                    Gib bitte das Kennzeichen des Fahrzeugs ein.
                @enderror
            </div>
        </div>

        <div>
            <label>Privatfahrzeug</label>
            <div class="btn-group @error('private') is-invalid @enderror">
                <input type="radio" class="btn-check" name="private" id="private-1" value="1" autocomplete="off" @if(old('private', optional($vehicle)->private) == true) checked @endif>
                <label class="btn btn-outline-secondary q-seg--amber" for="private-1">ja</label>
                <input type="radio" class="btn-check" name="private" id="private-0" value="0" autocomplete="off" @if(old('private', optional($vehicle)->private) == false) checked @endif>
                <label class="btn btn-outline-secondary" for="private-0">nein</label>
            </div>
            <div class="invalid-feedback @error('private') d-block @enderror">
                @error('private')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zum Fahrzeug.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Fahrzeug" value="{{ old('comment', optional($vehicle)->comment) }}" v-cloak></markdown-editor>
        <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
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
