@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                </svg>
                Finanzübersicht
            </h3>

            <div class="scroll-x d-flex">
                @can('finances-createpdf')
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('finances.download') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        <h4>
            <svg class="icon-bs icon-baseline text-muted mr-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart-2"></use>
            </svg>
            Aktuelle Finanzübersicht
        </h4>

        <div class="row">

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Einnahmen</h5>
                        <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($groupTotals['revenue'], 2) }}{{ $currencyUnit }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Ausgaben</h5>
                        <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($groupTotals['expense'], 2) }}{{ $currencyUnit }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Differenz</h5>
                        <span class="h2 font-weight-bold @if($groupTotals['revenue'] + $groupTotals['expense'] >= 0) text-green @else text-red @endif  m-0">{{ Number::toLocal($groupTotals['revenue'] + $groupTotals['expense'], 2) }}{{ $currencyUnit }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <finance-revenue-expense-chart :revenue="{{ $groupTotals['revenue'] }}" :expense="{{ $groupTotals['expense'] }}" v-cloak></finance-revenue-expense-chart>
        </div>


        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted mr-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use>
            </svg>
            Gruppenübersicht
        </h4>

        <form class="needs-validation mt-4" action="{{ route('finances.index') }}" method="get" novalidate>
            <div class="row">
                <div class="col d-flex">
                    <div class="form-group flex-grow-1 mr-2">
                        <finance-group-dropdown :finance_groups="{{ $financeGroups }}" :current_finance_group="{{ $currentFinanceGroup ?? 'null' }}" inputname="group" v-cloak></finance-group-dropdown>
                        <div class="invalid-feedback @error('finance_group_id') d-block @enderror">
                            @error('finance_group_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#eye"></use>
                            </svg>
                            Anzeigen
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if($groupData)
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">Einnahmen</h5>
                            <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($groupData['revenue'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">Ausgaben</h5>
                            <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($groupData['expense'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">Differenz</h5>
                            <span class="h2 font-weight-bold @if($groupData['revenue'] + $groupData['expense'] >= 0) text-green @else text-red @endif m-0">{{ Number::toLocal($groupData['revenue'] + $groupData['expense'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <finance-revenue-expense-chart :revenue="{{ $groupData['revenue'] }}" :expense="{{ $groupData['expense'] }}" v-cloak></finance-revenue-expense-chart>
            </div>
        @endif

    </div>
@endsection
