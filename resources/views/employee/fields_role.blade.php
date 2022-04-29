@php use Spatie\Permission\Models\Role; @endphp

@if (old('role_id'))
    @php $currentRole = Role::find(old('role_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
            </svg>
            Rolle zuweisen
        </p>
        <p class="text-muted">
            Dem Mitarbeiter alle Berechtigungen der ausgewählten Rolle zuweisen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="role_id">Rolle</label>
            <role-dropdown :roles="{{ $roles }}" :current_role="{{ $currentRole ?? 'null' }}" v-cloak></role-dropdown>
            <div class="invalid-feedback">
                @error('role_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>

</div>
