@extends('layouts.app')

{{-- Mobile app bar: "X" close + generic title (not the record's own name —
     that's the detail page's job, see company/show.blade.php), per the
     Claude Design mobile mockup's "Form" frame (2026-07-21). Closing goes
     back to the record's show page, same as the desktop Abbrechen link. --}}
@section('mobile-detail-bar')
    <a href="{{ route('companies.show', $company) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-lg"></use></svg>
    </a>
    <span class="q-appbar__title">Firma bearbeiten</span>
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

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('companies.show', $company) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Firma speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
