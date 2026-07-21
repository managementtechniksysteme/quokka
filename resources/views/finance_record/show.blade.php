@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}" class="q-appbar__btn" aria-label="Zurück zur Finanzgruppe">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $financeRecord->title }}</span>
    @canany(['update', 'delete'], $financeRecord)
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#financeRecordShowActionsSheet" aria-controls="financeRecordShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcanany
@endsection

@section('mobile-detail-sheets')
    @canany(['update', 'delete'], $financeRecord)
        <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="financeRecordShowActionsSheet" aria-label="Aktionen">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">Aktionen</div>
                @can('update', $financeRecord)
                    <a class="q-row" href="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                        <span class="q-row__title">Bearbeiten</span>
                    </a>
                @endcan
                @can('delete', $financeRecord)
                    <form action="{{ route('finance-records.destroy', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="q-row q-row--danger">
                            <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                            <span class="q-row__title">Entfernen</span>
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @endcanany
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('finance_record.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <div class="q-avatar q-avatar--icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                </div>
                <div>
                    <div class="q-eyebrow">Finanzeintrag</div>
                    <h1 class="q-title">{{ $financeRecord->title }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $financeRecord)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="financeRecordShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financeRecordShowDropdown">
                        @can('delete', $financeRecord)
                            <form action="{{ route('finance-records.destroy', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-card mt-2 mt-md-4">
            <div class="q-card__head">Details</div>
            <div class="q-card__body">

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#layers"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Finanzgruppe</div>
                        <div class="q-inforow__value">
                            <a href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}">{{ $financeRecord->financeGroup->title }}</a>
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Datum</div>
                        <div class="q-inforow__value">{{ $financeRecord->billed_on->format('d.m.Y') }}</div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Betrag</div>
                        <div class="q-inforow__value q-mono" @if($financeRecord->amount < 0) style="color: var(--q-red)" @endif>
                            {{ Number::toLocal($financeRecord->amount, 2) }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if ($financeRecord->comment)
            <div class="q-card mt-3">
                <div class="q-card__head">Bemerkungen</div>
                <div class="q-card__body">
                    <div class="markdown">
                        {!! Html::fromMarkdown($financeRecord->comment) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
