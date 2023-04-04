@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
            </svg>
            Finanzgruppe Details
        </p>
        <p class="text-muted">
            Die Details der Finanzgruppe.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="title">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Mustergruppe" value="{{ old('title', optional($financeGroup)->title) }}" />
            <div class="invalid-feedback">
                @error('title')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zur Finanzgruppe"  value="{{ old('comment', optional($financeGroup)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe zu Markdown
            </a>
            <div class="invalid-feedback @error('comment') d-block @enderror">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>

</div>
