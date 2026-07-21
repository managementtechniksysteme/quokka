@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $financeRecord->title }}</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('finance_record.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Finanzeintrag bearbeiten</div>
                    <h1 class="q-title">{{ $financeRecord->title }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('finance-records.update', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post" novalidate>
            @method('PATCH')
            @include('finance_record.fields', ['financeRecord' => $financeRecord, 'currencyUnit' => $currencyUnit])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('finance-groups.show', $financeRecord->financeGroup) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Finanzeintrag speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
