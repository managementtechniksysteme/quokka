@extends('layouts.app')

{{-- Mobile app bar: "X" close + the record's own name — same continuity as
     the detail page's app bar you tapped "Bearbeiten" from, no avatar icon
     since the close icon fills that slot (2026-07-21, user: matches the
     detail page's design language better than a generic "Firma bearbeiten"
     label, which was redundant with the edit context anyway). Server-
     rendered, not live-bound to the Name field below, so it can't go stale
     mid-edit the way a reactive title might. --}}
@section('mobile-detail-bar')
    <a href="{{ route('companies.show', $company) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-lg"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $company->full_name }}</span>
@endsection

@section('content')
    <div class="q-container">
        @include('company.breadcrumb')

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Firma bearbeiten</div>
                    <h1 class="q-title">{{ $company->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('companies.update', $company) }}" method="post" novalidate>
            @method('PATCH')
            @include('company.fields', ['company' => $company, 'currentAddress' => $currentAddress, 'currentOperatorAddress' => $currentOperatorAddress, 'addresses' => $addresses, 'currentContactPerson' => $currentContactPerson, 'people' => $people])

            {{-- Abbrechen dropped on mobile: the app bar's "X" already covers
                 it, and unlike a bottom button it's reachable without
                 scrolling on a long form (2026-07-21, user: "double info
                 with the abort button at the bottom"). Desktop keeps it —
                 there's no X there. --}}
            <div class="q-form-actions">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('companies.show', $company) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Firma speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
