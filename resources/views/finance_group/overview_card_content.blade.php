<div class="overview-card rounded">
    <div class="mw-100 d-flex flex-grow-1 p-3 align-items-center">
        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">
            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('finance-groups.show', $financeGroup) }}"></a>
                <div class="mw-100 d-flex align-items-center">
                    {{ $financeGroup->title }}
                </div>
                <div class="text-muted mw-100 text-truncate">
                    {{ $financeGroup->finance_records_count }} Einträge
                </div>
            </div>
        </div>

        <div class="d-none d-sm-block ml-2">
            @if($financeGroup->finance_records_count)
                <span class="@if($financeGroup->finance_records_sum_amount >= 0) text-muted @else text-danger @endif d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                    </svg>
                    {{ Number::toLocal($financeGroup->finance_records_sum_amount, 2) }}
            </span>
            @endif

        </div>

        <div class="d-none d-md-block ml-2">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="financeGroupOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="financeGroupsOverviewDropdown">
                    @can('update', $financeGroup)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('finance-groups.edit', $financeGroup) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('delete', $financeGroup)
                        <form action="{{ route('finance-groups.destroy', $financeGroup) }}" method="post">
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
