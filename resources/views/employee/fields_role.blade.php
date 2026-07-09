@php use Spatie\Permission\Models\Role; @endphp

@if (old('role_id'))
    @php $currentRole = Role::find(old('role_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use>
            </svg>
            Rolle zuweisen
        </p>
        <p class="text-muted">
            Dem Mitarbeiter alle Berechtigungen der ausgewählten Rolle zuweisen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
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
