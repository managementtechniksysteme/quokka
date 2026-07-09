@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('exception.breadcrumb')

            <h3>
                Fehlerdatei
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $exception['uuid'] }}
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('tools-deleteexceptions')
                    <form action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon-bs icon-16 me-2">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        <pre>{{ $exception['content'] }}</pre>


    </div>
@endsection
