@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten der Rolle.</div>
    </div>
    <div class="q-form-section__body">
        <div>
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musterrolle" value="{{ old('name', optional($role)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen der Rolle ein.
                @enderror
            </div>
        </div>
    </div>
</div>

@include('permission.fields', ['permissions' => $role])
