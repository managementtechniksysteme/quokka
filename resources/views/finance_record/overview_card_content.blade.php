<div class="overview-card rounded">
    <div class="mw-100 d-flex p-3 align-items-center">

        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">
            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('finance-records.show', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}"></a>
                <div class="mw-100 text-truncate">
                    {{ $financeRecord->title }}
                </div>
                <div class="text-muted mw-100 d-flex align-items-center">
                    <svg class="icon icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $financeRecord->billed_on }}
                    </span>
                </div>
            </div>
        </div>

        <div class="d-none d-sm-block ml-2">
            <span class="@if($financeRecord->amount >= 0) text-muted @else text-danger @endif d-inline-flex align-items-center">
                <svg class="icon icon-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                </svg>
                {{ Number::toLocal($financeRecord->amount, 2) }}
            </span>
        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="financeRecordOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="financeRecordOverviewDropdown">
                    @can('update', $financeRecord)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('delete', $financeRecord)
                        <form action="{{ route('finance-records.destroy', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                </svg>
                                Entfernen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>

</div>
