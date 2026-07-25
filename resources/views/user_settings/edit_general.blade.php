@extends('user_settings.edit')

@section('tab')
    <form class="q-form" action="{{ route('user-settings.update-signature') }}" method="post">
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Unterschrift
                <div class="q-form-section__desc">
                    Die Unterschrift ist erforderlich, um Berichte, wie etwa Serviceberichte, automatisch mit einer Unterschrift zu versehen.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                @if(Auth::user()->signature())
                    <div>
                        <label class="form-label">Deine aktuelle Unterschrift</label>
                        <div class="q-sign-img mt-1" style="max-width: 220px;">
                            <img src="{{ Auth::user()->signature()->getUrl() }}" alt="{{ Auth::user()->signature()->file_name }}" style="max-height: 80px; object-fit: contain; width: 100%; display: block;" />
                        </div>
                        <div class="text-muted small mt-1">{{ Auth::user()->signature()->file_name }} · {{ Auth::user()->signature()->human_readable_size }}</div>
                    </div>
                @endif

                <div>
                    <signature-pad></signature-pad>
                    <div class="invalid-feedback @error('signature') d-block @enderror">
                        @error('signature')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="q-form-actions">
            <a class="btn q-btn d-inline-flex align-items-center gap-2" href="">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                Zurücksetzen
            </a>
            <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                <span class="d-none d-md-inline">Unterschrift speichern</span>
                <span class="d-inline d-md-none">Speichern</span>
            </button>
        </div>
    </form>
@endsection
