<nav class="q-breadcrumb">
    <a href="{{ route('finance-groups.index') }}">Finanzgruppen</a>
    <span class="q-breadcrumb__sep">/</span>
    <a href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}">{{ $financeRecord->financeGroup->title }}</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $financeRecord->title }}</span>
</nav>
