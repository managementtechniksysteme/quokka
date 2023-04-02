<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#menu"></use>
    </svg>
    <a href="{{ route('finance-groups.index') }}">Finanzgruppen</a>
    <span class="px-2">/</span>
    <a href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}">{{ $financeRecord->financeGroup->title_string }}</a>
</div>
