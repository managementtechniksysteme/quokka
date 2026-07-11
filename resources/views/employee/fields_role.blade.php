@php use Spatie\Permission\Models\Role; @endphp

@if (old('role_id'))
    @php $currentRole = Role::find(old('role_id')); @endphp
@endif

@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Rolle zuweisen
        <div class="q-form-section__desc">Dem Mitarbeiter alle Berechtigungen der ausgewählten Rolle zuweisen.</div>
    </div>
    <div class="q-form-section__body">
        <label for="role_id">Rolle</label>
        <role-dropdown :roles="{{ $roles }}" :current_role="{{ $currentRole ?? 'null' }}" v-cloak></role-dropdown>
        <div class="invalid-feedback">
            @error('role_id')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>
