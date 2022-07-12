<div class="overview-card rounded">
    <div class="mw-100 d-flex p-3 align-items-center">

        <div class="mw-100 d-flex flex-grow-1 h-100 align-items-center">
            <div class="mw-100 flex-grow-1 position-relative">
                <a class="stretched-link outline-none" href="{{ route('interim-invoices.show', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}"></a>
                <div class="mw-100 d-flex align-items-center">
                    <span class="mw-100 text-truncate">
                        {{ $interimInvoice->title }}
                    </span>
                </div>
                <div class="text-muted d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                    </svg>
                    {{ $interimInvoice->billed_on }}
                    <svg class="icon icon-16 ml-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                    </svg>
                    {{ Number::toLocal($interimInvoice->amount) }}
                </div>
            </div>

            <div class="d-none d-md-block ml-2">
                <div class="dropdown d-inline">
                    <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="projectOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projectOverviewDropdown">
                        @can('update', $interimInvoice)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                                </svg>
                                Bearbeiten
                            </a>
                        @endcan
                        @can('delete', $interimInvoice)
                            <form action="{{ route('interim-invoices.destroy', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}" method="post">
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
</div>
