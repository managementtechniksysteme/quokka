<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
    </svg>
    <a href="{{ route('roles.index') }}">Rollen</a>
    <span class="px-2">/</span>
    <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
</div>
