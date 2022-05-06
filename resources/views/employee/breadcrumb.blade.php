<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
    </svg>
    <a href="{{ route('employees.index') }}">Mitarbeiter</a>
    <span class="px-2">/</span>
    <a href="{{ route('employees.show', $employee) }}">{{ $employee->person->name }}</a>
</div>
