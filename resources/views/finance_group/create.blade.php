@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Finanzgruppe anlegen</div>
                    <h1 class="q-title">Neue Finanzgruppe</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('finance-groups.store') }}" method="post" novalidate>
            @include('finance_group.fields', ['financeGroup' => $financeGroup])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('finance-groups.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Finanzgruppe speichern
                </button>
            </div>
        </form>
    </div>
@endsection
