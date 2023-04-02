@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('finance_record.breadcrumb')

            <h3>
                Finanzeintrag
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $financeRecord->title }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $financeRecord)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('finance-records.edit', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('delete', $financeRecord)
                    <form action="{{ route('finance-records.destroy', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord]) }}" method="post" >
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
                {{ $financeRecord->billed_on }}
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
            <div class="col @if($financeRecord->amount >= 0) text-muted @else text-danger @endif">
                {{ Number::toLocal($financeRecord->amount) }}
            </div>
        </div>

        @if ($financeRecord->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            <div class="markdown">
                {!! Html::fromMarkdown($financeRecord->comment) !!}
            </div>
        @endif
    </div>
@endsection
