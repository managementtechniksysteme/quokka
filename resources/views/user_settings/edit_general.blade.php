@extends('user_settings.edit')

@section('tab')
    <div class="row">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                </svg>
                Unterschrift
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Die Unterschrift ist erforderlich, um Berichte, wie etwa Servicebrichte, automatisch mit einer
                    Unterschrift zu versehen.
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->signature())
        <div class="row">
            <div class="col">Deine aktuelle Unterschrift</div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-3 mt-1 mb-2">
                <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                    <img class="attachment-img-preview mr-2" src="{{ Auth::user()->signature()->getUrl() }}" alt="{{ Auth::user()->signature()->file_name }}" />
                    <div class="min-w-0">
                        <div class="min-w-0 text-truncate">{{ Auth::user()->signature()->file_name }}</div>
                        <div class="text-muted">{{ Auth::user()->signature()->human_readable_size }}</div>
                    </div>
                    <a href="{{ Auth::user()->signature()->getUrl() }}" class="stretched-link outline-none"></a>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('user-settings.update-signature') }}" method="post">
        @csrf

        <signature-pad></signature-pad>
        <div class="invalid-feedback @error('signature') d-block @enderror">
            @error('signature')
                {{ $message }}
            @enderror
        </div>


        <div class="row mt-4">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Unterschrift speichern
                </button>
                <a class="btn btn-outline-secondary d-inline-flex align-items-center ml-1" href="">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                    </svg>
                    Zurücksetzen
                </a>
            </div>
        </div>
    </form>

@endsection
