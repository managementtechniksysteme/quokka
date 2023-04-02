@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('interim_invoice.breadcrumb')

            <h3>
                Teilrechnung
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $interimInvoice->title }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $interimInvoice)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('delete', $interimInvoice)
                    <form action="{{ route('interim-invoices.destroy', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
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

    <div class="container my-4">
      <div class="row">
        <div class="col-sm-2">
          <div class="text-muted d-flex align-items-center">
            <svg class="icon icon-16 mr-2">
              <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
            </svg>
            Datum
          </div>
        </div>
        <div class="col">
	  {{ $interimInvoice->billed_on }}
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-2">
          <div class="text-muted d-flex align-items-center">
            <svg class="icon icon-16 mr-2">
              <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
            </svg>
            Summe
          </div>
        </div>
        <div class="col">
	        {{ Number::toLocal($interimInvoice->amount, 2) }}
        </div>
      </div>

      @if ($interimInvoice->comment)
        <div class="text-muted d-flex align-items-center mt-4">
          <svg class="icon icon-16 mr-2">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
          </svg>
          Bemerkungen
        </div>
        <div class="markdown">
          {!! Html::fromMarkdown($interimInvoice->comment) !!}
        </div>
      @endif
    </div>
@endsection
