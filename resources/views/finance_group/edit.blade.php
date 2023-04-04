@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('finance_group.breadcrumb')

            <h3>
                Finanzgruppe bearbeiten
                <small class="text-muted">{{ $financeGroup->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('finance-groups.update', $financeGroup) }}" method="post" novalidate>
            @method('PATCH')
            @component('finance_group.fields', [ 'financeGroup' => $financeGroup ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Finanzgruppe speichern
            </button>
        </form>
    </div>
@endsection
