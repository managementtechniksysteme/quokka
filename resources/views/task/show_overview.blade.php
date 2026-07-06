{{-- Beschreibung --}}
@if ($task->comment)
    <div class="q-card">
        <div class="q-card__head">Beschreibung</div>
        <div class="q-card__body">
            <div class="markdown">
                {!! Html::fromMarkdown($task->comment) !!}
            </div>
        </div>
    </div>
@endif

{{-- Anhänge --}}
@if($task->attachments()->count() > 0)
    <div class="q-card">
        <div class="q-card__head">Anhänge</div>
        <div class="q-card__body">
            <div class="row g-2">
                @foreach($task->attachments() as $attachment)
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ $attachment->getUrl() }}" class="q-attach">
                            @if($attachment->hasGeneratedConversion('thumbnail'))
                                <img class="q-attach__preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                            @else
                                <span class="q-attach__preview q-attach__preview--icon">
                                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use></svg>
                                </span>
                            @endif
                            <span class="min-w-0">
                                <span class="q-attach__name text-truncate d-block">{{ $attachment->file_name }}</span>
                                <span class="q-attach__size">{{ $attachment->human_readable_size }}</span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
