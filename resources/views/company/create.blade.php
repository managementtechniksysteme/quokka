@extends('layouts.app')

{{-- Mobile app bar: "X" close + generic title, not a back-chevron or the
     record's own name — forms are a dismissable modal-like flow, not a
     navigable detail page, per the Claude Design mobile mockup's "Form"
     frame (2026-07-21). No kebab; nothing to act on yet before saving. --}}
@section('mobile-detail-bar')
    <a href="{{ route('companies.index') }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-lg"></use></svg>
    </a>
    <span class="q-appbar__title">Firma anlegen</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Firma anlegen</div>
                    <h1 class="q-title">Neue Firma</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('companies.store') }}" method="post" novalidate>
            @include('company.fields', ['company' => $company, 'currentAddress' => $currentAddress, 'currentOperatorAddress' => $currentOperatorAddress, 'addresses' => $addresses, 'currentContactPerson' => $currentContactPerson, 'people' => $people])

            {{-- Abbrechen dropped on mobile: the app bar's "X" already covers
                 it, and unlike a bottom button it's reachable without
                 scrolling on a long form (2026-07-21, user: "double info
                 with the abort button at the bottom"). Desktop keeps it —
                 there's no X there. --}}
            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('companies.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Firma speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
