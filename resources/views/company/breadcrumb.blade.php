<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
    </svg>
    <a href="{{ route('companies.index') }}">Firmen</a>
    <span class="px-2">/</span>
    <a href="{{ route('companies.show', $company) }}">{{ $company->full_name }}</a>
</div>
