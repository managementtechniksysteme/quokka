@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten der Rolle.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
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

@component('permission.fields', [ 'permissions' => $role ])
@endcomponent
