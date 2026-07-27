<div class="q-comment">
    @include('partials.employee_avatar', ['employee' => $comment->employee])

    <div class="q-comment__head">
        <div class="q-comment__meta">
            <span class="q-comment__author">{{ $comment->employee->person->name }}</span>
            <span class="q-comment__date q-mono">
                <span class="d-none d-md-inline">
                    {{ $comment->created_at->format('d.m.Y · H:i') }}
                    @if($comment->created_at->lt($comment->updated_at))
                        <svg class="icon-bs icon-12 ms-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        {{ $comment->updated_at->format('d.m.Y · H:i') }}
                    @endif
                </span>
                <span class="d-md-none">
                    @if($comment->created_at->lt($comment->updated_at))
                        {{ $comment->created_at->format('d.m·H:i') }}
                        <svg class="icon-bs icon-12 ms-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        {{ $comment->updated_at->format('d.m·H:i') }}
                    @else
                        {{ $comment->created_at->format('d.m.Y·H:i') }}
                    @endif
                </span>
            </span>
        </div>

        @if(auth()->user()->can('update', $comment) || auth()->user()->can('delete', $comment))
            <div class="dropdown ms-auto d-none d-md-block">
                <button class="q-kebab" type="button" id="commentOverviewDropdown-{{ $comment->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="commentOverviewDropdown-{{ $comment->id }}">
                    @can('update', $comment)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('comments.edit', $comment) }}">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('delete', $comment)
                        <form action="{{ route('comments.destroy', $comment) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                Entfernen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            <button class="q-kebab ms-auto d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#commentActionsSheet-{{ $comment->id }}" aria-controls="commentActionsSheet-{{ $comment->id }}" aria-label="Aktionen">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
            </button>
        @endif
    </div>

    <div class="q-comment__body">
        <div class="q-comment__bubble">
            <div class="markdown">
                {!! Html::fromMarkdown($comment->comment) !!}
            </div>

            @if($comment->attachments()->count() > 0)
                <div class="row g-2 mt-2">
                    @foreach($comment->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ $attachment->getUrl() }}" class="q-attach">
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="q-attach__preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <span class="q-attach__preview q-attach__preview--icon">
                                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
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
            @endif
        </div>
    </div>

    @if(auth()->user()->can('update', $comment) || auth()->user()->can('delete', $comment))
        <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="commentActionsSheet-{{ $comment->id }}" aria-label="Aktionen">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">Aktionen</div>
                @can('update', $comment)
                    <a class="q-row" href="{{ route('comments.edit', $comment) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                        <span class="q-row__title">Bearbeiten</span>
                    </a>
                @endcan
                @can('delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="q-row q-row--danger">
                            <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                            <span class="q-row__title">Entfernen</span>
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @endif
</div>
