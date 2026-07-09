<nav class="q-breadcrumb">
    <a href="{{ route('employees.index') }}">Mitarbeiter</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $employee->person->name }}</span>
</nav>
